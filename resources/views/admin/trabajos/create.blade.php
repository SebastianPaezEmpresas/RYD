@extends('admin.layouts.app')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
   <div class="bg-gradient-to-r from-emerald-500 to-green-600 p-6 rounded-lg mb-6">
       <h1 class="text-2xl font-bold text-white flex items-center gap-2">
           <i class="fas fa-plus-circle"></i> Nuevo Trabajo
       </h1>
   </div>

   <div class="bg-white rounded-lg shadow-lg p-6">
       <form action="{{ route('admin.trabajos.store') }}" method="POST">
           @csrf
           <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
               <div>
                   <label class="block text-sm font-medium text-gray-700 mb-2">
                       Título del Trabajo
                   </label>
                   <input type="text" name="titulo" value="{{ old('titulo') }}" required 
                          class="w-full rounded-md border-gray-300 focus:border-emerald-500 focus:ring-emerald-200" 
                          placeholder="Título del trabajo">
               </div>

               <div>
                   <label class="block text-sm font-medium text-gray-700 mb-2">
                       Tipo de Trabajo
                   </label>
                   <input type="text" name="tipo_trabajo" value="{{ old('tipo_trabajo') }}" 
                          class="w-full rounded-md border-gray-300 focus:border-emerald-500 focus:ring-emerald-200" 
                          placeholder="Tipo de trabajo" required>
               </div>

               <div>
                   <label class="block text-sm font-medium text-gray-700 mb-2">
                       Cliente
                   </label>
                   <input type="text" name="cliente" value="{{ old('cliente') }}" required
                          class="w-full rounded-md border-gray-300 focus:border-emerald-500 focus:ring-emerald-200" 
                          placeholder="Nombre del cliente">
               </div>

               <div>
                   <label class="block text-sm font-medium text-gray-700 mb-2">
                       Email del Cliente
                   </label>
                   <input type="email" name="cliente_email" value="{{ old('cliente_email') }}" required
                          class="w-full rounded-md border-gray-300 focus:border-emerald-500 focus:ring-emerald-200" 
                          placeholder="email@ejemplo.com">
               </div>

               <div>
                   <label class="block text-sm font-medium text-gray-700 mb-2">
                       Dirección
                   </label>
                   <input type="text" name="direccion" value="{{ old('direccion') }}" required
                          class="w-full rounded-md border-gray-300 focus:border-emerald-500 focus:ring-emerald-200" 
                          placeholder="Dirección del trabajo">
               </div>

               <div class="md:col-span-2">
                   <label class="block text-sm font-medium text-gray-700 mb-2">
                       Descripción
                   </label>
                   <textarea name="descripcion" rows="3" required 
                             class="w-full rounded-md border-gray-300 focus:border-emerald-500 focus:ring-emerald-200" 
                             placeholder="Descripción detallada del trabajo">{{ old('descripcion') }}</textarea>
               </div>

               <div>
                   <label class="block text-sm font-medium text-gray-700 mb-2">
                       Trabajador Asignado
                   </label>
                   <select name="worker_id" required 
                           class="w-full rounded-md border-gray-300 focus:border-emerald-500 focus:ring-emerald-200">
                       <option value="">Seleccione un trabajador</option>
                       @foreach($trabajadores as $trabajador)
                           <option value="{{ $trabajador->id }}" {{ old('worker_id') == $trabajador->id ? 'selected' : '' }}>
                               {{ $trabajador->name }}
                           </option>
                       @endforeach
                   </select>
               </div>

               <div>
                   <label class="block text-sm font-medium text-gray-700 mb-2">
                       Presupuesto
                   </label>
                   <input type="number" step="0.01" name="presupuesto" value="{{ old('presupuesto') }}" required
                          class="w-full rounded-md border-gray-300 focus:border-emerald-500 focus:ring-emerald-200" 
                          placeholder="0.00">
               </div>

               <div>
                   <label class="block text-sm font-medium text-gray-700 mb-2">
                       Fecha Programada
                   </label>
                   <input type="date" name="fecha_programada" value="{{ old('fecha_programada') }}" required
                          class="w-full rounded-md border-gray-300 focus:border-emerald-500 focus:ring-emerald-200">
               </div>
           </div>

           @if ($errors->any())
               <div class="mt-4 bg-red-50 text-red-700 p-4 rounded-md">
                   <ul class="list-disc list-inside">
                       @foreach ($errors->all() as $error)
                           <li>{{ $error }}</li>
                       @endforeach
                   </ul>
               </div>
           @endif

           <div class="mt-6 flex justify-end gap-4">
               <a href="{{ route('admin.trabajos.index') }}" 
                  class="bg-gray-100 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors">
                   Cancelar
               </a>
               <button type="submit" 
                   class="bg-emerald-500 text-white px-4 py-2 rounded-lg hover:bg-emerald-600 transition-colors">
                   Crear Trabajo
               </button>
           </div>
       </form>
   </div>
</div>
@endsection