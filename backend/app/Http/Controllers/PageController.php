<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Project;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Project $project)
    {
        abort_unless($project->user_id === auth()->id(), 403);
        return response()->json($project->pages);
    }

    public function store(Request $request, Project $project)
    {
        abort_unless($project->user_id === auth()->id(), 403);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string',
            'html' => 'nullable|string',
            'css' => 'nullable|string',
            'components' => 'nullable|array',
            'styles' => 'nullable|array',
            'meta' => 'nullable|array',
        ]);

        $page = $project->pages()->create($validated);
        return response()->json($page, 201);
    }

    public function show(Project $project, Page $page)
    {
        abort_unless($project->user_id === auth()->id(), 403);
        abort_unless($page->project_id === $project->id, 404);

        return response()->json($page);
    }

    public function update(Request $request, Project $project, Page $page)
    {
        abort_unless($project->user_id === auth()->id(), 403);
        abort_unless($page->project_id === $project->id, 404);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'slug' => 'sometimes|required|string',
            'html' => 'nullable|string',
            'css' => 'nullable|string',
            'components' => 'nullable|array',
            'styles' => 'nullable|array',
            'meta' => 'nullable|array',
            'is_published' => 'sometimes|required|boolean',
        ]);

        $page->update($validated);

        if ($page->is_published && !empty($project->settings['deploy_webhook'])) {
            $webhook = $project->settings['deploy_webhook'];
            if ($this->isSafeUrl($webhook)) {
                try {
                    \Illuminate\Support\Facades\Http::timeout(5)->post($webhook, [
                        'event' => 'page.published',
                        'project_id' => $project->id,
                        'project_name' => $project->name,
                        'page_id' => $page->id,
                        'page_title' => $page->title,
                        'page_slug' => $page->slug,
                        'published_url' => url('/api/projects/' . $project->id . '/pages/' . $page->id),
                        'timestamp' => now()->toIso8601String(),
                    ]);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::warning('Deployment webhook failed: ' . $e->getMessage());
                }
            } else {
                \Illuminate\Support\Facades\Log::warning('Deployment webhook blocked (SSRF prevention): ' . $webhook);
            }
        }

        return response()->json($page);
    }

    public function destroy(Project $project, Page $page)
    {
        abort_unless($project->user_id === auth()->id(), 403);
        abort_unless($page->project_id === $project->id, 404);

        $page->delete();
        return response()->json(null, 204);
    }

    private function isSafeUrl(string $url): bool
    {
        $parsed = parse_url($url);
        if (!$parsed || !isset($parsed['scheme']) || !isset($parsed['host'])) {
            return false;
        }

        $scheme = strtolower($parsed['scheme']);
        if ($scheme !== 'http' && $scheme !== 'https') {
            return false;
        }

        $host = $parsed['host'];
        $ips = [];

        if (filter_var($host, FILTER_VALIDATE_IP)) {
            $ips[] = $host;
        } else {
            $records = @dns_get_record($host, DNS_A | DNS_AAAA);
            if ($records) {
                foreach ($records as $record) {
                    if (isset($record['ip'])) {
                        $ips[] = $record['ip'];
                    }
                    if (isset($record['ipv6'])) {
                        $ips[] = $record['ipv6'];
                    }
                }
            }
        }

        if (empty($ips)) {
            return false;
        }

        foreach ($ips as $ip) {
            if ($this->isPrivateIp($ip)) {
                return false;
            }
        }

        return true;
    }

    private function isPrivateIp(string $ip): bool
    {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            if ($ip === '::1') {
                return true;
            }
            $bin = inet_pton($ip);
            if ($bin === false) {
                return true;
            }
            $firstByte = ord($bin[0]);
            if (($firstByte & 0xfe) === 0xfc) {
                return true;
            }
            if ($firstByte === 0xfe && (ord($bin[1]) & 0xc0) === 0x80) {
                return true;
            }
            return false;
        }

        $long = ip2long($ip);
        if ($long === false) {
            return true;
        }

        $privateRanges = [
            ['127.0.0.0', '127.255.255.255'],
            ['10.0.0.0', '10.255.255.255'],
            ['172.16.0.0', '172.31.255.255'],
            ['192.168.0.0', '192.168.255.255'],
            ['169.254.0.0', '169.254.255.255'],
        ];

        foreach ($privateRanges as $range) {
            $start = ip2long($range[0]);
            $end = ip2long($range[1]);
            if ($long >= $start && $long <= $end) {
                return true;
            }
        }

        return false;
    }
}
