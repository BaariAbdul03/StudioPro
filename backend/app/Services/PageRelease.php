<?php

namespace App\Services;

use App\Models\Page;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class PageRelease
{
    public function __construct(private PageSnapshot $renderer) {}

    public function publish(Page $page): Page
    {
        // Persist the stable link before touching the bucket so retries always address the same object.
        Page::whereKey($page->id)->whereNull('public_id')->update(['public_id' => (string) Str::uuid()]);

        return DB::transaction(function () use ($page) {
            $page = Page::lockForUpdate()->findOrFail($page->id);
            $snapshot = $this->renderer->build($page->project, $page);
            $published = Storage::disk('published');

            foreach ($snapshot['assets'] as $key => $asset) {
                $stream = Storage::disk('media')->readStream($asset->path);
                if (! is_resource($stream)) {
                    throw new RuntimeException('A draft image could not be read.');
                }

                try {
                    if (! $published->put($key, $stream, ['ContentType' => $asset->mime_type])) {
                        throw new RuntimeException('A release image could not be written.');
                    }
                } finally {
                    if (is_resource($stream)) {
                        fclose($stream);
                    }
                }
            }

            $page->forceFill(['is_published' => true])->save();
            $html = $this->renderer->document($page, $snapshot, $published->url(''));

            // A single PUT switches the public URL after every referenced image is available.
            if (! $published->put($this->key($page), $html, [
                'ContentType' => 'text/html; charset=utf-8',
                'CacheControl' => 'no-store',
            ])) {
                throw new RuntimeException('The page release could not be written.');
            }

            return $page;
        });
    }

    public function remove(Page $page): Page
    {
        return DB::transaction(function () use ($page) {
            $page = Page::lockForUpdate()->findOrFail($page->id);
            if (! $page->is_published) {
                return $page;
            }

            $page->forceFill(['is_published' => false])->save();

            if ($page->public_id && ! Storage::disk('published')->delete($this->key($page))) {
                throw new RuntimeException('The page release could not be removed.');
            }

            return $page;
        });
    }

    private function key(Page $page): string
    {
        return 'pages/'.$page->public_id.'.html';
    }
}
