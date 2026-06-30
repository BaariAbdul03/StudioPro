<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Project $project)
    {
        abort_unless($project->user_id === auth()->id(), 403);
        return response()->json($project->products()->with('variants')->get());
    }

    public function store(Request $request, Project $project)
    {
        abort_unless($project->user_id === auth()->id(), 403);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,NULL,id,project_id,'.$project->id,
            'price' => 'required|numeric|min:0',
            'compare_at_price' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string|max:100',
            'inventory_quantity' => 'integer|min:0',
            'inventory_status' => 'nullable|in:in_stock,low_stock,out_of_stock',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'images' => 'nullable|array',
        ]);

        $product = $project->products()->create($validated);
        return response()->json($product, 201);
    }

    public function update(Request $request, Project $project, Product $product)
    {
        abort_unless($project->user_id === auth()->id(), 403);
        abort_unless($product->project_id === $project->id, 404);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'slug' => 'sometimes|required|string|max:255|unique:products,slug,'.$product->id.',id,project_id,'.$project->id,
            'price' => 'sometimes|required|numeric|min:0',
            'compare_at_price' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string|max:100',
            'inventory_quantity' => 'sometimes|integer|min:0',
            'inventory_status' => 'nullable|in:in_stock,low_stock,out_of_stock',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'images' => 'nullable|array',
        ]);

        $product->update($validated);
        return response()->json($product);
    }

    public function destroy(Project $project, Product $product)
    {
        abort_unless($project->user_id === auth()->id(), 403);
        abort_unless($product->project_id === $project->id, 404);

        $product->delete();
        return response()->json(['success' => true]);
    }
}
