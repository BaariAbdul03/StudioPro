<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\CmsCollection;
use Illuminate\Http\Request;

class CmsCollectionController extends Controller
{
    public function index(Project $project)
    {
        return response()->json($project->collections()->withCount('items')->get());
    }

    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:cms_collections,slug,NULL,id,project_id,'.$project->id,
            'fields' => 'required|array',
            'fields.*.name' => 'required|string|max:255',
            'fields.*.type' => 'required|string|in:plain-text,rich-text,asset,number,date',
            'fields.*.key' => 'required|string|max:255',
        ]);

        $collection = $project->collections()->create($validated);
        $collection->loadCount('items');
        return response()->json($collection, 201);
    }

    public function update(Request $request, Project $project, CmsCollection $collection)
    {
        abort_unless($collection->project_id === $project->id, 404);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'slug' => 'sometimes|required|string|max:255|unique:cms_collections,slug,'.$collection->id.',id,project_id,'.$project->id,
            'fields' => 'sometimes|required|array',
            'fields.*.name' => 'required|string|max:255',
            'fields.*.type' => 'required|string|in:plain-text,rich-text,asset,number,date',
            'fields.*.key' => 'required|string|max:255',
        ]);

        $collection->update($validated);
        $collection->loadCount('items');
        return response()->json($collection);
    }

    public function destroy(Project $project, CmsCollection $collection)
    {
        abort_unless($collection->project_id === $project->id, 404);

        $collection->delete();
        return response()->json(['success' => true]);
    }
}
