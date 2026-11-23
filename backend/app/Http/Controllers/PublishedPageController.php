<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use League\Flysystem\UnableToReadFile;

class PublishedPageController extends Controller
{
    private const IMAGE_TYPES = [
        'jpg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'webp' => 'image/webp',
    ];

    public function show(string $publicId)
    {
        abort_unless(config('filesystems.disks.published.driver') === 'neon'
            || app()->environment('local', 'testing'), 404);
        $disk = Storage::disk('published');
        $path = 'pages/'.$publicId.'.html';
        try {
            $html = $disk->get($path);
        } catch (UnableToReadFile $error) {
            abort_unless($disk->exists($path), 404);
            throw $error;
        }
        abort_if($html === null, 404);
        $imageHost = parse_url((string) config('filesystems.disks.published.url'), PHP_URL_HOST);
        $imageSource = $imageHost && preg_match('/\A[a-z0-9.-]+\z/i', $imageHost) ? " https://{$imageHost}" : '';

        return response($html, 200, [
            'Content-Type' => 'text/html; charset=utf-8',
            'Content-Security-Policy' => "default-src 'none'; script-src 'none'; img-src 'self'{$imageSource}; style-src 'unsafe-inline'; base-uri 'none'; form-action 'none'; frame-ancestors 'none'",
            'Cache-Control' => 'no-store',
        ]);
    }

    public function image(string $hash, string $extension)
    {
        $disk = $this->localPublishedDisk();
        $path = 'images/'.$hash.'.'.$extension;
        abort_unless($disk->exists($path), 404);

        return $disk->response($path, null, [
            'Content-Type' => self::IMAGE_TYPES[$extension],
            'Cache-Control' => 'no-store',
        ]);
    }

    public function bundledImage(string $file)
    {
        abort_unless(app()->environment('local', 'testing'), 404);
        $path = base_path('../frontend/public/images/'.$file);
        abort_unless(is_file($path), 404);

        return response()->file($path);
    }

    private function localPublishedDisk()
    {
        abort_unless(app()->environment('local', 'testing') && config('filesystems.disks.published.driver') === 'local', 404);

        return Storage::disk('published');
    }
}
