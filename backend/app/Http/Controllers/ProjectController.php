<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return response()->json(
            $request->user()->projects()->with(['pages' => function($q) { 
                $q->orderBy('id', 'asc')->limit(1); 
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
        ]);

        $validated['user_id'] = $request->user()->id;

        $project = Project::create($validated);
        
        // Create default Home Page
        $project->pages()->create([
            'title' => 'Home Page',
            'slug' => 'home',
            'html' => '<div class="w-full h-full bg-[#0a0b10] text-white flex flex-col justify-center items-center p-12"><h1>Welcome to ' . $project->name . '</h1><p class="text-gray-400 mt-2">Start editing this page in the visual builder.</p></div>',
            'css' => '',
            'components' => [],
            'styles' => [],
        ]);

        return response()->json($project->load('pages'), 201);
    }

    public function show(Project $project)
    {
        abort_unless($project->user_id === auth()->id(), 403);
        return response()->json($project->load('pages'));
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

    public function destroy(Project $project)
    {
        abort_unless($project->user_id === auth()->id(), 403);
        $project->delete();
        return response()->json(null, 204);
    }
}
