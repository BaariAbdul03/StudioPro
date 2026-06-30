<?php

namespace App\Http\Controllers;

use App\Models\FormSubmission;
use App\Models\Page;
use App\Models\Project;
use Illuminate\Http\Request;

class FormSubmissionController extends Controller
{
    public function index(Project $project)
    {
        abort_unless($project->user_id === auth()->id(), 403);

        return response()->json(
            $project->formSubmissions()
                ->latest()
                ->limit(100)
                ->get()
        );
    }

    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'page_id' => 'nullable|integer|exists:pages,id',
            'form_name' => 'nullable|string|max:255',
            'payload' => 'required|array',
            'source_url' => 'nullable|string|max:500',
        ]);

        if (! empty($validated['page_id'])) {
            $page = Page::findOrFail($validated['page_id']);
            abort_unless($page->project_id === $project->id, 404);
        }

        $submission = $project->formSubmissions()->create([
            'page_id' => $validated['page_id'] ?? null,
            'form_name' => $validated['form_name'] ?? 'Contact Form',
            'payload' => $validated['payload'],
            'source_url' => $validated['source_url'] ?? null,
        ]);

        return response()->json($submission, 201);
    }
}
