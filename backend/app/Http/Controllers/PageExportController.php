<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Project;
use App\Services\PageSnapshot;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;
use ZipArchive;

class PageExportController extends Controller
{
    public function download(Project $project, Page $page, PageSnapshot $renderer)
    {
        abort_unless($project->user_id === auth()->id(), 403);
        abort_unless($page->project_id === $project->id, 404);

        $snapshot = $renderer->build($project, $page);
        $path = tempnam(sys_get_temp_dir(), 'studio-export-');
        $zip = new ZipArchive;

        try {
            if ($path === false || $zip->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                throw new RuntimeException('The export archive could not be opened.');
            }

            $opened = true;
            $this->addEntry($zip, 'index.html', $renderer->document($page, $snapshot));
            $this->addEntry($zip, 'style.css', $snapshot['css']);

            foreach ($snapshot['assets'] as $key => $asset) {
                $this->addEntry($zip, $key, Storage::disk('media')->get($asset->path));
            }

            foreach ($snapshot['builtIns'] as $key => $file) {
                $this->addEntry($zip, $key, file_get_contents($file));
            }

            if (! $zip->close()) {
                throw new RuntimeException('The export archive could not be written.');
            }
            $opened = false;
        } catch (Throwable $exception) {
            if ($opened ?? false) {
                $zip->unchangeAll();
                $zip->close();
            }
            if (is_string($path) && is_file($path)) {
                unlink($path);
            }

            throw $exception;
        }

        $filename = (Str::slug($project->slug.'-'.$page->slug) ?: 'studio-page').'.zip';

        return response()->download($path, $filename, [
            'Content-Type' => 'application/zip',
            'Cache-Control' => 'private, no-store',
        ])->deleteFileAfterSend();
    }

    private function addEntry(ZipArchive $zip, string $name, string|false|null $contents): void
    {
        if (! is_string($contents) || ! $zip->addFromString($name, $contents)) {
            throw new RuntimeException('An export file could not be added to the archive.');
        }
    }
}
