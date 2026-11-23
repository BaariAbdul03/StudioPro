<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use ZipArchive;

class PageReleaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_draft_save_and_restore_cannot_change_the_published_snapshot(): void
    {
        [$page, $url] = $this->createDraft();
        $disk = Storage::fake('published');
        config(['studio.public_url' => 'https://showcase.example.test']);

        $this->putJson($url, [
            'html' => '<main><h1>First release</h1></main><script>/* omitted */</script>',
            'css' => 'h1{color:blue}',
            'project_data' => ['pages' => [['id' => 'first']]],
            'is_published' => true,
        ])->assertOk()->assertJsonPath('is_published', false);

        $version = $this->postJson($url.'/versions', ['version_label' => 'First draft'])->assertCreated()->json();
        $release = $this->postJson($url.'/publish')->assertOk()->assertJsonPath('is_published', true)->json();
        $key = 'pages/'.$release['public_id'].'.html';
        $html = $disk->get($key);

        $this->assertSame('https://showcase.example.test/p/'.$release['public_id'], $release['public_url']);
        $this->assertStringContainsString('<main><h1>First release</h1></main>', $html);
        $this->assertStringContainsString('h1{color:blue}', $html);
        $this->assertStringNotContainsString('<script', $html);
        $this->get('/p/'.$release['public_id'])->assertOk()->assertSee('First release');

        $this->putJson($url, ['html' => '<h1>Draft changes</h1>'])->assertOk()
            ->assertJsonPath('project_data', null);
        $this->assertSame($html, $disk->get($key));

        $this->postJson($url.'/versions/'.$version['id'].'/restore')->assertOk()
            ->assertJsonPath('project_data.pages.0.id', 'first');
        $this->assertSame($html, $disk->get($key));

        $this->deleteJson($url.'/publish')->assertOk()->assertJsonPath('public_url', null);
        $disk->assertMissing($key);
        $this->get('/p/'.$release['public_id'])->assertNotFound();
        $this->postJson($url.'/publish')->assertOk()->assertJsonPath('public_id', $release['public_id']);

        $this->deleteJson($url)->assertNoContent();
        $disk->assertMissing($key);
        $this->assertModelMissing($page);
    }

    public function test_publish_and_export_use_the_same_sanitized_content_and_owned_images(): void
    {
        [$page, $url] = $this->createDraft();
        $private = Storage::fake('media');
        $public = Storage::fake('published');
        $key = 'projects/'.$page->project_id.'/sample.png';
        $private->put($key, 'sample-image');
        $asset = $page->project->assets()->create([
            'path' => $key,
            'filename' => 'sample.png',
            'mime_type' => 'image/png',
            'size' => 12,
        ]);

        $this->putJson($url, [
            'html' => '<section class="feature"><h1 id="intro" style="padding:10px">Portfolio</h1><img alt="Sample" src="/api/projects/'.$page->project_id.'/assets/'.$asset->id.'/file"><img alt="Architecture" src="/images/arch_hero_01.png"><iframe title="Omitted"></iframe></section>',
            'css' => '@import "https://example.test/theme.css"; .feature{display:grid; background-image:url(https://example.test/background.png)} @media (max-width: 600px){.feature{display:block}}',
            'meta' => ['headCode' => '<script>/* omitted */</script>', 'seoTitle' => 'Title & description'],
        ])->assertOk();

        $release = $this->postJson($url.'/publish')->assertOk()->json();
        $html = $public->get('pages/'.$release['public_id'].'.html');
        $image = 'images/'.hash('sha256', $key).'.png';
        $this->assertSame('sample-image', $public->get($image));
        $this->assertStringContainsString('src="'.$public->url($image).'"', $html);
        $this->assertStringContainsString('src="/images/arch_hero_01.png"', $html);
        $this->assertStringContainsString('id="intro"', $html);
        $this->assertStringContainsString('style="padding:10px"', $html);
        $this->assertStringContainsString('Title &amp; description', $html);
        $this->assertStringContainsString('@media', $html);
        $this->assertStringNotContainsString('example.test', $html);
        $this->assertStringNotContainsString('<iframe', $html);
        $this->assertStringNotContainsString('<script', $html);

        $private->delete($key);
        $this->postJson($url.'/publish')->assertServerError();
        $this->assertSame($html, $public->get('pages/'.$release['public_id'].'.html'));
        $private->put($key, 'sample-image');

        $response = $this->get($url.'/export')->assertOk()->assertDownload('portfolio-home.zip');
        $archive = $response->baseResponse->getFile()->getPathname();
        $zip = new ZipArchive;

        try {
            $this->assertTrue($zip->open($archive));
            $this->assertStringContainsString('src="./'.$image.'"', $zip->getFromName('index.html'));
            $this->assertStringContainsString('src="./images/arch_hero_01.png"', $zip->getFromName('index.html'));
            $this->assertNotFalse($zip->getFromName('images/arch_hero_01.png'));
            $this->assertStringContainsString('display:grid', $zip->getFromName('style.css'));
            $this->assertSame('sample-image', $zip->getFromName($image));
        } finally {
            $zip->close();
            unlink($archive);
        }
    }

    public function test_a_different_user_cannot_publish_or_export_a_draft(): void
    {
        [, $url] = $this->createDraft();
        $this->actingAs(User::factory()->create());

        $this->postJson($url.'/publish')->assertForbidden();
        $this->deleteJson($url.'/publish')->assertForbidden();
        $this->getJson($url.'/export')->assertForbidden();
    }

    public function test_the_full_app_serves_published_pages_with_a_neon_image_policy(): void
    {
        $this->app->instance('env', 'production');
        config([
            'filesystems.disks.published.driver' => 'neon',
            'filesystems.disks.published.url' => 'https://object-storage.example.test/studio-published',
        ]);
        $disk = Storage::fake('published');
        $id = (string) str()->uuid();
        $disk->put('pages/'.$id.'.html', '<h1>Published studio</h1>');

        $response = $this->get('/p/'.$id)->assertOk()->assertSee('Published studio')
            ->assertCookieMissing(config('session.cookie'));
        $this->assertStringContainsString("img-src 'self' https://object-storage.example.test", $response->headers->get('Content-Security-Policy'));
        $this->assertStringContainsString("script-src 'none'", $response->headers->get('Content-Security-Policy'));

        $disk->delete('pages/'.$id.'.html');
        $this->get('/p/'.$id)->assertNotFound();
    }

    public function test_project_deletion_removes_published_pages(): void
    {
        [$page, $url] = $this->createDraft();
        $disk = Storage::fake('published');
        $release = $this->postJson($url.'/publish')->assertOk()->json();
        $key = 'pages/'.$release['public_id'].'.html';

        $disk->assertExists($key);
        $this->deleteJson('/api/projects/'.$page->project_id)->assertNoContent();
        $disk->assertMissing($key);
        $this->assertModelMissing($page);
    }

    private function createDraft(): array
    {
        $user = User::factory()->create();
        $project = Project::create(['user_id' => $user->id, 'name' => 'Portfolio', 'slug' => 'portfolio']);
        $page = $project->pages()->create(['title' => 'Home', 'slug' => 'home']);
        $this->actingAs($user);

        return [$page, '/api/projects/'.$project->id.'/pages/'.$page->id];
    }
}
