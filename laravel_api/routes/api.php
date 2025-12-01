<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\LevelController; 
Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);


Route::get('users',[AuthController::class,'index']);


Route::get('/teams/search', [TeamController::class, 'search']);
Route::post('/teams', [TeamController::class, 'store']);


Route::post('/levels', [LevelController::class, 'store']);

