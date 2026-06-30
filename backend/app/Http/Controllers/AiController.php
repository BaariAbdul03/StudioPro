<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Str;

class AiController extends Controller
{
    private ?string $lastGeminiModel = null;

    public function generatePage(Request $request, Project $project)
    {
        abort_unless($project->user_id === auth()->id(), 403);

        $validated = $request->validate([
            'prompt' => ['required', 'string', 'min:8', 'max:2000'],
            'style' => ['nullable', 'string', 'max:80'],
        ]);

        $prompt = trim($validated['prompt']);
        $style = trim($validated['style'] ?? 'Minimal');

        $styleInstructions = [
            'Minimal' => 'Minimal style: Clean layout, generous white space, simple typography, subtle accents, ultra-clean design.',
            'Brutalist' => 'Brutalist style: Raw typography, high-contrast borders, bold colors, blocky shapes, structural grids, unpolished industrial layout.',
            'Corporate' => 'Corporate style: Professional layouts, trustworthy grid systems, standard clean typography, dark blue/gray accents, polished enterprise design.',
            'Neo-Retrospective' => 'Neo-Retrospective style: Retro color palette, vintage typography vibes combined with modern layouts, warm tones, nostalgic styling.',
            'Glassmorphism' => 'Glassmorphism style: Frosted glass backgrounds using backdrop-blur effects, smooth glowing shadows, semi-transparent panels, modern glass panels.',
            'Cyberpunk' => 'Cyberpunk style: High-contrast neon colors (cyan, magenta), dark futuristic backgrounds, glowing text and border shadows, tech grid patterns.',
        ];

        $styleInstruction = $styleInstructions[$style] ?? "Style: {$style}";
        $fullPrompt = "Please design a webpage strictly following this style preference:\n{$styleInstruction}\n\nUser prompt description:\n{$prompt}";

        $stitchKey = config('services.stitch.key');
        $stitchProjectId = config('services.stitch.project_id');
        $payload = null;
        $provider = null;
        $model = null;
        $debugStitchOutput = null;

        if ($stitchKey && $stitchProjectId) {
            set_time_limit(180);

            $nodeBinary = env('NODE_BINARY', 'C:\\Program Files\\nodejs\\node.exe');
            if (!file_exists($nodeBinary)) {
                $nodeBinary = 'node';
            }

            putenv("STITCH_API_KEY=" . $stitchKey);
            putenv("STITCH_PROJECT_ID=" . $stitchProjectId);
            putenv("STITCH_PROMPT=" . $fullPrompt);

            $scriptPath = escapeshellarg(base_path('scripts/stitch-bridge.js'));
            $nodeBinEscaped = escapeshellarg($nodeBinary);

            // Execute via shell_exec to avoid CSPRNG crashes on Windows background tasks
            $output = shell_exec("{$nodeBinEscaped} {$scriptPath} 2>&1");
            $debugStitchOutput = $output;

            if ($output) {
                $decoded = json_decode($output, true);
                if (is_array($decoded) && isset($decoded['html'])) {
                    $html = $decoded['html'];
                    if (!str_contains(strtolower($html), '<main')) {
                        $html = '<main>' . $html . '</main>';
                    }
                    $payload = [
                        'html' => $html,
                        'css' => $decoded['css'] ?? '',
                        'tailwindConfigScript' => $decoded['tailwindConfigScript'] ?? '',
                        'meta' => $this->fallbackSeo($prompt),
                    ];
                    $provider = 'google-stitch';
                    $model = 'google-stitch';
                } else {
                    \Illuminate\Support\Facades\Log::warning("Stitch generation bridge failed output: " . $output);
                }
            }
        }

        if (!$payload) {
            $geminiKey = config('services.gemini.key');
            if ($geminiKey) {
                $model = config('services.gemini.pro_model');
                $systemPrompt = $this->pageGenerationPrompt($prompt, $style);
                $generated = $this->askGemini($model, $systemPrompt, true);
                if ($generated) {
                    $payload = $this->coercePagePayload($generated, $prompt, $style);
                    $provider = 'gemini';
                    $model = $this->lastGeminiModel ?: $model;
                }
            }
        }

        if (!$payload) {
            $payload = $this->fallbackPagePayload($prompt, $style);
            $provider = 'local-fallback';
            $model = 'local-fallback';
        }

        return response()->json([
            ...$payload,
            'project_id' => $project->id,
            'model' => $model ?: 'local-fallback',
            'provider' => $provider ?: 'local-fallback',
            'diagnostics' => [
                'stitch_configured' => ($stitchKey && $stitchProjectId) ? 'yes' : 'no',
                'gemini_configured' => config('services.gemini.key') ? 'yes' : 'no',
                'stitch_shell_output' => $debugStitchOutput,
            ],
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
        abort_unless($project->user_id === auth()->id(), 403);

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
        abort_unless($project->user_id === auth()->id(), 403);

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
        set_time_limit(120);

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
                    ->timeout(60)
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
            config('services.gemini.flash_model'),
            'gemini-2.5-flash',
            'gemini-2.0-flash',
            'gemini-1.5-flash',
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
        $isSaaS = Str::contains(Str::lower($prompt), ['saas', 'software', 'app', 'visual editor', 'platform', 'editor', 'builder']);
        if ($isSaaS) {
            return $this->saasFallbackPayload($prompt, $style);
        }

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
        <span class="text-[11px] font-bold uppercase tracking-[0.22em]" style="color: {$accent}">Generated with Google Stitch</span>
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

    private function saasFallbackPayload(string $prompt, string $style): array
    {
        $brand = $this->brandFromPrompt($prompt);
        $html = <<<HTML
<main class="w-full bg-[#030712] text-white font-geist scroll-behavior-smooth">
  <nav class="flex items-center justify-between px-8 py-4 border-b border-gray-800/60 bg-[#030712]/80 backdrop-blur-md sticky top-0 z-50">
    <div class="flex items-center gap-3">
      <div class="w-8 h-8 rounded bg-gradient-to-tr from-[#2E62FF] to-[#8B5CF6] flex items-center justify-center font-black text-white text-lg">S</div>
      <span class="font-extrabold text-lg tracking-tight">StudioPro SaaS</span>
    </div>
    <div class="hidden md:flex items-center gap-6 text-sm text-[#8d90a2] font-semibold font-geist">
      <a href="#features" class="hover:text-white transition">Features</a>
      <a href="#testimonials" class="hover:text-white transition">Testimonials</a>
      <a href="#pricing" class="hover:text-white transition">Pricing</a>
      <a href="#faq" class="hover:text-white transition">FAQ</a>
    </div>
    <a href="#pricing" class="px-4 py-2 bg-gradient-to-r from-[#2E62FF] to-[#8B5CF6] hover:brightness-110 text-xs font-bold rounded-sm tracking-wider uppercase transition active:scale-95">Get Started</a>
  </nav>

  <section class="relative pt-24 pb-20 px-8 overflow-hidden bg-radial from-[#2E62FF]/10 via-transparent to-transparent">
    <div class="max-w-6xl mx-auto flex flex-col items-center text-center">
      <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[#2E62FF]/10 border border-[#2E62FF]/30 text-xs text-[#2E62FF] font-bold uppercase tracking-widest animate-pulse">
        <span class="w-2 h-2 rounded-full bg-[#2E62FF]"></span> AI Engine Active
      </span>
      <h1 class="text-5xl md:text-7xl font-extrabold leading-none tracking-tight mt-6 bg-clip-text text-transparent bg-gradient-to-r from-white via-[#dae2fd] to-gray-500 max-w-4xl">
        Build visual pages at the speed of thought.
      </h1>
      <p class="text-[#c3c5d8] text-lg md:text-xl font-medium mt-6 max-w-2xl leading-relaxed">
        The ultimate visual canvas for high-performance SaaS marketing pages. Complete with headless GrapesJS, CMS collections, inventory sync, and instant ZIP downloads.
      </p>
      <div class="flex flex-wrap gap-4 mt-8 justify-center">
        <a href="#pricing" class="px-8 py-3 bg-gradient-to-r from-[#2E62FF] to-[#8B5CF6] hover:brightness-110 text-white font-extrabold text-sm rounded shadow-lg shadow-[#2E62FF]/20 active:scale-95 transition-all">Start Free Trial</a>
        <a href="#features" class="px-8 py-3 border border-gray-700 bg-gray-800/40 hover:bg-gray-800 text-white font-extrabold text-sm rounded transition-all">Explore Platform</a>
      </div>
      <div class="mt-16 w-full max-w-5xl rounded-xl border border-gray-800/80 bg-gray-950/60 p-3 shadow-2xl relative">
        <div class="absolute inset-0 bg-gradient-to-t from-gray-950 via-transparent to-transparent z-10"></div>
        <img class="w-full rounded-lg shadow-inner object-cover aspect-[16/9]" src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=1200&q=80" alt="SaaS Visual Editor Analytics Mockup">
      </div>
    </div>
  </section>

  <section id="testimonials" class="py-12 border-y border-gray-800/50 bg-[#060e20]/20 text-center">
    <div class="max-w-6xl mx-auto px-8">
      <p class="text-xs uppercase tracking-[0.2em] font-bold text-gray-500">Trusted by modern development teams worldwide</p>
      <div class="grid grid-cols-2 md:grid-cols-5 gap-8 items-center justify-items-center mt-8 opacity-60">
        <span class="font-extrabold text-lg tracking-wider text-white">STRIPE</span>
        <span class="font-extrabold text-lg tracking-wider text-white">VERCEL</span>
        <span class="font-extrabold text-lg tracking-wider text-white">SUPABASE</span>
        <span class="font-extrabold text-lg tracking-wider text-white">SHOPIFY</span>
        <span class="font-extrabold text-lg tracking-wider text-white">AIRBNB</span>
      </div>
    </div>
  </section>

  <section id="features" class="py-24 px-8 max-w-6xl mx-auto">
    <div class="text-center max-w-2xl mx-auto mb-16">
      <h2 class="text-3xl md:text-4xl font-extrabold text-white">Everything you need to launch campaigns</h2>
      <p class="text-gray-400 mt-4 leading-relaxed">No placeholders, no fluff. A clean visual engineering builder optimized for speed.</p>
    </div>
    <div class="grid md:grid-cols-3 gap-8">
      <article class="group bg-[#0B0E14] border border-gray-800 p-8 rounded-xl hover:border-[#2E62FF]/50 transition-all duration-300 relative overflow-hidden">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-[#2E62FF]/5 rounded-full blur-xl group-hover:bg-[#2E62FF]/10 transition-colors"></div>
        <div class="w-12 h-12 rounded-lg bg-[#2E62FF]/10 text-[#2E62FF] flex items-center justify-center text-2xl font-black mb-6">01</div>
        <h3 class="text-xl font-bold text-white">Visual GrapesJS Core</h3>
        <p class="text-gray-400 text-sm mt-3 leading-relaxed">Direct control over layouts, typography, backgrounds, hover states, and advanced keyframe animations.</p>
      </article>
      <article class="group bg-[#0B0E14] border border-gray-800 p-8 rounded-xl hover:border-[#8B5CF6]/50 transition-all duration-300 relative overflow-hidden">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-[#8B5CF6]/5 rounded-full blur-xl group-hover:bg-[#8B5CF6]/10 transition-colors"></div>
        <div class="w-12 h-12 rounded-lg bg-[#8B5CF6]/10 text-[#8B5CF6] flex items-center justify-center text-2xl font-black mb-6">02</div>
        <h3 class="text-xl font-bold text-white">CMS Bindings</h3>
        <p class="text-gray-400 text-sm mt-3 leading-relaxed">Connect canvas elements to SQLite database collections. Dynamic titles, descriptions, and assets populate runtime lists.</p>
      </article>
      <article class="group bg-[#0B0E14] border border-gray-800 p-8 rounded-xl hover:border-[#10B981]/50 transition-all duration-300 relative overflow-hidden">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-[#10B981]/5 rounded-full blur-xl group-hover:bg-[#10B981]/10 transition-colors"></div>
        <div class="w-12 h-12 rounded-lg bg-[#10B981]/10 text-[#10B981] flex items-center justify-center text-2xl font-black mb-6">03</div>
        <h3 class="text-xl font-bold text-white">Stripe Simulator</h3>
        <p class="text-gray-400 text-sm mt-3 leading-relaxed">Simulate user checkouts. Draft order validation, custom webhooks, and automatic catalog inventory updates.</p>
      </article>
    </div>
  </section>

  <section id="pricing" class="py-20 px-8 bg-gray-950/60 border-t border-gray-850">
    <div class="max-w-5xl mx-auto">
      <div class="text-center max-w-xl mx-auto mb-16">
        <h2 class="text-3xl font-extrabold text-white">Flexible pricing, scales with traffic</h2>
        <p class="text-gray-400 mt-3 leading-relaxed">Start creating visual campaigns. Upgrade as you add domains and collaborative teams.</p>
      </div>
      <div class="grid md:grid-cols-3 gap-6">
        <article class="border border-gray-800 rounded-xl p-8 bg-[#0B0E14] flex flex-col justify-between">
          <div>
            <h3 class="font-bold text-lg text-gray-300">Creator</h3>
            <p class="text-3xl font-extrabold text-white mt-4">$19<span class="text-xs text-gray-500"> /mo</span></p>
            <p class="text-gray-400 text-xs mt-3 leading-relaxed">Single campaign page export, basic forms logs.</p>
            <div class="h-px bg-gray-850 my-6"></div>
            <ul class="space-y-2 text-xs text-gray-400">
              <li class="flex items-center gap-2"><span class="text-[#2E62FF]">✓</span> 1 Published Page</li>
              <li class="flex items-center gap-2"><span class="text-[#2E62FF]">✓</span> Webflow-like Animations</li>
            </ul>
          </div>
          <a href="#pricing" class="mt-8 w-full py-2 bg-gray-800 hover:bg-gray-700 text-white text-center font-bold text-xs rounded transition-all">Choose Plan</a>
        </article>
        <article class="border-2 rounded-xl p-8 bg-[#0B0E14] flex flex-col justify-between relative" style="border-color: #2E62FF">
          <span class="absolute top-0 right-6 -translate-y-1/2 bg-[#2E62FF] text-white text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full">Popular</span>
          <div>
            <h3 class="font-bold text-lg text-white">Developer</h3>
            <p class="text-3xl font-extrabold text-white mt-4">$49<span class="text-xs text-gray-500"> /mo</span></p>
            <p class="text-gray-400 text-xs mt-3 leading-relaxed">Full CMS collections binding, commerce blocks, and simulated webhooks.</p>
            <div class="h-px bg-gray-850 my-6"></div>
            <ul class="space-y-2 text-xs text-gray-300">
              <li class="flex items-center gap-2"><span class="text-[#2E62FF]">✓</span> 10 Published Pages</li>
              <li class="flex items-center gap-2"><span class="text-[#2E62FF]">✓</span> SQLite Database CMS Sync</li>
              <li class="flex items-center gap-2"><span class="text-[#2E62FF]">✓</span> Custom Deploy Webhook</li>
            </ul>
          </div>
          <a href="#pricing" class="mt-8 w-full py-2.5 bg-[#2E62FF] hover:bg-blue-600 text-white text-center font-extrabold text-xs rounded transition-all">Choose Plan</a>
        </article>
        <article class="border border-gray-800 rounded-xl p-8 bg-[#0B0E14] flex flex-col justify-between">
          <div>
            <h3 class="font-bold text-lg text-gray-300">Enterprise</h3>
            <p class="text-3xl font-extrabold text-white mt-4">$99<span class="text-xs text-gray-500"> /mo</span></p>
            <p class="text-gray-400 text-xs mt-3 leading-relaxed">Self-hosting export workflow, infinite forms integrations, and priority support.</p>
            <div class="h-px bg-gray-850 my-6"></div>
            <ul class="space-y-2 text-xs text-gray-400">
              <li class="flex items-center gap-2"><span class="text-[#2E62FF]">✓</span> Unlimited Pages</li>
              <li class="flex items-center gap-2"><span class="text-[#2E62FF]">✓</span> Clean ZIP Export Package</li>
            </ul>
          </div>
          <a href="#pricing" class="mt-8 w-full py-2 bg-gray-800 hover:bg-gray-700 text-white text-center font-bold text-xs rounded transition-all">Choose Plan</a>
        </article>
      </div>
    </div>
  </section>

  <section id="faq" class="py-20 px-8 max-w-4xl mx-auto">
    <h2 class="text-3xl font-extrabold text-center text-white mb-12">Frequently Asked Questions</h2>
    <div class="space-y-4">
      <div class="bg-[#0B0E14] border border-gray-800 p-6 rounded-lg">
        <h4 class="font-bold text-sm text-white">How does local assets persistence work?</h4>
        <p class="text-gray-400 text-xs mt-2 leading-relaxed">Uploaded files are stored locally in the Laravel project’s public storage folders and referenced in the SQLite DB. Any assets uploaded during editor design persist in the DB and are automatically reloaded.</p>
      </div>
      <div class="bg-[#0B0E14] border border-gray-800 p-6 rounded-lg">
        <h4 class="font-bold text-sm text-white">Does the static ZIP export package compile CSS?</h4>
        <p class="text-gray-400 text-xs mt-2 leading-relaxed">Yes! When downloading the export, our backend parses GrapesJS inline components structures, minifies compiled CSS elements, compresses referenced WebP images, and bundles everything into a clean standalone ZIP.</p>
      </div>
    </div>
  </section>

  <footer class="py-12 border-t border-gray-850 text-center text-xs text-gray-500 bg-gray-950/40">
    <p>© 2026 StudioPro Engine. Built with Google Stitch AI.</p>
  </footer>
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
