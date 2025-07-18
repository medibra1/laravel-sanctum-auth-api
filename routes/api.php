<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::controller(AuthController::class)->group( function() {
    Route::post('register', 'register');
    Route::post('login', 'login');

    Route::get('user', 'userProfile')->middleware('auth:sanctum', 'custom_abilities:supadmin');
    Route::get('logout', 'userLogout')->middleware('auth:sanctum', 'custom_abilities:supadmin');
});
