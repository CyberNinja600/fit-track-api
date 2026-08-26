<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\ProgramDetailsController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/refresh', [AuthController::class, 'refresh']);

    Route::middleware('auth:api')->group(function () {
        Route::get('/user', [AuthController::class, 'user']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

Route::middleware('auth:api')->group(function () {
    Route::post('/program', [ProgramController::class, 'create'])->middleware('trainer');
    Route::get('/program', [ProgramController::class, 'read']);
    Route::post('/program/update', [ProgramController::class, 'update'])->middleware('trainer');
    Route::delete('/program', [ProgramController::class, 'delete'])->middleware('trainer');

    Route::post('/program-details', [ProgramDetailsController::class, 'create'])->middleware('trainer');
    Route::get('/program-details', [ProgramDetailsController::class, 'read']);
    Route::post('/program-details/update', [ProgramDetailsController::class, 'update']);
    Route::delete('/program-details', [ProgramDetailsController::class, 'delete'])->middleware('trainer');
});
