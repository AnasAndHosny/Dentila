<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('test', function () {
        return response()->json(['message' => 'API V1 working']);
    });
});
