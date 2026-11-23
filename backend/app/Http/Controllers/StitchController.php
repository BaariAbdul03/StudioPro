<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateStitchDraft;
use App\Models\Project;
use App\Services\StitchDraft;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class StitchController extends Controller
{
    public function store(Request $request, Project $project)
    {
        abort_unless($project->user_id === $request->user()->id, 403);
        abort_unless(StitchDraft::configured(), 404);
        $validated = $request->validate([
            'prompt' => ['required', 'string', 'min:8', 'max:500'],
            'style' => ['nullable', 'string', 'max:80'],
        ]);

        $id = (string) Str::uuid();
        $key = $this->key($project, $id);
        $style = $validated['style'] ?? 'Minimal';
        $prompt = "Design a responsive web page. Visual style: {$style}. Include the screen markup. ".$validated['prompt'];
        Cache::put($key, ['status' => 'pending'], now()->addMinutes(5));
        GenerateStitchDraft::dispatch($key, $prompt)->onConnection(config('studio.stitch_queue'))->onQueue('stitch');

        return response()->json(['id' => $id], 202);
    }

    public function show(Request $request, Project $project, string $draft)
    {
        abort_unless($project->user_id === $request->user()->id, 403);
        abort_unless(StitchDraft::configured(), 404);
        $result = Cache::get($this->key($project, $draft));
        abort_if($result === null, 404);

        return response()->json($result)->header('Cache-Control', 'private, no-store');
    }

    private function key(Project $project, string $id): string
    {
        return "stitch-draft:{$project->id}:{$id}";
    }
}
