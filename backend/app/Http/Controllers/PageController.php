<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Project;
use App\Services\PageRelease;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PageController extends Controller
{
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
            'slug' => ['required', 'string', 'max:100', 'alpha_dash:ascii', $this->uniqueSlug($project)],
        ] + Page::draftRules());

        return response()->json($project->pages()->create($this->withLegacyFallback($validated)), 201);
    }

    public function show(Project $project, Page $page)
    {
        $this->authorizePage($project, $page);

        return response()->json($page);
    }

    public function update(Request $request, Project $project, Page $page)
    {
        $this->authorizePage($project, $page);
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'slug' => ['sometimes', 'required', 'string', 'max:100', 'alpha_dash:ascii', $this->uniqueSlug($project)->ignore($page)],
        ] + Page::draftRules());

        $page->update($this->withLegacyFallback($validated));

        return response()->json($page->fresh());
    }

    public function publish(Project $project, Page $page, PageRelease $releases)
    {
        $this->authorizePage($project, $page);

        return response()->json($releases->publish($page));
    }

    public function unpublish(Project $project, Page $page, PageRelease $releases)
    {
        $this->authorizePage($project, $page);

        return response()->json($releases->remove($page));
    }

    public function destroy(Project $project, Page $page, PageRelease $releases)
    {
        $this->authorizePage($project, $page);

        DB::transaction(function () use ($page, $releases) {
            $releases->remove($page)->delete();
        });

        return response()->noContent();
    }

    private function authorizePage(Project $project, Page $page): void
    {
        abort_unless($project->user_id === auth()->id(), 403);
        abort_unless($page->project_id === $project->id, 404);
    }

    private function uniqueSlug(Project $project)
    {
        return Rule::unique('pages')->where('project_id', $project->id);
    }

    private function withLegacyFallback(array $draft): array
    {
        // A legacy template or caller can replace the rendered draft without sending project JSON.
        if (! array_key_exists('project_data', $draft) && array_key_exists('html', $draft)) {
            $draft['project_data'] = null;
        }

        return $draft;
    }
}
