<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StudentController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsUserAuth;
use App\Http\Middleware\IsTeamOrIsAdmin;
use App\Http\Controllers\AttendanceController;

Route::post('login', [AuthController::class, 'login']);
Route::controller(TeamController::class)->group(function () {
        Route::get('teams', 'index'); 
        Route::get('teams/{id}', 'edit'); 
        Route::put('teams/{id}', 'update'); 
        Route::delete('teams/{id}', 'destroy'); 
        });
Route::middleware([IsUserAuth::class])->group(function () {
    
    Route::get('attendances', [AttendanceController::class, 'index']);
    Route::post('attendances', [AttendanceController::class, 'store']);

    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'getUser']);

        
    Route::controller(StudentController::class)->group(function () {

        Route::get('levels', [LevelController::class, 'index']);
        Route::get('students', 'index');
        Route::get('students/search', 'search');
        Route::get('students/{rut}', 'show');

        Route::middleware([IsTeamOrIsAdmin::class])->group(function () {
            Route::post('students', 'store');
            Route::put('students/{rut}', 'update');
            Route::delete('students/{rut}', 'destroy');
            Route::delete('students/{rut}', 'destroy');

            Route::post('levels', [LevelController::class,'store']);
            Route::post('/levels', [LevelController::class, 'store']);
            Route::get('/levels/{id}/edit', [LevelController::class, 'edit']);
            Route::put('/levels/{id}', [LevelController::class, 'update']);
            Route::delete('/levels/{id}', [LevelController::class, 'destroy']);

            Route::get('/users', [UserController::class, 'index']);
            Route::get('/users/search', [UserController::class, 'search']);
            Route::get('/users/{rut}', [UserController::class, 'show']);
            Route::put('/users/{rut}', [UserController::class, 'update']);
            Route::delete('/users/{rut}', [UserController::class, 'destroy']);
        });
     
     
    });


    Route::middleware([IsAdmin::class])->group(function () {
        Route::post('teams', [TeamController::class,'store']);
        Route::post('register', [AuthController::class, 'register']);
    
    });

});
