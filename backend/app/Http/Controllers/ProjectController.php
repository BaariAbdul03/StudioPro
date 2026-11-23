<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Services\PageRelease;
use App\Services\StitchDraft;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return response()->json(
            $request->user()->projects()->with(['pages' => function($q) { 
                $q->latest('updated_at')->orderBy('id')->limit(1);
            }])
            ->withCount(['pages', 'products', 'collections'])
            ->orderBy('updated_at', 'desc')
            ->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:projects,slug',
            'description' => 'nullable|string',
            'page' => 'sometimes|array:title,html,css',
            'page.title' => 'required_with:page|string|max:255',
            'page.html' => 'required_with:page|string|max:262144',
            'page.css' => 'nullable|string|max:131072',
        ]);

        $starter = $validated['page'] ?? [];
        unset($validated['page']);
        $validated['user_id'] = $request->user()->id;

        $project = DB::transaction(function () use ($validated, $starter) {
            $project = Project::create($validated);
            $project->pages()->create($starter + [
                'title' => 'Home Page',
                'slug' => 'home',
                'html' => '<main class="max-w-3xl mx-auto p-12"><h1>Welcome to '.e($project->name).'</h1><p>Drag a block onto the canvas to start building.</p></main>',
                'css' => '',
                'components' => [],
                'styles' => [],
            ]);

            return $project->load('pages');
        });

        return response()->json($project, 201);
    }

    public function show(Project $project)
    {
        abort_unless($project->user_id === auth()->id(), 403);
        return response()->json([
            ...$project->load('pages')->toArray(),
            'ai_enabled' => (bool) config('studio.ai_enabled'),
            'stitch_enabled' => StitchDraft::configured(),
        ]);
    }

    public function update(Request $request, Project $project)
    {
        abort_unless($project->user_id === auth()->id(), 403);
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'slug' => 'sometimes|required|string|unique:projects,slug,' . $project->id,
            'description' => 'nullable|string',
            'settings' => 'nullable|array',
            'status' => 'sometimes|required|string|max:50',
        ]);

        $project->update($validated);
        return response()->json($project);
    }

    public function destroy(Project $project, PageRelease $releases)
    {
        abort_unless($project->user_id === auth()->id(), 403);

        // Commit each successful unpublish so a storage failure leaves accurate link state for a retry.
        $project->pages()->where('is_published', true)->eachById(fn ($page) => $releases->remove($page));

        DB::transaction(function () use ($project) {
            $project = Project::lockForUpdate()->findOrFail($project->id);
            $pages = $project->pages()->lockForUpdate()->get(['id', 'is_published']);
            abort_if($pages->contains('is_published', true), 409, 'A page was just published. Retry deleting the project.');
            $project->delete();
        });

        return response()->noContent();
    }
}
