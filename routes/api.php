<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProgramController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/refresh', [AuthController::class, 'refresh']);

    Route::middleware('auth:api')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

Route::middleware('auth:api')->group(function () {
    Route::post('/program', [ProgramController::class, 'create'])->middleware('trainer');
    Route::post('/program/create', [ProgramController::class, 'create'])->middleware('trainer');
    Route::get('/program', [ProgramController::class, 'read'])->middleware('trainer');
    Route::delete('/program/delete', [ProgramController::class, 'delete'])->middleware('trainer');
    Route::post('/program/update', [ProgramController::class, 'update'])->middleware('trainer'); // patch or put is sending empty json from postman
});
