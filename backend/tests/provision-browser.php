<?php

use App\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Hash;

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

if (! app()->environment('testing') || ! getenv('STUDIO_BROWSER_PASSWORD')) {
    exit(1);
}

User::create([
    'name' => 'Browser test owner',
    'email' => 'owner@example.test',
    'password' => Hash::make(getenv('STUDIO_BROWSER_PASSWORD')),
]);
