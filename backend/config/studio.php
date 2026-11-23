<?php

return [
    'public_url' => env('STUDIO_PUBLIC_URL'),
    'ai_enabled' => env('STUDIO_AI_ENABLED', env('APP_ENV', 'production') !== 'production'),
    'stitch_enabled' => env('STUDIO_STITCH_ENABLED', false),
    'stitch_queue' => PHP_OS_FAMILY === 'Windows' ? 'database' : 'background',
];
