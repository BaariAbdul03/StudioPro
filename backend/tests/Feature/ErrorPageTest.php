<?php

namespace Tests\Feature;

use Tests\TestCase;

class ErrorPageTest extends TestCase
{
    public function test_web_failures_show_branded_pages_with_the_correct_http_status(): void
    {
        config(['app.debug' => false]);

        foreach ([
            '/error/403' => [403, 'Access denied'],
            '/missing-page' => [404, 'Page not found'],
            '/error/500' => [500, 'Something went wrong'],
            '/error/503' => [503, 'Workspace unavailable'],
        ] as $path => [$status, $title]) {
            $this->get($path)
                ->assertStatus($status)
                ->assertSee($title)
                ->assertSee('/welcome#faq')
                ->assertHeader('X-Frame-Options', 'DENY');
        }

        $this->getJson('/api/missing-page')->assertNotFound()->assertJsonStructure(['message']);
    }
}