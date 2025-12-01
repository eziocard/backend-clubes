<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TeamController;

Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);

Route::get('users',[AuthController::class,'index']);
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/teams/search', [TeamController::class, 'search']);
 Route::middleware('auth:sanctum')->group(function () {
    Route::post('/teams', [TeamController::class, 'store']);
    

 });
