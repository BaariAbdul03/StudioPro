<?php

$storageEndpoint = env('NEON_STORAGE_ENDPOINT');
$publishedBucket = env('NEON_PUBLISHED_BUCKET', 'studio-published');
$defaultDiskDriver = env('APP_ENV', 'production') === 'production' ? 'neon' : 'local';
$mediaDriver = env('MEDIA_DISK_DRIVER', $defaultDiskDriver);
$publishedDriver = env('PUBLISHED_DISK_DRIVER', $defaultDiskDriver);
$storage = [
    'key' => env('NEON_STORAGE_ACCESS_KEY_ID'),
    'secret' => env('NEON_STORAGE_SECRET_ACCESS_KEY'),
    'region' => env('NEON_STORAGE_REGION', 'eu-central-1'),
    'endpoint' => $storageEndpoint,
    'use_path_style_endpoint' => true,
    'visibility' => 'private',
    'throw' => true,
    'http' => ['connect_timeout' => 5, 'timeout' => 15],
    'request_checksum_calculation' => 'when_required',
    'response_checksum_validation' => 'when_required',
];

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application for file storage.
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Below you may configure as many filesystem disks as necessary, and you
    | may even configure multiple disks for the same driver. Examples for
    | most supported storage drivers are configured here for reference.
    |
    | Supported drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'media' => [
            ...$storage,
            'driver' => $mediaDriver,
            'root' => $mediaDriver === 'local' ? storage_path('app/private/media') : '',
            'bucket' => env('NEON_MEDIA_BUCKET', 'studio-media'),
        ],

        'published' => [
            ...$storage,
            'driver' => $publishedDriver,
            'root' => $publishedDriver === 'local' ? env('PUBLISHED_LOCAL_ROOT', storage_path('app/private/published')) : '',
            'bucket' => $publishedBucket,
            'url' => $publishedDriver === 'neon'
                ? env('PUBLISHED_BUCKET_URL') ?: rtrim((string) $storageEndpoint, '/').'/'.$publishedBucket
                : '/studio-published',
        ],

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => true,
            'throw' => false,
            'report' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => rtrim(env('APP_URL', 'http://localhost'), '/').'/storage',
            'visibility' => 'public',
            'throw' => false,
            'report' => false,
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
            'report' => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
