<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkspaceAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_form_submission_requires_authentication_and_project_ownership(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $project = $owner->projects()->create(['name' => 'Demo', 'slug' => 'demo']);
        $uri = "/api/projects/{$project->id}/form-submissions";
        $input = ['form_name' => 'Contact', 'payload' => ['name' => 'Visitor']];

        $this->postJson($uri, $input)->assertUnauthorized();
        $this->actingAs($other)->postJson($uri, $input)->assertForbidden();
        $this->assertDatabaseCount('form_submissions', 0);

        $this->actingAs($owner)->postJson($uri, $input)->assertCreated();
        $this->assertDatabaseCount('form_submissions', 1);
    }
}
