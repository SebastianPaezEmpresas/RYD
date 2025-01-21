<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TrabajadoresController;
use App\Http\Controllers\Admin\TrabajoController;
use App\Http\Controllers\Admin\EncuestaController;

// Ruta de bienvenida
Route::get('/', function () {
    return view('welcome');
});

// Ruta del dashboard con autenticación
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas de perfil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rutas de administración
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Rutas para trabajadores
    Route::get('/trabajadores', [TrabajadoresController::class, 'index'])->name('trabajadores.index');
    Route::get('/trabajadores/create', [TrabajadoresController::class, 'create'])->name('trabajadores.create');
    Route::post('/trabajadores', [TrabajadoresController::class, 'store'])->name('trabajadores.store');
    Route::get('/trabajadores/{id}/edit', [TrabajadoresController::class, 'edit'])->name('trabajadores.edit');
    Route::put('/trabajadores/{id}', [TrabajadoresController::class, 'update'])->name('trabajadores.update');
    Route::delete('/trabajadores/{id}', [TrabajadoresController::class, 'destroy'])->name('trabajadores.destroy');

    // Rutas para trabajos
    Route::get('/trabajos', [TrabajoController::class, 'index'])->name('trabajos.index');
    Route::get('/trabajos/create', [TrabajoController::class, 'create'])->name('trabajos.create');
    Route::post('/trabajos', [TrabajoController::class, 'store'])->name('trabajos.store');
    Route::get('/trabajos/{id}/edit', [TrabajoController::class, 'edit'])->name('trabajos.edit');
    Route::put('/trabajos/{id}', [TrabajoController::class, 'update'])->name('trabajos.update');
    Route::delete('/trabajos/{id}', [TrabajoController::class, 'destroy'])->name('trabajos.destroy');

    // Rutas para encuestas
    Route::get('/encuestas', [EncuestaController::class, 'index'])->name('encuestas.index');
    Route::get('/encuestas/create', [EncuestaController::class, 'create'])->name('encuestas.create');
    Route::post('/encuestas', [EncuestaController::class, 'store'])->name('encuestas.store');
    Route::get('/encuestas/{id}/edit', [EncuestaController::class, 'edit'])->name('encuestas.edit');
    Route::put('/encuestas/{id}', [EncuestaController::class, 'update'])->name('encuestas.update');
    Route::delete('/encuestas/{id}', [EncuestaController::class, 'destroy'])->name('encuestas.destroy');
});

// Autenticación
require __DIR__.'/auth.php';
