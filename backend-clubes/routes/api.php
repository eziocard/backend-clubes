<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserAuthController;

Route::prefix('user')->group(function () {
    Route::post('/register', [UserAuthController::class, 'register']);
    Route::post('/login', [UserAuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/profile', [UserAuthController::class, 'profile']);
        Route::post('/logout', [UserAuthController::class, 'logout']);
    });
});

Route::get('/teams', [TeamController::class, 'index']); // la puse para desarrollo nomas jeje
Route::get('/levels', [LevelController::class, 'index']);
Route::get('/students', [StudentController::class, 'index']);  


Route::middleware(['auth:sanctum', RoleMiddleware::class])->group(function () {
    Route::post('/teams', [TeamController::class, 'store']);
    Route::put('/teams/{id}', [TeamController::class, 'update']);
    Route::delete('/teams/{id}', [TeamController::class, 'destroy']);


    Route::post('/levels', [LevelController::class, 'store']);
    Route::put('/levels/{id}', [LevelController::class, 'update']);
    Route::delete('/levels/{id}', [LevelController::class, 'destroy']);

    Route::post('/students', [StudentController::class, 'store']);
    Route::put('/students/{id}', [StudentController::class, 'update']);
    Route::delete('/students/{id}', [StudentController::class, 'destroy']);

});