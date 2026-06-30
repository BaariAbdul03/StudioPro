<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AllowLocalSpaCors
{
    private function getAllowedOrigins(): array
    {
        $envOrigins = env('CORS_ALLOWED_ORIGINS');
        if ($envOrigins) {
            return array_map('trim', explode(',', $envOrigins));
        }

        return [
            'http://127.0.0.1:5173',
            'http://localhost:5173',
            'http://127.0.0.1:3000',
            'http://localhost:3000',
        ];
    }

    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('OPTIONS')) {
            return $this->withCorsHeaders(response()->noContent(), $request);
        }

        return $this->withCorsHeaders($next($request), $request);
    }

    private function withCorsHeaders(Response $response, Request $request): Response
    {
        $origin = $request->headers->get('Origin');
        $allowed = $this->getAllowedOrigins();

        if ($origin && in_array($origin, $allowed, true)) {
            $response->headers->set('Access-Control-Allow-Origin', $origin);
            $response->headers->set('Vary', 'Origin');
        }

        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, Accept');
        $response->headers->set('Access-Control-Allow-Credentials', 'true');
        $response->headers->set('Access-Control-Max-Age', '86400');

        return $response;
    }
}
