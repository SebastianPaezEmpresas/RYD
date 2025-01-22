<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TrabajadoresController;
use App\Http\Controllers\Admin\TrabajoController;
use App\Http\Controllers\Admin\EncuestaController;
use App\Http\Controllers\EncuestaPublicController;

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
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Trabajadores
    Route::resource('trabajadores', TrabajadoresController::class);

    // Trabajos
    Route::get('/trabajos/filtrar', [TrabajoController::class, 'filtrar'])->name('trabajos.filtrar');
    Route::patch('/trabajos/{trabajo}/fechas', [TrabajoController::class, 'actualizarFechas'])
        ->name('trabajos.actualizarFechas');
    Route::resource('trabajos', TrabajoController::class);

    // Encuestas
    Route::controller(EncuestaController::class)->group(function () {
        Route::get('/encuestas/exportar', 'exportar')->name('encuestas.exportar');
        Route::get('/encuestas/estadisticas', 'estadisticas')->name('encuestas.estadisticas');
        Route::post('/encuestas/{encuesta}/enviar', 'enviar')->name('encuestas.enviar');
        Route::post('/encuestas/{encuesta}/reenviar', 'reenviar')->name('encuestas.reenviar');
    });
    Route::resource('encuestas', EncuestaController::class);
});

// Rutas públicas para encuestas
Route::controller(EncuestaPublicController::class)->group(function () {
    Route::get('/encuesta/{token}', 'show')->name('encuesta.publica');
    Route::post('/encuesta/{token}', 'store')->name('encuesta.responder');
    Route::get('/encuesta/gracias', 'gracias')->name('encuesta.gracias');
});

// Autenticación
require __DIR__.'/auth.php';