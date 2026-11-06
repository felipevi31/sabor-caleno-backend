<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\AdminDashboardController;
use App\Http\Controllers\Api\ReportController;

/*
|--------------------------------------------------------------------------
| API Routes - Sabor Caleño
|--------------------------------------------------------------------------
*/

// Rutas públicas de Autenticación
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas (requieren token Bearer de Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    
    // Sesión de Usuario
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Módulo de Administración (Métricas y Panel)
    Route::get('/admin/metrics', [AdminDashboardController::class, 'getMetrics']);

    // Módulo de Reportes Contables (Excel / CSV separados para ventas y clientes)
    Route::get('/admin/reports/sales-excel', [ReportController::class, 'salesExcel']);
    Route::get('/admin/reports/users-excel', [ReportController::class, 'usersExcel']);
});