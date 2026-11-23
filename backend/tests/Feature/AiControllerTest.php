<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class AiControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_ai_page_generation_returns_builder_payload(): void
    {
        config([
            'services.gemini.key' => null,
        ]);

        $user = User::factory()->create();
        $this->actingAs($user);
        $project = Project::create([
            'user_id' => $user->id,
            'name' => 'Phase 7',
            'slug' => 'phase-7',
            'status' => 'active',
        ]);

        $response = $this->postJson("/api/projects/{$project->id}/ai/generate-page", [
            'prompt' => 'Create a modern landing page for an organic matcha powder shop with a hero, feature grid, and pricing table.',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('provider', 'local-fallback')
            ->assertJsonStructure(['html', 'css', 'meta' => ['title', 'description'], 'steps']);
    }

    public function test_ai_copy_and_seo_work_without_gemini_key(): void
    {
        config([
            'services.gemini.key' => null,
        ]);

        $user = User::factory()->create();
        $this->actingAs($user);
        $project = Project::create([
            'user_id' => $user->id,
            'name' => 'Phase 7',
            'slug' => 'phase-7',
            'status' => 'active',
        ]);

        $this->postJson("/api/projects/{$project->id}/ai/copy", [
            'text' => 'Build powerful pages quickly with connected CMS and commerce blocks.',
            'mode' => 'shorten',
        ])->assertOk()->assertJsonPath('provider', 'local-fallback')->assertJsonStructure(['text']);

        $this->postJson("/api/projects/{$project->id}/ai/seo", [
            'html' => '<main><h1>AI visual page builder for fast ecommerce campaigns</h1></main>',
        ])->assertOk()->assertJsonStructure(['title', 'description', 'keywords', 'alt_texts']);
    }

    public function test_gemini_payload_is_requested_with_a_header_and_normalized(): void
    {
        $credential = str()->random(32);
        config(['services.gemini.key' => $credential]);
        $owner = User::factory()->create();
        $project = $owner->projects()->create(['name' => 'Demo', 'slug' => 'demo']);
        Http::preventStrayRequests();
        Http::fake([
            'https://generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [['content' => ['parts' => [['text' => json_encode([
                    'html' => '<h1>Field notes</h1>',
                    'css' => 'h1 { color: green }',
                ])]]]]],
            ]),
        ]);

        $this->actingAs($owner)->postJson("/api/projects/{$project->id}/ai/generate-page", [
            'prompt' => 'Build a page for a field journal.',
        ])->assertOk()->assertJsonPath('provider', 'gemini')
            ->assertJsonPath('html', '<main><h1>Field notes</h1></main>')
            ->assertJsonMissingPath('diagnostics');

        Http::assertSent(fn (Request $request) => $request->hasHeader('x-goog-api-key', $credential)
            && ! str_contains($request->url(), '?')
            && $request['generationConfig']['responseMimeType'] === 'application/json');
    }

    public function test_stitch_generation_is_queued_and_only_the_project_owner_can_read_it(): void
    {
        $credential = str()->random(32);
        config([
            'studio.stitch_enabled' => true,
            'studio.stitch_queue' => 'database',
            'services.stitch.key' => $credential,
            'services.stitch.project_id' => '12345',
        ]);
        $owner = User::factory()->create();
        $project = $owner->projects()->create(['name' => 'Demo', 'slug' => 'demo']);
        $url = "/api/projects/{$project->id}/ai/stitch";
        $prompt = ['prompt' => 'Design a portfolio for an architect.'];
        Process::fake(fn () => Process::result(json_encode([
            'screenId' => 'test-screen',
            'downloadUrl' => 'http://127.0.0.1/private',
        ])));

        $this->postJson($url, $prompt)->assertUnauthorized();
        $id = $this->actingAs($owner)->postJson($url, $prompt)->assertAccepted()->json('id');
        $this->getJson($url.'/'.$id)->assertOk()->assertJsonPath('status', 'pending');
        $job = Queue::connection('database')->pop('stitch');
        $this->assertNotNull($job);
        $this->assertStringNotContainsString($prompt['prompt'], $job->getRawBody());
        $job->fire();
        $this->getJson($url.'/'.$id)->assertOk()
            ->assertJsonPath('status', 'ready')
            ->assertJsonPath('stitch_url', 'https://stitch.withgoogle.com/projects/12345')
            ->assertJsonMissingPath('downloadUrl')
            ->assertJsonMissingPath('html');

        $this->actingAs(User::factory()->create())->getJson($url.'/'.$id)->assertForbidden();
    }
}
