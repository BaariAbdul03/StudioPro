<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        return self::apply($next($request));
    }

    public static function apply(Response $response): Response
    {
        $media = parse_url(config('filesystems.disks.media.endpoint') ?? '', PHP_URL_HOST);
        $imageSource = $media && preg_match('/^[a-z0-9.-]+$/iD', $media) ? " https://{$media}" : '';

        if (! $response->headers->has('Content-Security-Policy')) {
            $response->headers->set('Content-Security-Policy', "default-src 'self'; script-src 'self'; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data: blob:{$imageSource}; frame-src 'self' about: blob:; frame-ancestors 'none'; form-action 'self'; object-src 'none'; base-uri 'none'");
        }

        $response->headers->add([
            'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains',
            'X-Frame-Options' => 'DENY',
            'X-Content-Type-Options' => 'nosniff',
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
        ]);

        return $response;
    }
}
