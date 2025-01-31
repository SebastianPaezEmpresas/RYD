@extends('admin.layouts.app')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-emerald-50 to-green-100">
   <div class="max-w-3xl mx-auto pt-8 px-4">
       <div class="bg-emerald-500 rounded-t-xl p-4">
           <div class="flex justify-between items-center">
               <h1 class="text-xl font-bold text-white flex items-center gap-2">
                   <i class="fas fa-edit"></i> Editar Trabajo
               </h1>
               <a href="{{ route('admin.trabajos.show', $trabajo) }}" 
                  class="bg-white/10 text-white px-4 py-2 rounded-lg hover:bg-white/20 transition-all flex items-center gap-2">
                   <i class="fas fa-images"></i>
                   Ver Evidencias
               </a>
           </div>
       </div>

       <div class="bg-white rounded-b-xl shadow-lg p-6">
           <form action="{{ route('admin.trabajos.update', $trabajo) }}" method="POST" class="space-y-6">
               @csrf
               @method('PUT')
               
               <div class="grid grid-cols-2 gap-6">
                   <div>
                       <label class="block text-sm font-medium text-gray-700">Tipo de Trabajo</label>
                       <input type="text" name="tipo_trabajo" value="{{ $trabajo->tipo_trabajo }}"
                              class="mt-1 w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-200" required>
                   </div>

                   <div>
                       <label class="block text-sm font-medium text-gray-700">Estado</label>
                       <select name="estado" class="mt-1 w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-200">
                           <option value="pendiente" {{ $trabajo->estado === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                           <option value="en_progreso" {{ $trabajo->estado === 'en_progreso' ? 'selected' : '' }}>En Progreso</option>
                           <option value="completado" {{ $trabajo->estado === 'completado' ? 'selected' : '' }}>Completado</option>
                       </select>
                   </div>

                   <div>
                       <label class="block text-sm font-medium text-gray-700">Fecha Inicio</label>
                       <input type="date" name="fecha_inicio" value="{{ $trabajo->fecha_inicio ? $trabajo->fecha_inicio->format('Y-m-d') : '' }}"
                              class="mt-1 w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-200">
                   </div>

                   <div>
                       <label class="block text-sm font-medium text-gray-700">Fecha Fin</label>
                       <input type="date" name="fecha_fin" value="{{ $trabajo->fecha_fin ? $trabajo->fecha_fin->format('Y-m-d') : '' }}"
                              class="mt-1 w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-200">
                   </div>

                   <div class="col-span-2">
                       <label class="block text-sm font-medium text-gray-700">Descripción</label>
                       <textarea name="descripcion" rows="3" 
                                class="mt-1 w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-200">{{ $trabajo->descripcion }}</textarea>
                   </div>

                   <div class="col-span-2">
                       <label class="block text-sm font-medium text-gray-700">Notas Internas</label>
                       <textarea name="notas_internas" rows="3" 
                                class="mt-1 w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-200">{{ $trabajo->notas_internas }}</textarea>
                   </div>
               </div>

               <div class="flex justify-end gap-4">
                   <a href="{{ route('admin.trabajos.index') }}" 
                      class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors inline-flex items-center gap-2">
                       <i class="fas fa-times"></i>
                       Cancelar
                   </a>
                   <button type="submit" 
                       class="px-4 py-2 text-white bg-emerald-500 rounded-lg hover:bg-emerald-600 transition-colors inline-flex items-center gap-2">
                       <i class="fas fa-save"></i>
                       Guardar Cambios
                   </button>
               </div>
           </form>
       </div>
   </div>
</div>
@endsection