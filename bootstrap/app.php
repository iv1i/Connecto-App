<?php

use App\Http\Middleware\EnsureTokenIsValid;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(function ($request) {
            if ($request->expectsJson() || str_starts_with($request->path(), 'api/')) {
                return route('api.login');
            }
            return route('login');
        });
        $middleware->statefulApi();
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (MethodNotAllowedHttpException $e) {
            return response()->json([
                'error' => 'Method is not supported.'
            ], 401);
        });
        $exceptions->render(function (RouteNotFoundException $e) {
            return response()->json([
                'error' => 'Invalid endpoint or unauthorized access.'
            ], 404);
        });
        $exceptions->render(function (ValidationException $e) {
            return response()->json([
                'error' => $e->validator->errors()->first()
            ], 422);
        });
    })->create();
