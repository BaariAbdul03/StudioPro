<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AiControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_ai_page_generation_returns_builder_payload(): void
    {
        config(['services.gemini.key' => null]);

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
        config(['services.gemini.key' => null]);

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
}
