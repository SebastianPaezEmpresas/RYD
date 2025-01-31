<?php
// resources/views/admin/dashboard.blade.php

$stats = [
   'totalTrabajadores' => App\Models\User::where('role', 'worker')->where('active', true)->count(),
   'trabajosCompletados' => App\Models\Trabajo::where('estado', 'completado')->count(),
   'satisfaccionPromedio' => number_format(App\Models\Encuesta::avg('calificacion') * 20, 1),
   'proximosTrabajos' => App\Models\Trabajo::where('estado', 'pendiente')
       ->where('fecha_programada', '>=', now())
       ->orderBy('fecha_programada')
       ->with('worker')
       ->take(5)
       ->get(),
   'actividadReciente' => App\Models\Trabajo::with('worker')
       ->whereIn('estado', ['en_progreso', 'completado'])
       ->latest('updated_at')
       ->take(5)
       ->get(),
   'notificaciones' => auth()->user()->notifications()
       ->latest()
       ->take(5)
       ->get()
];
?>

@extends('admin.layouts.app')

@push('styles')
<style>
   .notification-dot {
       animation: pulse 2s infinite;
   }
   @keyframes pulse {
       0% { transform: scale(0.95); }
       70% { transform: scale(1); }
       100% { transform: scale(0.95); }
   }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-emerald-50 to-green-100">
   <nav class="bg-white/80 backdrop-blur-md border-b border-gray-200 sticky top-0 z-50">
       <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
           <div class="flex justify-between h-16">
               <div class="flex items-center">
                   <img class="h-10 w-auto" src="{{ asset('img/logo.png') }}" alt="RYD">
                   <div class="ml-8 flex space-x-6">
                       <a href="{{ route('admin.dashboard') }}" class="group flex items-center px-3 py-2">
                           <i class="fas fa-tachometer-alt mr-2 text-emerald-500"></i>
                           <span class="text-gray-900">Dashboard</span>
                       </a>
                       <a href="{{ route('admin.trabajadores.index') }}" class="group flex items-center px-3 py-2">
                           <i class="fas fa-users mr-2 text-gray-400 group-hover:text-emerald-500"></i>
                           <span class="text-gray-500 group-hover:text-gray-900">Trabajadores</span>
                       </a>
                       <a href="{{ route('admin.trabajos.index') }}" class="group flex items-center px-3 py-2">
                           <i class="fas fa-briefcase mr-2 text-gray-400 group-hover:text-emerald-500"></i>
                           <span class="text-gray-500 group-hover:text-gray-900">Trabajos</span>
                       </a>
                   </div>
               </div>
               <div class="flex items-center gap-4">
                 <!-- Notificaciones -->
                 
 

                   <form action="{{ route('logout') }}" method="POST">
                       @csrf
                       <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors">
                           <i class="fas fa-sign-out-alt mr-2"></i>
                           Cerrar Sesión
                       </button>
                   </form>
               </div>
           </div>
       </div>
   </nav>

   <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
       <!-- Header -->
       <div class="bg-gradient-to-r from-emerald-500 to-green-600 rounded-lg shadow-lg p-6 mb-6">
           <h1 class="text-2xl font-bold text-white flex items-center">
               <i class="fas fa-chart-line mr-3"></i>
               Dashboard RYD Jardinería
           </h1>
       </div>

       <!-- Stats -->
       <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
           <!-- Total Trabajadores -->
           <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow p-6">
               <div class="flex items-center">
                   <div class="p-3 rounded-full bg-emerald-100">
                       <i class="fas fa-users text-emerald-600 text-xl"></i>
                   </div>
                   <div class="ml-4">
                       <h3 class="text-lg font-semibold text-gray-900">{{ $stats['totalTrabajadores'] }}</h3>
                       <p class="text-sm text-gray-500">Total Trabajadores</p>
                   </div>
               </div>
           </div>

           <!-- Trabajos Completados -->
           <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow p-6">
               <div class="flex items-center">
                   <div class="p-3 rounded-full bg-green-100">
                       <i class="fas fa-check-circle text-green-600 text-xl"></i>
                   </div>
                   <div class="ml-4">
                       <h3 class="text-lg font-semibold text-gray-900">{{ $stats['trabajosCompletados'] }}</h3>
                       <p class="text-sm text-gray-500">Trabajos Completados</p>
                   </div>
               </div>
           </div>

           <!-- Satisfacción -->
           <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow p-6">
               <div class="flex items-center">
                   <div class="p-3 rounded-full bg-emerald-100">
                       <i class="fas fa-smile text-emerald-600 text-xl"></i>
                   </div>
                   <div class="ml-4">
                       <h3 class="text-lg font-semibold text-gray-900">{{ $stats['satisfaccionPromedio'] }}%</h3>
                       <p class="text-sm text-gray-500">Satisfacción Promedio</p>
                   </div>
               </div>
           </div>
       </div>

       <!-- Content Grid -->
       <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
           <!-- Próximos Trabajos -->
           <div class="lg:col-span-2 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
               <div class="p-6 border-b border-gray-200">
                   <h2 class="text-lg font-semibold text-gray-900">Próximos Trabajos</h2>
               </div>
               <div class="p-6">
                   @if($stats['proximosTrabajos']->count())
                       <div class="overflow-x-auto">
                           <table class="w-full">
                               <thead>
                                   <tr class="text-left text-sm font-medium text-gray-500">
                                       <th class="pb-3 px-3">Trabajo</th>
                                       <th class="pb-3 px-3">Cliente</th>
                                       <th class="pb-3 px-3">Trabajador</th>
                                       <th class="pb-3 px-3">Fecha</th>
                                   </tr>
                               </thead>
                               <tbody class="divide-y divide-gray-200">
                                   @foreach($stats['proximosTrabajos'] as $trabajo)
                                       <tr class="hover:bg-gray-50">
                                           <td class="py-3 px-3">
                                               <div class="font-medium text-gray-900">{{ $trabajo->tipo_trabajo }}</div>
                                               <div class="text-sm text-gray-500">{{ Str::limit($trabajo->descripcion, 30) }}</div>
                                           </td>
                                           <td class="py-3 px-3 text-gray-500">{{ $trabajo->cliente }}</td>
                                           <td class="py-3 px-3 text-gray-500">{{ $trabajo->worker->name }}</td>
                                           <td class="py-3 px-3 text-gray-500">
                                               {{ $trabajo->fecha_programada->format('d/m/Y') }}
                                           </td>
                                       </tr>
                                   @endforeach
                               </tbody>
                           </table>
                       </div>
                   @else
                       <p class="text-gray-500 text-center py-4">No hay trabajos programados</p>
                   @endif
               </div>
           </div>

           <!-- Actividad Reciente -->
           <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
               <div class="p-6 border-b border-gray-200">
                   <h2 class="text-lg font-semibold text-gray-900">Actividad Reciente</h2>
               </div>
               <div class="p-6">
                   @if($stats['actividadReciente']->count())
                       <div class="space-y-4">
                           @foreach($stats['actividadReciente'] as $trabajo)
                               <div class="flex items-start">
                                   <div class="flex-shrink-0">
                                       <div class="w-8 h-8 rounded-full {{ $trabajo->estado === 'completado' ? 'bg-green-100' : 'bg-emerald-100' }} flex items-center justify-center">
                                           <i class="fas {{ $trabajo->estado === 'completado' ? 'fa-check' : 'fa-clock' }} {{ $trabajo->estado === 'completado' ? 'text-green-600' : 'text-emerald-600' }}"></i>
                                       </div>
                                   </div>
                                   <div class="ml-4">
                                       <p class="text-sm font-medium text-gray-900">
                                           {{ $trabajo->tipo_trabajo }}
                                       </p>
                                       <p class="text-sm text-gray-500">
                                           {{ $trabajo->worker->name }} - 
                                           {{ $trabajo->estado === 'completado' ? 'completó' : 'inició' }} el trabajo
                                       </p>
                                       <p class="text-xs text-gray-400">
                                           {{ $trabajo->updated_at->diffForHumans() }}
                                       </p>
                                   </div>
                               </div>
                           @endforeach
                       </div>
                   @else
                       <p class="text-gray-500 text-center py-4">No hay actividad reciente</p>
                   @endif
               </div>
           </div>
       </div>
   </div>
</div>

@push('scripts')
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush

@endsection