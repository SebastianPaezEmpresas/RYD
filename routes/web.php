<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\TipoTrabajoController; 
use App\Http\Controllers\TrabajoController;
use App\Http\Controllers\EncuestaController;
use App\Http\Controllers\TrabajoFotoController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
   Route::get('/', [AuthController::class, 'showLogin']);
   Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
   Route::post('/login', [AuthController::class, 'login']);
   Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
   Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
   Route::get('/home', function() {
       if (auth()->user()->role == 'admin') {
           return redirect()->route('admin.dashboard');
       } elseif (auth()->user()->role == 'worker') {
           return redirect()->route('worker.dashboard');
       }
       return redirect('/login');
   });

   Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

   Route::middleware('role:admin')->prefix('admin')->group(function () {
       Route::get('/dashboard', function() {
           return view('admin.dashboard');
       })->name('admin.dashboard');
       
       Route::get('/trabajadores', [WorkerController::class, 'index'])->name('admin.trabajadores.index');
       Route::get('/trabajadores/create', [WorkerController::class, 'create'])->name('admin.trabajadores.create');
       Route::post('/trabajadores', [WorkerController::class, 'store'])->name('admin.trabajadores.store');
       Route::get('/trabajadores/{worker}/edit', [WorkerController::class, 'edit'])->name('admin.trabajadores.edit');
       Route::put('/trabajadores/{worker}', [WorkerController::class, 'update'])->name('admin.trabajadores.update');
       Route::delete('/trabajadores/{worker}', [WorkerController::class, 'destroy'])->name('admin.trabajadores.destroy');

       Route::resource('/tipos-trabajo', TipoTrabajoController::class)->names('admin.tipos_trabajo');
       
       // Trabajos routes
       Route::get('/trabajos/schedule', [TrabajoController::class, 'schedule'])->name('admin.trabajos.schedule');
       Route::post('/trabajos/schedule', [TrabajoController::class, 'scheduleStore'])->name('admin.trabajos.schedule.store');
              Route::resource('/trabajos', TrabajoController::class)->names('admin.trabajos');
       
       Route::resource('/encuestas', EncuestaController::class)->only(['index', 'show'])->names('admin.encuestas');

       
   });

   Route::middleware('role:worker')->prefix('worker')->group(function () {
       Route::get('/dashboard', function () {
           return view('dashboard.worker');
       })->name('worker.dashboard');
       
       Route::get('/trabajos', [TrabajoController::class, 'workerTrabajos'])->name('worker.trabajos');
       Route::post('/trabajos/{trabajo}/iniciar', [TrabajoController::class, 'iniciarTrabajo'])->name('worker.trabajos.iniciar');
       Route::post('/trabajos/{trabajo}/finalizar', [TrabajoController::class, 'finalizarTrabajo'])->name('worker.trabajos.finalizar');
       Route::post('/trabajos/{trabajo}/fotos', [TrabajoFotoController::class, 'store'])->name('worker.trabajos.fotos.store');
       Route::delete('/trabajos/fotos/{foto}', [TrabajoFotoController::class, 'destroy'])->name('worker.trabajos.fotos.destroy');
   });
});

Route::get('/encuesta/{token}', [EncuestaController::class, 'create'])->name('encuestas.create');
Route::post('/encuesta/{token}', [EncuestaController::class, 'store'])->name('encuestas.store');
Route::get('/encuesta/gracias', [EncuestaController::class, 'gracias'])->name('encuestas.gracias');