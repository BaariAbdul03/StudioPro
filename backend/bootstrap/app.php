<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(fn (Request $request) => $request->is('api/*') ? null : '/login');
        $middleware->append(\App\Http\Middleware\SecurityHeadersMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );

        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
        });
        $exceptions->respond(function (\Symfony\Component\HttpFoundation\Response $response) {
            $request = request();
            if ($request->is('api/*') && $response->getStatusCode() === 419
                && $request->route()?->gatherMiddleware()
                && in_array('auth:web', $request->route()->gatherMiddleware(), true)
                && ! $request->user()) {
                $response = response()->json(['message' => 'Unauthenticated.'], 401);
            }
            if (app()->environment('production') && $request->is('api/*') && $response->getStatusCode() >= 500) {
                $response = response()->json(['error' => 'Something went wrong'], $response->getStatusCode());
            }
            return \App\Http\Middleware\SecurityHeadersMiddleware::apply($response);
        });
    })->create();
