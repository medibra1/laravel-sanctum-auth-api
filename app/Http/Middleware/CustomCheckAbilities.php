<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
// use Symfony\Component\HttpFoundation\Response;
use Laravel\Sanctum\Exceptions\MissingAbilityException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CustomCheckAbilities
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$abilities)
     {
         try {
             // Call the original CheckAbilities logic
             return app(\Laravel\Sanctum\Http\Middleware\CheckAbilities::class)->handle($request, $next, ...$abilities);
         } catch (MissingAbilityException $exception) {
             return response()->json([
                 'message' => $exception->getMessage() . " You don't have the permission."
             ], 403);
         } catch (AccessDeniedHttpException $exception) {
             // Handle other access denied exceptions if necessary
             return response()->json([
                 'message' => 'Access denied.'
             ], 403);
         }
     }
}
