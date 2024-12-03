<?php

use Illuminate\Http\Request;
use App\Http\Middleware\Admin;
use App\Http\Middleware\Chief;
use App\Http\Middleware\Guard;
use App\Http\Middleware\Client;
use App\Http\Middleware\Inactive;
use Illuminate\Foundation\Application;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'inactive' => Inactive::class,
            'noAdmin' => Admin::class,
            'noChief' => Chief::class,
            'noGuard' => Guard::class,
            'noClient' => Client::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => 'error',
                    'message' => strtolower($e->getMessage())
                ], 404);
            }
        });

        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => 'error',
                    'message' => strtolower($e->getMessage())
                ], 404);
            }
        });
    })->create();
