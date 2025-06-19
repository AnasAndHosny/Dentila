<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;

Route::prefix('v1')->group(function () {
    Route::get('test', function () {
        return response()->json(['message' => 'API V1 working']);
    });

    Route::controller(AuthController::class)->group(function () {
        Route::post('login', 'login');
        Route::get('logout', 'logout')->middleware('auth:sanctum');
    });
});
