<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Project;
use Illuminate\Support\Facades\Response;
use ZipArchive;

class PageExportController extends Controller
{
    public function download(Project $project, Page $page)
    {
        abort_unless($project->user_id === auth()->id(), 403);
        abort_unless($page->project_id === $project->id, 404);
        abort_unless(class_exists(ZipArchive::class), 500, 'ZipArchive extension is required for static exports.');

        $meta = $page->meta ?? [];
        $exportId = 'page-export-'.$project->id.'-'.$page->id.'-'.now()->format('YmdHis');
        $directory = storage_path('app/exports/'.$exportId);
        $zipPath = storage_path('app/exports/'.$exportId.'.zip');

        if (! is_dir($directory.'/assets')) {
            mkdir($directory.'/assets', 0775, true);
        }

        try {
            $this->copyPublicAssets($directory);

            $optimizedImages = $this->createWebpSidecars($directory.'/assets/images');

            file_put_contents($directory.'/index.html', $this->minifyHtml($this->buildHtml($project, $page, $meta)));
            file_put_contents($directory.'/style.css', $this->buildCss($page));
            file_put_contents($directory.'/runtime.js', $this->minifyJs($this->runtimeScript($project, $page)));
            file_put_contents($directory.'/stripe-checkout.js', $this->minifyJs($this->checkoutScript()));
            file_put_contents($directory.'/README.txt', "Static export for {$project->name} / {$page->title}\nGenerated: ".now()->toDateTimeString()."\nOptimized WebP sidecars: {$optimizedImages}\n");

            $zip = new ZipArchive();
            $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($directory, \FilesystemIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $file) {
                if (! $file->isFile()) {
                    continue;
                }

                $relativePath = str_replace('\\', '/', substr($file->getPathname(), strlen($directory) + 1));
                $zip->addFile($file->getPathname(), $relativePath);
            }

            $zip->close();
        } finally {
            // Always clean up the temp directory, even on failure
            $this->deleteDirectory($directory);
        }

        return Response::download($zipPath, $project->slug.'-'.$page->slug.'-static.zip')->deleteFileAfterSend(true);
    }

    private function buildHtml(Project $project, Page $page, array $meta): string
    {
        $title = e($meta['seoTitle'] ?? $page->title ?? $project->name);
        $description = e($meta['seoDescription'] ?? $project->description ?? '');
        $ogTitle = e($meta['ogTitle'] ?? $meta['seoTitle'] ?? $page->title ?? $project->name);
        $ogDescription = e($meta['ogDescription'] ?? $meta['seoDescription'] ?? $project->description ?? '');
        $headCode = $meta['headCode'] ?? '';
        $bodyCode = $meta['bodyCode'] ?? '';
        $html = str_replace(
            ['src="/images/', "src='/images/"],
            ['src="./assets/images/', "src='./assets/images/"],
            $page->html ?? ''
        );

        return '<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>'.$title.'</title>
  <meta name="description" content="'.$description.'">
  <meta property="og:title" content="'.$ogTitle.'">
  <meta property="og:description" content="'.$ogDescription.'">
  <meta property="og:type" content="website">
  <link rel="stylesheet" href="./style.css">
  '.$headCode.'
</head>
<body>
'.$html.'
'.$bodyCode.'
<script>window.STUDIOPRO_PROJECT_ID='.$project->id.'; window.STUDIOPRO_API_BASE_URL="'.rtrim(url('/'), '/').'";</script>
<script src="./runtime.js"></script>
<script src="./stripe-checkout.js"></script>
</body>
</html>';
    }

    private function buildCss(Page $page): string
    {
        $canvasCssPath = base_path('../frontend/public/canvas.css');
        $canvasCss = is_file($canvasCssPath) ? file_get_contents($canvasCssPath) : '';

        return trim($canvasCss."\n\n".$this->minifyCss($page->css ?? ''));
    }

    private function copyPublicAssets(string $directory): void
    {
        $source = base_path('../frontend/public/images');
        $target = $directory.'/assets/images';

        if (! is_dir($source)) {
            return;
        }

        if (! is_dir($target)) {
            mkdir($target, 0775, true);
        }

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($source, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            if (! $file->isFile()) {
                continue;
            }

            $relativePath = substr($file->getPathname(), strlen($source) + 1);
            $destination = $target.'/'.str_replace('\\', '/', $relativePath);
            $destinationDir = dirname($destination);

            if (! is_dir($destinationDir)) {
                mkdir($destinationDir, 0775, true);
            }

            copy($file->getPathname(), $destination);
        }
    }

    private function minifyCss(string $css): string
    {
        $css = preg_replace('/\/\*.*?\*\//s', '', $css) ?? $css;
        $css = preg_replace('/\s+/', ' ', $css) ?? $css;

        return trim($css);
    }

    private function deleteDirectory(string $dir): void
    {
        if (! is_dir($dir)) {
            return;
        }

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $file) {
            if ($file->isDir()) {
                @rmdir($file->getPathname());
            } else {
                @unlink($file->getPathname());
            }
        }

        @rmdir($dir);
    }

    private function minifyHtml(string $html): string
    {
        $html = preg_replace('/>\s+</', '><', $html) ?? $html;
        $html = preg_replace('/\s{2,}/', ' ', $html) ?? $html;

        return trim($html);
    }

    private function minifyJs(string $js): string
    {
        $js = preg_replace('/^\s*\/\/.*$/m', '', $js) ?? $js;
        $js = preg_replace('/\s+/', ' ', $js) ?? $js;

        return trim($js);
    }

    private function createWebpSidecars(string $imageDirectory): int
    {
        if (! is_dir($imageDirectory) || ! function_exists('imagewebp')) {
            return 0;
        }

        $count = 0;
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($imageDirectory, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            if (! $file->isFile()) {
                continue;
            }

            $path = $file->getPathname();
            $extension = strtolower($file->getExtension());
            $image = match ($extension) {
                'jpg', 'jpeg' => function_exists('imagecreatefromjpeg') ? @imagecreatefromjpeg($path) : false,
                'png' => function_exists('imagecreatefrompng') ? @imagecreatefrompng($path) : false,
                default => false,
            };

            if (! $image) {
                continue;
            }

            imagepalettetotruecolor($image);
            imagealphablending($image, true);
            imagesavealpha($image, true);

            if (@imagewebp($image, preg_replace('/\.(jpe?g|png)$/i', '.webp', $path), 82)) {
                $count++;
            }

            imagedestroy($image);
        }

        return $count;
    }

    private function runtimeScript(Project $project, Page $page): string
    {
        $endpoint = url('/api/projects/'.$project->id.'/form-submissions');

        return <<<JS
(() => {
  const runAnimation = (el) => {
    const action = el.dataset.interactionAction || "move";
    const duration = Number(el.dataset.interactionDuration || 500);
    const delay = Number(el.dataset.interactionDelay || 0);
    const easing = el.dataset.interactionEasing || "ease-out";
    const moveX = Number(el.dataset.interactionMoveX || 0);
    const moveY = Number(el.dataset.interactionMoveY || -16);
    const keyframes = {
      move: [{ transform: "translate(0, 0)" }, { transform: `translate(\${moveX}px, \${moveY}px)` }, { transform: "translate(0, 0)" }],
      scale: [{ transform: "scale(1)" }, { transform: "scale(1.05)" }, { transform: "scale(1)" }],
      rotate: [{ transform: "rotate(0deg)" }, { transform: "rotate(3deg)" }, { transform: "rotate(0deg)" }],
      opacity: [{ opacity: 1 }, { opacity: 0.45 }, { opacity: 1 }],
      blur: [{ filter: "blur(0)" }, { filter: "blur(3px)" }, { filter: "blur(0)" }]
    }[action] || [];
    if (keyframes.length && el.animate) el.animate(keyframes, { duration, delay, easing });
  };

  document.querySelectorAll("[data-interaction-trigger]").forEach((el) => {
    const trigger = el.dataset.interactionTrigger;
    if (trigger === "page-load") runAnimation(el);
    if (trigger === "click") el.addEventListener("click", () => runAnimation(el));
    if (trigger === "hover") el.addEventListener("mouseenter", () => runAnimation(el));
    if (trigger === "scroll-into-view" && "IntersectionObserver" in window) {
      const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            runAnimation(el);
            observer.disconnect();
          }
        });
      }, { threshold: 0.35 });
      observer.observe(el);
    }
  });

  document.querySelectorAll("form[data-studiopro-form]").forEach((form) => {
    form.addEventListener("submit", async (event) => {
      event.preventDefault();
      const payload = Object.fromEntries(new FormData(form).entries());
      await fetch("$endpoint", {
        method: "POST",
        headers: { "Content-Type": "application/json", "Accept": "application/json" },
        body: JSON.stringify({
          page_id: {$page->id},
          form_name: form.dataset.formName || "Contact Form",
          payload,
          source_url: location.href
        })
      });
      form.dispatchEvent(new CustomEvent("studiopro:submitted", { bubbles: true }));
      form.reset();
    });
  });
})();
JS;
    }

    private function checkoutScript(): string
    {
        return 'window.StudioProCheckout = { start(payload) { const data = Array.isArray(payload) ? { items: payload } : (payload || {}); data.project_id = data.project_id || window.STUDIOPRO_PROJECT_ID; data.customer_email = data.customer_email || "customer@example.com"; const apiBase = (data.apiBaseUrl || window.STUDIOPRO_API_BASE_URL || "").replace(/\/$/, ""); return fetch(apiBase + "/api/checkout/session", { method: "POST", headers: { "Content-Type": "application/json", "Accept": "application/json" }, body: JSON.stringify(data) }); } };';
    }
}
