<?php

use App\Http\Middleware\ActiveAdminMiddleware;
use App\Http\Middleware\HandleAppearance;
use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))

    ->withRouting(

        web: __DIR__.'/../routes/web.php',

        api: __DIR__.'/../routes/api.php',

        commands: __DIR__.'/../routes/console.php',

        health: '/up',

    )

    ->withMiddleware(function (Middleware $middleware): void {

        /*
        |--------------------------------------------------------------------------
        | Cookies
        |--------------------------------------------------------------------------
        */

        $middleware->encryptCookies(
            except: [
                'appearance',
                'sidebar_state',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Middleware Aliases
        |--------------------------------------------------------------------------
        */

        $middleware->alias([

            'role' => RoleMiddleware::class,

            'active.admin' => ActiveAdminMiddleware::class,

        ]);

        /*
        |--------------------------------------------------------------------------
        | Web Middleware
        |--------------------------------------------------------------------------
        */

        $middleware->web(append: [

            HandleAppearance::class,

            HandleInertiaRequests::class,

            AddLinkHeadersForPreloadedAssets::class,

        ]);

    })

    ->withExceptions(function (Exceptions $exceptions): void {

        /*
        |--------------------------------------------------------------------------
        | JSON Response Detection
        |--------------------------------------------------------------------------
        */

        $exceptions->shouldRenderJsonWhen(

            fn (Request $request) =>

                $request->is('api/*')

                ||

                $request->expectsJson(),

        );

        /*
        |--------------------------------------------------------------------------
        | Validation Errors
        |--------------------------------------------------------------------------
        */

        $exceptions->render(function (
            ValidationException $e,
            Request $request
        ) {

            if (! $request->is('api/*')) {
                return null;
            }

            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        });

        /*
        |--------------------------------------------------------------------------
        | Authentication Errors
        |--------------------------------------------------------------------------
        */

        $exceptions->render(function (
            AuthenticationException $e,
            Request $request
        ) {

            if (! $request->is('api/*')) {
                return null;
            }

            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        });

        /*
        |--------------------------------------------------------------------------
        | Authorization Errors
        |--------------------------------------------------------------------------
        */

        $exceptions->render(function (
            AuthorizationException $e,
            Request $request
        ) {

            if (! $request->is('api/*')) {
                return null;
            }

            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to perform this action.',
            ], 403);
        });

        /*
        |--------------------------------------------------------------------------
        | Route Not Found
        |--------------------------------------------------------------------------
        */

        $exceptions->render(function (
            NotFoundHttpException $e,
            Request $request
        ) {

            if (! $request->is('api/*')) {
                return null;
            }

            return response()->json([
                'success' => false,
                'message' => 'Resource not found.',
            ], 404);
        });

        /*
        |--------------------------------------------------------------------------
        | Method Not Allowed
        |--------------------------------------------------------------------------
        */

        $exceptions->render(function (
            MethodNotAllowedHttpException $e,
            Request $request
        ) {

            if (! $request->is('api/*')) {
                return null;
            }

            return response()->json([
                'success' => false,
                'message' => 'Method not allowed.',
            ], 405);
        });

        /*
        |--------------------------------------------------------------------------
        | Unexpected Server Errors
        |--------------------------------------------------------------------------
        */

        $exceptions->render(function (
            \Throwable $e,
            Request $request
        ) {

            if (! $request->is('api/*')) {
                return null;
            }

            /*
            |--------------------------------------------------------------------------
            | Production
            |--------------------------------------------------------------------------
            |
            | Never expose internal exception details to the client.
            |
            */

            return response()->json([
                'success' => false,
                'message' => 'Server error.',
            ], 500);
        });

    })

    ->create();