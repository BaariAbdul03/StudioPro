<?php

namespace App\Jobs;

use App\Services\StitchDraft;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

class GenerateStitchDraft implements ShouldBeEncrypted, ShouldQueue
{
    use Queueable;

    public int $timeout = 158;

    public int $tries = 1;

    public bool $failOnTimeout = true;

    public function __construct(private string $cacheKey, private string $prompt) {}

    public function handle(StitchDraft $stitch): void
    {
        try {
            $data = $stitch->generate($this->prompt);
            Cache::put($this->cacheKey, ['status' => 'ready'] + $data, now()->addMinutes(20));
        } catch (Throwable $error) {
            $this->failed($error);
        }
    }

    public function failed(?Throwable $error): void
    {
        Log::warning('Stitch draft generation failed.', ['type' => $error ? $error::class : null]);
        Cache::put($this->cacheKey, ['status' => 'failed'], now()->addMinutes(20));
    }
}
