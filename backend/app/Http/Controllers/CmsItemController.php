<?php

namespace App\Http\Controllers;

use App\Models\CmsCollection;
use App\Models\CmsItem;
use Illuminate\Http\Request;

class CmsItemController extends Controller
{
    public function index(CmsCollection $collection)
    {
        abort_unless($collection->project->user_id === auth()->id(), 403);
        return response()->json($collection->items()->orderBy('created_at', 'desc')->get());
    }

    public function store(Request $request, CmsCollection $collection)
    {
        abort_unless($collection->project->user_id === auth()->id(), 403);
        $validated = $request->validate([
            'slug' => 'required|string|max:255|unique:cms_items,slug,NULL,id,collection_id,'.$collection->id,
            'data' => 'required|array',
            'status' => 'sometimes|string|max:50',
        ]);

        $item = $collection->items()->create($validated);
        return response()->json($item, 201);
    }

    public function update(Request $request, CmsCollection $collection, CmsItem $item)
    {
        abort_unless($collection->project->user_id === auth()->id(), 403);
        abort_unless($item->collection_id === $collection->id, 404);

        $validated = $request->validate([
            'slug' => 'sometimes|required|string|max:255|unique:cms_items,slug,'.$item->id.',id,collection_id,'.$collection->id,
            'data' => 'sometimes|required|array',
            'status' => 'sometimes|required|string|max:50',
        ]);

        $item->update($validated);
        return response()->json($item);
    }

    public function destroy(CmsCollection $collection, CmsItem $item)
    {
        abort_unless($collection->project->user_id === auth()->id(), 403);
        abort_unless($item->collection_id === $collection->id, 404);

        $item->delete();
        return response()->json(['success' => true]);
    }
}
