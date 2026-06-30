<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\PageVersion;
use App\Models\Project;
use Illuminate\Http\Request;

class PageVersionController extends Controller
{
    public function index(Project $project, Page $page)
    {
        abort_unless($project->user_id === auth()->id(), 403);
        abort_unless($page->project_id === $project->id, 404);

        return response()->json(
            $page->versions()
                ->latest()
                ->get()
        );
    }

    public function store(Request $request, Project $project, Page $page)
    {
        abort_unless($project->user_id === auth()->id(), 403);
        abort_unless($page->project_id === $project->id, 404);

        $validated = $request->validate([
            'version_label' => 'nullable|string|max:100',
            'components' => 'nullable|array',
            'styles' => 'nullable|array',
            'html' => 'nullable|string',
            'css' => 'nullable|string',
            'meta' => 'nullable|array',
        ]);

        $version = $page->versions()->create([
            'version_label' => $validated['version_label'] ?? 'Manual checkpoint',
            'components' => $validated['components'] ?? $page->components ?? [],
            'styles' => $validated['styles'] ?? $page->styles ?? [],
            'html' => $validated['html'] ?? $page->html,
            'css' => $validated['css'] ?? $page->css,
            'meta' => $validated['meta'] ?? $page->meta ?? [],
        ]);

        return response()->json($version, 201);
    }

    public function restore(Project $project, Page $page, PageVersion $version)
    {
        abort_unless($project->user_id === auth()->id(), 403);
        abort_unless($page->project_id === $project->id, 404);
        abort_unless($version->page_id === $page->id, 404);

        $page->update([
            'components' => $version->components,
            'styles' => $version->styles,
            'html' => $version->html,
            'css' => $version->css,
            'meta' => $version->meta ?? [],
        ]);

        return response()->json($page->fresh());
    }
}
