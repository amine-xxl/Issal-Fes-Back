<?php

use App\Http\Controllers\ActualiteController;
use App\Http\Controllers\AlerteController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LigneController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProInfoController;
use App\Http\Controllers\UserController;
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

    // Liste et suppression des messages de contact
    Route::get('admin/messages',    [MessageController::class, 'index']);
    Route::delete('admin/messages/{message}', [MessageController::class, 'destroy']);

    // Gestion des affectations chauffeurs
    Route::get('admin/chauffeurs', [ProInfoController::class, 'index']);
    Route::post('admin/chauffeurs', [ProInfoController::class, 'store']);
});

// Ces routes nécessitent d'être connecté (token Sanctum) afin de pouvoir modifier le profil ou le mot de passe

Route::middleware('auth:sanctum')->group(function () {

    // Informations professionnelles du chauffeur
    Route::get('/pro-info', [ProInfoController::class, 'getDriverInfo']);

    // Modifier le nom de l'utilisateur connecté
    Route::put('/user/update', [UserController::class, 'update']);

    // Modifier le mot de passe de l'utilisateur connecté
    Route::put('/user/password', [UserController::class, 'updatePassword']);
});
