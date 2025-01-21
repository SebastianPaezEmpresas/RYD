@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Editar Trabajador</h1>
        <a href="{{ route('admin.trabajadores.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
            Volver
        </a>
    </div>

    @if($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
            <p class="font-bold">Por favor corrige los siguientes errores:</p>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.trabajadores.update', $trabajador->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre *</label>
                    <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $trabajador->nombre) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                </div>

                <div>
                    <label for="apellido" class="block text-sm font-medium text-gray-700">Apellido *</label>
                    <input type="text" name="apellido" id="apellido" value="{{ old('apellido', $trabajador->apellido) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico *</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $trabajador->email) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                </div>

                <div>
                    <label for="telefono" class="block text-sm font-medium text-gray-700">Teléfono</label>
                    <input type="text" name="telefono" id="telefono" value="{{ old('telefono', $trabajador->telefono) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>

                <div class="md:col-span-2">
                    <label for="direccion" class="block text-sm font-medium text-gray-700">Dirección</label>
                    <input type="text" name="direccion" id="direccion" value="{{ old('direccion', $trabajador->direccion) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>

                <div>
                    <label for="fecha_contratacion" class="block text-sm font-medium text-gray-700">Fecha de Contratación *</label>
                    <input type="date" name="fecha_contratacion" id="fecha_contratacion" value="{{ old('fecha_contratacion', $trabajador->fecha_contratacion->format('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                </div>

                <div>
                    <label for="salario" class="block text-sm font-medium text-gray-700">Salario</label>
                    <input type="number" step="0.01" name="salario" id="salario" value="{{ old('salario', $trabajador->salario) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>

                <div>
                    <label for="cargo" class="block text-sm font-medium text-gray-700">Cargo</label>
                    <input type="text" name="cargo" id="cargo" value="{{ old('cargo', $trabajador->cargo) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>

                <div>
                    <label for="estado" class="block text-sm font-medium text-gray-700">Estado</label>
                    <select name="estado" id="estado" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="activo" {{ old('estado', $trabajador->estado) === 'activo' ? 'selected' : '' }}>Activo</option>
                        <option value="inactivo" {{ old('estado', $trabajador->estado) === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label for="observaciones" class="block text-sm font-medium text-gray-700">Observaciones</label>
                    <textarea name="observaciones" id="observaciones" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('observaciones', $trabajador->observaciones) }}</textarea>
                </div>
            </div>

            <div class="flex justify-end gap-4 pt-4">
                <a href="{{ route('admin.trabajadores.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-500">
                    Cancelar
                </a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Actualizar Trabajador
                </button>
            </div>
        </form>
    </div>
</div>
@endsection