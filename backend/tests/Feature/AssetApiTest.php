<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use App\Models\Asset;
use GuzzleHttp\Promise\Create;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Psr\Http\Message\RequestInterface;
use Tests\TestCase;

class AssetApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_asset_request_without_accept_header_returns_401(): void
    {
        $project = User::factory()->create()->projects()->create(['name' => 'Demo', 'slug' => 'demo']);

        $this->get("/api/projects/{$project->id}/assets/1/file")->assertUnauthorized();
    }

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
        Storage::fake('media');

        $user = User::factory()->create();
        $this->actingAs($user);
        $project = Project::create([
            'user_id' => $user->id,
            'name' => 'Test Project',
            'slug' => 'test-project',
        ]);

        $file = UploadedFile::fake()->createWithContent('incorrect.jpg', base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/l1EAAAAASUVORK5CYII='
        ));

        $response = $this->postJson("/api/projects/{$project->id}/assets", [
            'file' => $file,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'filename', 'url', 'dimensions']);

        $asset = Asset::first();
        $this->assertMatchesRegularExpression("~^projects/{$project->id}/[a-f0-9-]{36}\\.png$~", $asset->path);
        $this->assertSame('image/png', $asset->mime_type);
        Storage::disk('media')->assertExists($asset->path);

        $url = $response->json('url');
        $this->get($url)->assertOk()->assertHeader('Content-Type', 'image/png');
        $this->actingAs(User::factory()->create());
        $this->getJson($url)->assertForbidden();
    }

    public function test_upload_rejects_unrecognized_bytes_even_with_an_image_filename(): void
    {
        Storage::fake('media');
        $user = User::factory()->create();
        $this->actingAs($user);
        $project = $user->projects()->create(['name' => 'Test', 'slug' => 'test']);
        $file = UploadedFile::fake()->createWithContent('photo.png', 'plain text');

        $this->postJson("/api/projects/{$project->id}/assets", ['file' => $file])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('file');
        $this->assertDatabaseCount('assets', 0);
    }

    public function test_can_delete_asset(): void
    {
        Storage::fake('media');

        $user = User::factory()->create();
        $this->actingAs($user);
        $project = Project::create([
            'user_id' => $user->id,
            'name' => 'Test Project',
            'slug' => 'test-project',
        ]);

        $path = 'projects/'.$project->id.'/example.png';
        Storage::disk('media')->put($path, 'test contents');
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
        Storage::disk('media')->assertMissing($path);
    }

    public function test_neon_upload_uses_bucket_visibility_with_path_style_s3(): void
    {
        $request = null;
        $disk = Storage::build([
            'driver' => 'neon',
            'endpoint' => 'https://storage.example.test',
            'bucket' => 'studio-media',
            'region' => 'eu-central-1',
            'key' => Str::random(20),
            'secret' => Str::random(40),
            'use_path_style_endpoint' => true,
            'throw' => true,
            'http_handler' => function (RequestInterface $outgoing) use (&$request) {
                $request = $outgoing;

                return Create::promiseFor(new Response(200));
            },
        ]);

        $this->assertTrue($disk->put('projects/1/photo.png', 'image bytes', ['ContentType' => 'image/png']));
        $this->assertSame('/studio-media/projects/1/photo.png', $request->getUri()->getPath());
        $this->assertSame('image/png', $request->getHeaderLine('Content-Type'));
        $this->assertFalse($request->hasHeader('x-amz-acl'));
    }
}
