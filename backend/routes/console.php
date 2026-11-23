<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('studio:provision {email}', function (string $email) {
    if (! filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 255) {
        $this->error('Enter a valid owner email.');
        return 1;
    }

    $user = User::where('email', $email)->first();
    $password = null;
    if (! $user) {
        $password = $this->secret('Owner password (at least 12 characters)');
        if (strlen((string) $password) < 12 || strlen((string) $password) > 72
            || $password !== $this->secret('Confirm owner password')) {
            $this->error('Use a matching password between 12 and 72 bytes.');
            return 1;
        }
    }

    $fixture = is_dir(resource_path('demo')) ? resource_path('demo') : base_path('../frontend/src/data');
    DB::transaction(function () use ($user, $email, $password, $fixture) {
        $owner = $user ?? User::create(['name' => 'Studio owner', 'email' => $email, 'password' => Hash::make($password)]);
        $project = $owner->projects()->firstOrCreate(['slug' => 'fieldwork'], [
            'name' => 'Fieldwork',
            'description' => 'Portfolio sample for an architecture studio.',
        ]);
        $project->pages()->firstOrCreate(['slug' => 'home'], [
            'title' => 'Fieldwork',
            'html' => file_get_contents($fixture.'/demo.html'),
            'css' => file_get_contents($fixture.'/demo.css'),
            'meta' => ['seoTitle' => 'Fieldwork Architecture'],
        ]);
    });

    $this->info('Owner and Fieldwork project ready. Sign in to publish the sample.');

    return 0;
})->purpose('Provision the private workspace owner and sample project');
