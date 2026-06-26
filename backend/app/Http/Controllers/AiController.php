<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AiController extends Controller
{
    private ?string $lastGeminiModel = null;

    public function generatePage(Request $request, Project $project)
    {
        $validated = $request->validate([
            'prompt' => ['required', 'string', 'min:8', 'max:2000'],
            'style' => ['nullable', 'string', 'max:80'],
        ]);

        $prompt = trim($validated['prompt']);
        $style = trim($validated['style'] ?? 'Pro-grade minimalist');
        $systemPrompt = $this->pageGenerationPrompt($prompt, $style);
        $model = config('services.gemini.pro_model');

        $generated = $this->askGemini($model, $systemPrompt, true);
        $payload = $generated ? $this->coercePagePayload($generated, $prompt, $style) : $this->fallbackPagePayload($prompt, $style);

        return response()->json([
            ...$payload,
            'project_id' => $project->id,
            'model' => $this->lastGeminiModel ?: $model,
            'provider' => $generated ? 'gemini' : 'local-fallback',
            'steps' => [
                'Reasoning through layout structure...',
                'Mapping prompt to reusable builder sections...',
                'Generating production HTML and CSS...',
                'Preparing SEO metadata and image alt text...',
            ],
        ]);
    }

    public function rewriteCopy(Request $request, Project $project)
    {
        $validated = $request->validate([
            'text' => ['required', 'string', 'max:4000'],
            'mode' => ['required', 'in:rewrite,shorten,professional,extend'],
        ]);

        $text = trim($validated['text']);
        $mode = $validated['mode'];
        $model = config('services.gemini.flash_model');
        $prompt = "Rewrite this page-builder copy. Mode: {$mode}. Return only final copy.\n\n{$text}";
        $generated = $this->askGemini($model, $prompt);

        return response()->json([
            'project_id' => $project->id,
            'model' => $this->lastGeminiModel ?: $model,
            'provider' => $generated ? 'gemini' : 'local-fallback',
            'mode' => $mode,
            'text' => $generated ?: $this->fallbackCopy($text, $mode),
        ]);
    }

    public function seo(Request $request, Project $project)
    {
        $validated = $request->validate([
            'html' => ['nullable', 'string', 'max:30000'],
            'text' => ['nullable', 'string', 'max:12000'],
        ]);

        $source = trim(strip_tags(($validated['text'] ?? '').' '.($validated['html'] ?? '')));
        $source = preg_replace('/\s+/', ' ', $source) ?: $project->name;
        $model = config('services.gemini.flash_model');
        $prompt = "Create JSON SEO metadata for this page. Keys: title, description, og_title, og_description, keywords, alt_texts. Keep title under 60 chars and description under 160 chars.\n\n{$source}";
        $generated = $this->askGemini($model, $prompt, true);
        $payload = $generated ? $this->coerceSeoPayload($generated, $source) : $this->fallbackSeo($source);

        return response()->json([
            ...$payload,
            'project_id' => $project->id,
            'model' => $this->lastGeminiModel ?: $model,
            'provider' => $generated ? 'gemini' : 'local-fallback',
        ]);
    }

    private function askGemini(string $model, string $prompt, bool $expectsJson = false): array|string|null
    {
        @set_time_limit(75);

        $key = config('services.gemini.key');
        if (!$key) return null;
        $this->lastGeminiModel = null;

        foreach ($this->geminiModelCandidates($model) as $candidate) {
            try {
                $generationConfig = ['temperature' => 0.45];
                if ($expectsJson) {
                    $generationConfig['response_mime_type'] = 'application/json';
                }

                $response = Http::connectTimeout(5)
                    ->timeout(45)
                    ->withOptions(['verify' => false])
                    ->acceptJson()
                    ->post("https://generativelanguage.googleapis.com/v1beta/models/{$candidate}:generateContent?key={$key}", [
                    'contents' => [[
                        'parts' => [['text' => $prompt]],
                    ]],
                    'generationConfig' => $generationConfig,
                ]);

                if (!$response->ok()) {
                    continue;
                }

                $text = data_get($response->json(), 'candidates.0.content.parts.0.text');
                if (!is_string($text) || trim($text) === '') {
                    continue;
                }

                if (!$expectsJson) {
                    $this->lastGeminiModel = $candidate;
                    return trim($text);
                }

                $decoded = json_decode($text, true);
                if (is_array($decoded)) {
                    $this->lastGeminiModel = $candidate;
                    return $decoded;
                }
            } catch (\Throwable) {
                continue;
            }
        }

        return null;
    }

    private function geminiModelCandidates(string $model): array
    {
        return array_values(array_unique(array_filter([
            $model,
            'gemini-3.5-flash',
            config('services.gemini.flash_model'),
            'gemini-flash-latest',
            'gemini-2.5-flash',
        ])));
    }

    private function pageGenerationPrompt(string $prompt, string $style): string
    {
        return "Generate a structured visual-builder page as JSON with keys html, css, meta. Use responsive semantic HTML, inline Tailwind-like utility classes allowed, no script tags. Style: {$style}. User prompt: {$prompt}";
    }

    private function coercePagePayload(array $generated, string $prompt, string $style): array
    {
        $fallback = $this->fallbackPagePayload($prompt, $style);

        $html = is_string($generated['html'] ?? null) && trim($generated['html']) !== '' ? trim($generated['html']) : $fallback['html'];
        if (!str_contains(strtolower($html), '<main')) {
            $html = '<main>'.$html.'</main>';
        }

        return [
            'html' => $html,
            'css' => is_string($generated['css'] ?? null) ? $generated['css'] : $fallback['css'],
            'meta' => is_array($generated['meta'] ?? null) ? array_merge($fallback['meta'], $generated['meta']) : $fallback['meta'],
        ];
    }

    private function fallbackPagePayload(string $prompt, string $style): array
    {
        $brand = $this->brandFromPrompt($prompt);
        $isCommerce = Str::contains(Str::lower($prompt), ['shop', 'store', 'product', 'pricing', 'commerce', 'matcha']);
        $accent = $isCommerce ? '#10B981' : '#2E62FF';
        $featureOne = $isCommerce ? 'Fresh sourcing' : 'Visual workflow';
        $featureTwo = $isCommerce ? 'Simple subscriptions' : 'CMS-ready data';
        $featureThree = $isCommerce ? 'Clean checkout' : 'Static export';

        $html = <<<HTML
<main class="w-full bg-[#0B0E14] text-white font-geist">
  <section class="min-h-[620px] px-8 py-20 flex items-center border-b border-[#222530]">
    <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-12 items-center">
      <div>
        <span class="text-[11px] font-bold uppercase tracking-[0.22em]" style="color: {$accent}">Generated with Gemini</span>
        <h1 class="text-5xl md:text-6xl font-black leading-tight mt-5">{$brand}</h1>
        <p class="text-lg text-[#c3c5d8] leading-relaxed mt-5">{$this->sentenceFromPrompt($prompt)}</p>
        <div class="flex flex-wrap gap-3 mt-8">
          <a class="px-6 py-3 rounded text-white font-bold" style="background: {$accent}" href="#pricing">Start building</a>
          <a class="px-6 py-3 rounded border border-[#434656] text-[#dae2fd]" href="#features">Explore sections</a>
        </div>
      </div>
      <div class="bg-[#161B22] border border-[#434656] rounded-lg p-4">
        <img class="w-full aspect-[4/3] object-cover rounded" src="/images/product_cam.png" alt="AI generated hero preview">
      </div>
    </div>
  </section>
  <section id="features" class="px-8 py-16">
    <div class="max-w-6xl mx-auto grid md:grid-cols-3 gap-5">
      <article class="bg-[#161B22] border border-[#434656] rounded-lg p-6"><div class="text-3xl mb-4" style="color: {$accent}">01</div><h3 class="text-xl font-bold">{$featureOne}</h3><p class="text-[#8d90a2] text-sm mt-2">Clear section structure, production spacing, and reusable builder-friendly markup.</p></article>
      <article class="bg-[#161B22] border border-[#434656] rounded-lg p-6"><div class="text-3xl mb-4" style="color: {$accent}">02</div><h3 class="text-xl font-bold">{$featureTwo}</h3><p class="text-[#8d90a2] text-sm mt-2">Content blocks ready for CMS binding, SEO tuning, and visual edits.</p></article>
      <article class="bg-[#161B22] border border-[#434656] rounded-lg p-6"><div class="text-3xl mb-4" style="color: {$accent}">03</div><h3 class="text-xl font-bold">{$featureThree}</h3><p class="text-[#8d90a2] text-sm mt-2">Optimized layout patterns for fast publishing and clean static exports.</p></article>
    </div>
  </section>
  <section id="pricing" class="px-8 py-16 bg-white text-slate-950">
    <div class="max-w-5xl mx-auto text-center">
      <h2 class="text-4xl font-black">Launch-ready offer</h2>
      <p class="text-slate-600 mt-3">Generated pricing section with editable cards and clear conversion path.</p>
      <div class="grid md:grid-cols-3 gap-5 mt-8 text-left">
        <article class="border border-slate-200 rounded-lg p-6"><h3 class="font-bold text-lg">Starter</h3><p class="text-3xl font-black mt-4">$19</p><p class="text-slate-600 text-sm mt-2">Single campaign page.</p></article>
        <article class="border-2 rounded-lg p-6" style="border-color: {$accent}"><h3 class="font-bold text-lg">Growth</h3><p class="text-3xl font-black mt-4">$49</p><p class="text-slate-600 text-sm mt-2">CMS, forms, and commerce blocks.</p></article>
        <article class="border border-slate-200 rounded-lg p-6"><h3 class="font-bold text-lg">Scale</h3><p class="text-3xl font-black mt-4">$99</p><p class="text-slate-600 text-sm mt-2">Export workflow and team review.</p></article>
      </div>
    </div>
  </section>
</main>
HTML;

        return [
            'html' => $html,
            'css' => 'main { scroll-behavior: smooth; } img { max-width: 100%; }',
            'meta' => $this->fallbackSeo($brand.' '.$prompt),
        ];
    }

    private function fallbackCopy(string $text, string $mode): string
    {
        $clean = trim(preg_replace('/\s+/', ' ', strip_tags($text)));
        if ($mode === 'shorten') return Str::limit($clean, 120, '.');
        if ($mode === 'extend') return $clean.' Designed for faster launch cycles, clearer user journeys, and measurable business outcomes.';
        if ($mode === 'professional') return 'Create a polished experience that helps teams move faster, communicate clearly, and convert visitors with confidence.';
        return 'Build a sharper, more focused experience with clear messaging, stronger hierarchy, and production-ready page structure.';
    }

    private function fallbackSeo(string $source): array
    {
        $clean = trim(preg_replace('/\s+/', ' ', $source));
        $title = Str::limit(Str::headline(Str::words($clean, 7, '')), 58, '');
        $description = Str::limit($clean, 155, '.');

        return [
            'title' => $title ?: 'StudioPro AI Generated Page',
            'description' => $description ?: 'AI-assisted visual page built with StudioPro.',
            'og_title' => $title ?: 'StudioPro AI Page',
            'og_description' => $description ?: 'AI-assisted visual page built with StudioPro.',
            'keywords' => ['design', 'AI', 'page builder', 'conversion'],
            'alt_texts' => ['AI generated hero image', 'Product preview image', 'Feature section illustration'],
        ];
    }

    private function coerceSeoPayload(array $generated, string $source): array
    {
        $fallback = $this->fallbackSeo($source);

        return [
            'title' => Str::limit((string) ($generated['title'] ?? $fallback['title']), 60, ''),
            'description' => Str::limit((string) ($generated['description'] ?? $fallback['description']), 160, ''),
            'og_title' => (string) ($generated['og_title'] ?? $generated['title'] ?? $fallback['og_title']),
            'og_description' => (string) ($generated['og_description'] ?? $generated['description'] ?? $fallback['og_description']),
            'keywords' => is_array($generated['keywords'] ?? null) ? array_values($generated['keywords']) : $fallback['keywords'],
            'alt_texts' => is_array($generated['alt_texts'] ?? null) ? array_values($generated['alt_texts']) : $fallback['alt_texts'],
        ];
    }

    private function brandFromPrompt(string $prompt): string
    {
        if (preg_match('/for (?:an?|the)?\s*([^,.]+?)(?: with| that|$)/i', $prompt, $match)) {
            return Str::headline(trim($match[1]));
        }

        return Str::headline(Str::words($prompt, 5, ''));
    }

    private function sentenceFromPrompt(string $prompt): string
    {
        $clean = trim(preg_replace('/\s+/', ' ', $prompt));
        return Str::finish(Str::ucfirst($clean), '.');
    }
}
