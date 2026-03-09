<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LivreController;
use App\Http\Controllers\AdminController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me',      [AuthController::class, 'me']);

    Route::get('/categories',       [CategoryController::class, 'index']);
    Route::get('/categories/{category}', [CategoryController::class, 'show']);

    Route::middleware('admin')->group(function () {
        Route::post('/categories',              [CategoryController::class, 'store']);
        Route::put('/categories/{category}',    [CategoryController::class, 'update']);
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);
    });

    Route::get('/livres/recherche',  [LivreController::class, 'recherche']);
    Route::get('/livres/populaires', [LivreController::class, 'populaires']);
    Route::get('/livres/recents',    [LivreController::class, 'recents']);

    Route::get('/livres',          [LivreController::class, 'index']);
    Route::get('/livres/{livre}',  [LivreController::class, 'show']);

    Route::middleware('admin')->group(function () {
        Route::post('/livres',             [LivreController::class, 'store']);
        Route::put('/livres/{livre}',      [LivreController::class, 'update']);
        Route::delete('/livres/{livre}',   [LivreController::class, 'destroy']);
    });

    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/stats',           [AdminController::class, 'stats']);
        Route::get('/livres/degrades', [AdminController::class, 'degrades']);
    });
});