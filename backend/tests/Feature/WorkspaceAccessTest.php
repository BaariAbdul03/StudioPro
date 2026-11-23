<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class WorkspaceAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_and_profile_updates_keep_workspace_data_private(): void
    {
        $owner = User::factory()->create();
        $project = $owner->projects()->create(['name' => 'Private project', 'slug' => 'private-project']);
        $input = [
            'name' => 'New editor',
            'email' => 'New.Editor@example.test',
            'password' => 'first-passphrase',
            'password_confirmation' => 'first-passphrase',
        ];

        $this->putJson('/api/user/profile', $input)->assertUnauthorized();
        $this->postJson('/api/register', $input)
            ->assertCreated()
            ->assertJsonPath('user.email', 'new.editor@example.test');
        $editor = User::where('email', 'new.editor@example.test')->firstOrFail();
        $this->assertTrue(Hash::check($input['password'], $editor->password));
        $this->getJson('/api/projects')->assertOk()->assertExactJson([]);
        $this->getJson('/api/projects/'.$project->id)->assertForbidden();

        $profile = ['name' => 'Updated editor', 'email' => 'updated@example.test'];
        $this->putJson('/api/user/profile', $profile)->assertUnprocessable();
        $this->putJson('/api/user/profile', $profile + ['current_password' => $input['password']])
            ->assertOk()->assertJsonPath('email', 'updated@example.test');
        $this->putJson('/api/user/password', [
            'current_password' => $input['password'],
            'password' => 'second-passphrase',
            'password_confirmation' => 'second-passphrase',
        ])->assertOk();
        $this->assertTrue(Hash::check('second-passphrase', $editor->refresh()->password));
        $this->getJson('/api/user')->assertOk()->assertJsonPath('name', 'Updated editor');
    }

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
