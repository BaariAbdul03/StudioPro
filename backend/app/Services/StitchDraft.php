<?php

namespace App\Services;

use Illuminate\Support\Facades\Process;
use Illuminate\Support\Str;
use RuntimeException;

class StitchDraft
{
    private const MAX_DOWNLOAD = 384_000;

    private const DOWNLOAD_HOSTS = [
        'contribution.usercontent.google.com',
        'storage.googleapis.com',
    ];

    public static function configured(): bool
    {
        return config('studio.ai_enabled') && config('studio.stitch_enabled') && filled(config('services.stitch.key'))
            && preg_match('/\A[0-9]{1,24}\z/', (string) config('services.stitch.project_id')) === 1;
    }

    public function generate(string $prompt): array
    {
        $projectId = (string) config('services.stitch.project_id');
        $result = Process::path(base_path())->timeout(140)->env([
            'STITCH_API_KEY' => (string) config('services.stitch.key'),
            'STITCH_PROJECT_ID' => $projectId,
            'STITCH_PROMPT' => $prompt,
        ])->run(['node', 'scripts/stitch-bridge.js']);

        $screen = $result->successful() ? json_decode($result->output(), true) : null;
        if (! is_string($screen['screenId'] ?? null) || ! preg_match('/\A[a-z0-9_-]{1,64}\z/i', $screen['screenId'])) {
            throw new RuntimeException('Stitch did not return a screen.');
        }

        $data = [
            'stitch_url' => 'https://stitch.withgoogle.com/projects/'.$projectId,
            'screen_id' => $screen['screenId'],
        ];
        $html = $this->download((string) ($screen['downloadUrl'] ?? ''));
        if ($html === null) {
            return $data;
        }

        $doc = new \DOMDocument;
        if (! $doc->loadHTML('<?xml encoding="UTF-8">'.$html, LIBXML_NONET | LIBXML_NOERROR | LIBXML_NOWARNING)) {
            return $data;
        }

        $body = $doc->getElementsByTagName('body')->item(0);
        if (! $body?->hasChildNodes()) {
            return $data;
        }

        $css = '';
        foreach ($doc->getElementsByTagName('style') as $style) {
            $css .= $style->textContent."\n";
        }
        $markup = '';
        foreach ($body->childNodes as $node) {
            $markup .= $doc->saveHTML($node);
        }

        $title = Str::limit(trim($doc->getElementsByTagName('title')->item(0)?->textContent ?? ''), 60, '');

        return $data + [
            'provider' => 'stitch',
            'html' => $markup,
            'css' => $css,
            'meta' => ['title' => $title],
        ];
    }

    private function download(string $url): ?string
    {
        $parts = parse_url($url);
        if (($parts['scheme'] ?? null) !== 'https' || ! in_array($parts['host'] ?? '', self::DOWNLOAD_HOSTS, true)
            || isset($parts['user']) || isset($parts['pass']) || isset($parts['port'])) {
            return null;
        }

        $host = $parts['host'];
        $ip = collect(gethostbynamel($host) ?: [])->first(
            static fn ($address) => filter_var($address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_GLOBAL_RANGE) !== false,
        );
        if ($ip === null) {
            return null;
        }

        $html = '';
        $request = curl_init($url);
        curl_setopt_array($request, [
            CURLOPT_PROTOCOLS => CURLPROTO_HTTPS,
            CURLOPT_RESOLVE => ["{$host}:443:{$ip}"],
            CURLOPT_PROXY => '',
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_WRITEFUNCTION => static function ($handle, string $chunk) use (&$html): int {
                if (strlen($html) + strlen($chunk) > self::MAX_DOWNLOAD) {
                    return 0;
                }
                $html .= $chunk;

                return strlen($chunk);
            },
        ]);
        $successful = curl_exec($request);
        $status = curl_getinfo($request, CURLINFO_RESPONSE_CODE);

        return $successful && $status === 200 && trim($html) !== '' ? $html : null;
    }
}
