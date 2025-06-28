<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EquipoController;

// Ruta de prueba
Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);
});

// Rutas públicas
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    
    // Rutas de usuarios
    Route::apiResource('users', UserController::class);
    
    // Rutas de equipos
    Route::apiResource('equipos', EquipoController::class);
}); 