<?php

use App\Http\Controllers\ActualiteController;
use App\Http\Controllers\AlerteController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LigneController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

// Routes publiques — pas besoin d'être connecté
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);
Route::post('/contact', [MessageController::class, 'store']);


// Lecture publique — lignes, actualités et alertes visibles par tous
Route::get('/lignes',      [LigneController::class,     'index']);
Route::get('/actualites',  [ActualiteController::class, 'index']);
Route::get('/alertes',     [AlerteController::class,    'index']);

// Routes admin — token obligatoire + rôle admin
Route::middleware(['auth:sanctum', 'admin'])->group(function () {

    // CRUD complet sur les lignes
    Route::apiResource('admin/lignes',     LigneController::class);

    // CRUD complet sur les actualites
    Route::apiResource('admin/actualites', ActualiteController::class);

    // CRUD complet sur les alertes
    Route::apiResource('admin/alertes',    AlerteController::class);
});