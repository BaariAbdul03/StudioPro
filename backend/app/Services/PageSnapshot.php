<?php

namespace App\Services;

use App\Models\Page;
use App\Models\Project;
use DOMDocument;
use DOMElement;
use DOMXPath;
use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Validation\ValidationException;
use Sabberworm\CSS\CSSList\AtRuleBlockList;
use Sabberworm\CSS\CSSList\CSSList;
use Sabberworm\CSS\OutputFormat;
use Sabberworm\CSS\Parser;
use Sabberworm\CSS\Parsing\SourceException;
use Sabberworm\CSS\Property\Declaration;
use Sabberworm\CSS\RuleSet\DeclarationBlock;

class PageSnapshot
{
    private const IMAGE_EXTENSIONS = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/webp' => 'webp',
    ];

    public function build(Project $project, Page $page): array
    {
        $dom = new DOMDocument;
        $dom->loadHTML('<!doctype html><html><head><meta charset="utf-8"></head><body>'
            .$this->purify($page->html ?? '').'</body></html>', LIBXML_NONET | LIBXML_NOERROR | LIBXML_NOWARNING);

        $assets = [];
        $library = [];
        $builtIns = [];
        $bytes = 0;
        $xpath = new DOMXPath($dom);

        foreach ($xpath->query('//*[@style]') as $node) {
            $declarations = $this->cleanInlineStyle($node);
            $node->removeAttribute('style');

            if ($declarations !== '') {
                $node->setAttribute('style', $declarations);
            }
        }

        foreach ($xpath->query('//img') as $image) {
            $source = $image->getAttribute('src');
            if (preg_match('~\Ahttps://images\.unsplash\.com/photo-(\d+-[a-f0-9]+)(?:\?[^#]*)?\z~i', $source, $photo)) {
                $source = '/images/template-'.strtolower($photo[1]).'.webp';
                $image->setAttribute('src', $source);
            }
            if (preg_match('~\A/api/projects/'.$project->id.'/assets/(\d+)/file\z~', $source, $matches)) {
                $id = (int) $matches[1];
                $asset = $library[$id] ??= $project->assets()->find($id) ?: false;
                $extension = $asset ? (self::IMAGE_EXTENSIONS[$asset->mime_type] ?? null) : null;

                if ($extension && preg_match('~\Aprojects/'.$project->id.'/[A-Za-z0-9_.-]+\z~', $asset->path)) {
                    $path = 'images/'.hash('sha256', $asset->path).'.'.$extension;
                    if (! isset($assets[$path])) {
                        $assets[$path] = $asset;
                        $bytes += $asset->size;
                    }
                    $image->setAttribute('src', '/'.$path);

                    continue;
                }
            } elseif (preg_match('~\A/images/([A-Za-z0-9_-]+\.(?:jpe?g|png|gif|webp))\z~i', $source)) {
                $file = public_path(ltrim($source, '/'));
                if (! is_file($file)) {
                    $file = base_path('../frontend/public'.$source);
                }
                if (is_file($file)) {
                    $builtIns[ltrim($source, '/')] = $file;

                    continue;
                }
            }

            // Keep the authored alternative text when the source is unavailable or unapproved.
            $image->replaceWith($dom->createTextNode($image->getAttribute('alt')));
        }

        if ($bytes > 25 * 1024 * 1024 || count($assets) > 40) {
            throw ValidationException::withMessages(['html' => 'A page can release up to 40 uploaded images totaling 25 MB.']);
        }

        return [
            'body' => $dom->getElementsByTagName('body')->item(0),
            'css' => $this->canvasCss()."\n".$this->cleanCss($page->css ?? ''),
            'assets' => $assets,
            'builtIns' => $builtIns,
        ];
    }

    public function document(Page $page, array $snapshot, ?string $publishedImageBase = null): string
    {
        $body = $snapshot['body']->cloneNode(true);
        $imageBase = $publishedImageBase === null ? './' : rtrim($publishedImageBase, '/').'/';
        foreach ($body->getElementsByTagName('img') as $image) {
            $key = ltrim($image->getAttribute('src'), '/');
            if (isset($snapshot['assets'][$key]) || $publishedImageBase === null) {
                $image->setAttribute('src', $imageBase.$key);
            }
        }

        $bodyHtml = '';
        foreach ($body->childNodes as $child) {
            $bodyHtml .= $body->ownerDocument->saveHTML($child);
        }

        $meta = $page->meta ?? [];
        $title = e($meta['seoTitle'] ?? $page->title);
        $description = e($meta['seoDescription'] ?? '');
        $ogTitle = e($meta['ogTitle'] ?? $meta['seoTitle'] ?? $page->title);
        $ogDescription = e($meta['ogDescription'] ?? $meta['seoDescription'] ?? '');
        $imageOrigin = str_starts_with($publishedImageBase ?? '', 'https://')
            ? e(rtrim($publishedImageBase, '/').'/')
            : '';
        $stylesheet = $publishedImageBase === null
            ? '<link rel="stylesheet" href="./style.css">'
            : '<style>'.$snapshot['css'].'</style>';

        return <<<HTML
            <!doctype html>
            <html lang="en">
            <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta http-equiv="Content-Security-Policy" content="default-src 'none'; img-src 'self' {$imageOrigin}; style-src 'self' 'unsafe-inline'; base-uri 'none'; form-action 'none'">
            <title>{$title}</title>
            <meta name="description" content="{$description}">
            <meta property="og:title" content="{$ogTitle}">
            <meta property="og:description" content="{$ogDescription}">
            {$stylesheet}
            </head>
            <body>{$bodyHtml}</body>
            </html>
            HTML;
    }

    private function purify(string $html): string
    {
        $config = HTMLPurifier_Config::createDefault();
        $config->set('Cache.DefinitionImpl', null);
        $config->set('Attr.EnableID', true);
        $config->set('URI.AllowedSchemes', ['http' => true, 'https' => true, 'mailto' => true, 'tel' => true]);
        $config->set('HTML.Allowed', 'a[href|title],article,aside,b,blockquote,br,button,caption,code,dd,details,div,dl,dt,em,figcaption,figure,footer,h1,h2,h3,h4,h5,h6,header,hr,i,img[src|alt|width|height],li,main,nav,ol,p,pre,section,small,span,strong,summary,table,tbody,td,th,thead,tr,u,ul,*[class|id|style]');
        $config->set('CSS.AllowedProperties', ['background', 'background-color', 'border', 'color', 'font-family', 'font-size', 'font-style', 'font-weight', 'height', 'line-height', 'margin', 'max-width', 'padding', 'text-align', 'text-decoration', 'width']);
        $definition = $config->getHTMLDefinition(true);

        foreach (['article', 'aside', 'details', 'footer', 'header', 'main', 'nav', 'section', 'figure', 'figcaption'] as $element) {
            $definition->addElement($element, 'Block', 'Flow', 'Common');
        }
        $definition->addElement('button', 'Inline', 'Inline', 'Common');
        $definition->addElement('summary', 'Block', 'Inline', 'Common');

        return (new HTMLPurifier($config))->purify($html);
    }

    private function canvasCss(): string
    {
        // Packaged as a static app asset in Docker and available from the source tree in local PHP runs.
        return implode("\n", array_map(static function (string $name): string {
            $path = public_path($name);
            if (! is_file($path)) {
                $path = base_path('../frontend/public/'.$name);
            }

            return is_file($path) ? file_get_contents($path) : '';
        }, ['template-utilities.css', 'canvas.css']));
    }

    private function cleanInlineStyle(DOMElement $node): string
    {
        $value = $node->getAttribute('style');
        if (strpbrk($value, '{}') !== false) {
            return '';
        }

        $rule = $this->cleanCss('p{'.$value.'}');

        return substr($rule, 2, -1);
    }

    private function cleanCss(string $css): string
    {
        if (strlen($css) > 524288 || ! $this->boundedNesting($css)) {
            throw ValidationException::withMessages(['css' => 'The page stylesheet is too complex.']);
        }

        try {
            $document = (new Parser($css))->parse();
            $this->filterCss($document);
            $result = $document->render(OutputFormat::createCompact());
        } catch (SourceException) {
            throw ValidationException::withMessages(['css' => 'The page stylesheet is invalid.']);
        }

        // Styles are embedded in a raw-text HTML element. Escape any closing tag delimiter.
        return str_replace('<', '\\3c ', $result);
    }

    private function boundedNesting(string $css): bool
    {
        preg_match_all('/[{}()]/', $css, $delimiters);
        $depth = 0;

        foreach ($delimiters[0] as $delimiter) {
            $depth = max(0, $depth + ($delimiter === '{' || $delimiter === '(' ? 1 : -1));
            if ($depth > 24) {
                return false;
            }
        }

        return true;
    }

    private function filterCss(CSSList $list, int $depth = 0): void
    {
        foreach ($list->getContents() as $item) {
            if ($item instanceof DeclarationBlock) {
                foreach ($item->getDeclarations() as $declaration) {
                    if (! $this->safeDeclaration($declaration)) {
                        $item->removeDeclaration($declaration);
                    }
                }
            } elseif ($depth < 4 && $item instanceof AtRuleBlockList && strtolower($item->atRuleName()) === 'media') {
                $this->filterCss($item, $depth + 1);
            } else {
                $list->remove($item);
            }
        }
    }

    private function safeDeclaration(Declaration $declaration): bool
    {
        if (in_array(strtolower($declaration->getPropertyName()), ['behavior', '-moz-binding'], true)) {
            return false;
        }

        $value = $declaration->render(OutputFormat::createCompact());
        if (strpbrk($value, '\\{}<>') !== false) {
            return false;
        }

        preg_match_all('/([a-z_-][a-z0-9_-]*)\s*\(/i', $value, $calls);

        return array_diff(array_map('strtolower', $calls[1]), [
            'calc', 'clamp', 'min', 'max', 'var', 'minmax', 'repeat', 'fit-content', 'rgb', 'rgba', 'hsl', 'hsla',
            'oklab', 'oklch', 'linear-gradient', 'radial-gradient', 'conic-gradient', 'translate', 'translatex',
            'translatey', 'rotate', 'scale', 'skew', 'cubic-bezier', 'steps', 'blur', 'brightness', 'saturate',
        ]) === [];
    }
}
