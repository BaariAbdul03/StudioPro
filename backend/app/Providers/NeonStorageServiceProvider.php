<?php

namespace App\Providers;

use Aws\CommandInterface;
use Aws\Middleware;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use LogicException;

class NeonStorageServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Storage::extend('neon', function ($app, array $config) {
            if (empty($config['endpoint']) || empty($config['key']) || empty($config['secret'])) {
                throw new LogicException('Neon object storage is missing its endpoint or credentials.');
            }

            $disk = $app['filesystem']->createS3Driver($config);
            // Neon grants visibility at bucket level. Flysystem adds an ACL to writes by default.
            $disk->getClient()->getHandlerList()->appendInit(Middleware::mapCommand(
                function (CommandInterface $command): CommandInterface {
                    unset($command['ACL']);

                    return $command;
                }
            ), 'neon.bucket-visibility');

            return $disk;
        });
    }
}
