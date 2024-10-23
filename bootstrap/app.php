<?php

use App\Http\Middleware\CustomCheckAbilities;
use App\Http\Middleware\ForceJsonRequestHeader;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            // 'abilities' => CheckAbilities::class,
            // 'ability' => CheckForAnyAbility::class,
            'custom_abilities' => CustomCheckAbilities::class,
        ]);
        $middleware->append(ForceJsonRequestHeader::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
           //Custom Rendering
           $exceptions->render(function (AuthenticationException $e, Request $request) {

            return response()->json([
                'status' => false,
                'message' => 'Not Authorized',
                'code' => 401
            ], 401);
        });
    })->create();
