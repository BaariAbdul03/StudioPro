<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use League\Flysystem\FilesystemException;
use Throwable;

class AssetController extends Controller
{
    private const IMAGE_TYPES = [
        IMAGETYPE_JPEG => ['image/jpeg', 'jpg'],
        IMAGETYPE_PNG => ['image/png', 'png'],
        IMAGETYPE_WEBP => ['image/webp', 'webp'],
    ];

    public function index(Project $project)
    {
        abort_unless($project->user_id === auth()->id(), 403);

        return response()->json($project->assets()->latest()->get());
    }

    public function store(Request $request, Project $project)
    {
        abort_unless($project->user_id === auth()->id(), 403);
        $request->validate(['file' => ['required', 'file', 'max:10240']]);

        $file = $request->file('file');
        $image = @getimagesize($file->getPathname());
        $type = self::IMAGE_TYPES[$image[2] ?? 0] ?? null;
        $mime = (new \finfo(FILEINFO_MIME_TYPE))->file($file->getPathname());
        if (! $type || $mime !== $type[0] || $image[0] < 1 || $image[1] < 1 || max($image[0], $image[1]) > 4096) {
            throw ValidationException::withMessages([
                'file' => 'Upload a valid JPEG, PNG or WebP image up to 4096 pixels per side.',
            ]);
        }

        [$mime, $extension] = $type;
        $key = 'projects/'.$project->id.'/'.Str::uuid().'.'.$extension;
        $disk = Storage::disk('media');
        $stream = fopen($file->getPathname(), 'rb');
        abort_if($stream === false, 503);

        try {
            abort_unless($disk->put($key, $stream, ['ContentType' => $mime]), 503);
        } catch (FilesystemException $error) {
            report($error);
            abort(503);
        } finally {
            if (is_resource($stream)) {
                fclose($stream);
            }
        }

        try {
            $asset = $project->assets()->create([
                'filename' => Str::limit($file->getClientOriginalName(), 255, ''),
                'path' => $key,
                'mime_type' => $mime,
                'size' => $file->getSize(),
                'dimensions' => ['width' => $image[0], 'height' => $image[1]],
            ]);
        } catch (Throwable $error) {
            rescue(fn () => $disk->delete($key));
            throw $error;
        }

        return response()->json($asset, 201);
    }

    public function file(Project $project, Asset $asset)
    {
        abort_unless($project->user_id === auth()->id(), 403);
        abort_unless($asset->project_id === $project->id, 404);

        $disk = $this->diskFor($asset);
        try {
            abort_unless($disk->exists($asset->path), 404);
            if (config('filesystems.disks.media.driver') === 'neon') {
                $url = $disk->temporaryUrl($asset->path, now()->addMinutes(5));

                return redirect()->away($url, 302, ['Cache-Control' => 'private, no-store']);
            }

            // Keep fixtures and local development functional without a Neon account.
            abort_unless(in_array($asset->mime_type, array_column(self::IMAGE_TYPES, 0), true), 404);

            return $disk->response($asset->path, null, [
                'Content-Type' => $asset->mime_type,
                'Cache-Control' => 'private, no-store',
                'X-Content-Type-Options' => 'nosniff',
            ]);
        } catch (FilesystemException $error) {
            report($error);
            abort(503);
        }
    }

    public function destroy(Project $project, Asset $asset)
    {
        abort_unless($project->user_id === auth()->id(), 403);
        abort_unless($asset->project_id === $project->id, 404);

        try {
            abort_unless($this->diskFor($asset)->delete($asset->path), 503);
        } catch (FilesystemException $error) {
            report($error);
            abort(503);
        }
        $asset->delete();

        return response()->json(['success' => true]);
    }

    private function diskFor(Asset $asset)
    {
        if (str_starts_with($asset->path, 'projects/'.$asset->project_id.'/')) {
            return Storage::disk('media');
        }

        abort_unless(
            app()->environment('local', 'testing') && str_starts_with($asset->path, 'uploads/'),
            404
        );

        return Storage::disk('public');
    }
}
