<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use App\Models\Asset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AssetApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_project_assets(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $project = Project::create([
            'user_id' => $user->id,
            'name' => 'Test Project',
            'slug' => 'test-project',
        ]);

        Asset::create([
            'project_id' => $project->id,
            'filename' => 'image1.png',
            'path' => 'uploads/image1.png',
            'mime_type' => 'image/png',
            'size' => 1024,
        ]);

        $response = $this->getJson("/api/projects/{$project->id}/assets");

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment(['filename' => 'image1.png']);
    }

    public function test_can_upload_asset(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $this->actingAs($user);
        $project = Project::create([
            'user_id' => $user->id,
            'name' => 'Test Project',
            'slug' => 'test-project',
        ]);

        $file = UploadedFile::fake()->create('photo.jpg', 100, 'image/jpeg');

        $response = $this->postJson("/api/projects/{$project->id}/assets", [
            'file' => $file,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'filename', 'url', 'dimensions']);

        $asset = Asset::first();
        Storage::disk('public')->assertExists($asset->path);
    }

    public function test_can_delete_asset(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $this->actingAs($user);
        $project = Project::create([
            'user_id' => $user->id,
            'name' => 'Test Project',
            'slug' => 'test-project',
        ]);

        $path = Storage::disk('public')->putFile('uploads', UploadedFile::fake()->create('avatar.png', 50, 'image/png'));
        $asset = Asset::create([
            'project_id' => $project->id,
            'filename' => 'avatar.png',
            'path' => $path,
            'mime_type' => 'image/png',
            'size' => 512,
        ]);

        $response = $this->deleteJson("/api/projects/{$project->id}/assets/{$asset->id}");

        $response->assertOk()
            ->assertJson(['success' => true]);

        $this->assertDatabaseMissing('assets', ['id' => $asset->id]);
        Storage::disk('public')->assertMissing($path);
    }
}
