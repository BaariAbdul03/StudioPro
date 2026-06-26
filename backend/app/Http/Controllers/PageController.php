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
        return response()->json($project->pages);
    }

    public function store(Request $request, Project $project)
    {
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
        abort_unless($page->project_id === $project->id, 404);

        return response()->json($page);
    }

    public function update(Request $request, Project $project, Page $page)
    {
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
        return response()->json($page);
    }

    public function destroy(Project $project, Page $page)
    {
        abort_unless($page->project_id === $project->id, 404);

        $page->delete();
        return response()->json(null, 204);
    }
}
