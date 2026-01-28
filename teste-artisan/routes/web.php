<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SuperAdminController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rota pública
Route::get('/', function () {
    return view('dashboard.index');
});

// Rotas de autenticação
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

// Rotas protegidas para usuários autenticados (todos os níveis)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
});

// Rotas protegidas para Admin (admin e superadmin)
Route::middleware(['auth', 'role:admin,superadmin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])
            ->name('dashboard');
        
        Route::get('/users', [AdminController::class, 'users'])
            ->name('users');
        
        Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])
            ->name('users.edit');
        
        Route::put('/users/{id}', [AdminController::class, 'updateUser'])
            ->name('users.update');
    });

// Rotas protegidas para SuperAdmin (apenas superadmin)
Route::middleware(['auth', 'role:superadmin'])
    ->prefix('superadmin')
    ->name('superadmin.')
    ->group(function () {
        Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])
            ->name('dashboard');
        
        Route::get('/users', [SuperAdminController::class, 'users'])
            ->name('users');
        
        Route::get('/users/{id}/edit', [SuperAdminController::class, 'editUser'])
            ->name('users.edit');
        
        Route::put('/users/{id}', [SuperAdminController::class, 'updateUser'])
            ->name('users.update');
        
        Route::delete('/users/{id}', [SuperAdminController::class, 'deleteUser'])
            ->name('users.delete');
        
        Route::get('/settings', [SuperAdminController::class, 'settings'])
            ->name('settings');
    });
