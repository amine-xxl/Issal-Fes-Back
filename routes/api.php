<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Routes publiques — pas besoin d'être connecté
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);