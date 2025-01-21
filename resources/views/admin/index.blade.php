@extends('admin.layouts.app')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h1 class="text-3xl font-semibold text-gray-800">Gestión de Trabajadores</h1>
        <p class="text-sm text-gray-600 mt-2">Aquí puedes agregar, editar y eliminar trabajadores.</p>

        <div class="mt-4 mb-6">
            <a href="{{ route('admin.trabajadores.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Agregar Trabajador</a>
        </div>

        <table class="min-w-full bg-white shadow-md rounded-lg">
            <thead class="bg-indigo-600 text-white">
                <tr>
                    <th class="px-6 py-3 text-left">Nombre</th>
                    <th class="px-6 py-3 text-left">Email</th>
                    <th class="px-6 py-3 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($trabajadores as $trabajador)
                    <tr class="border-b hover:bg-gray-100">
                        <td class="px-6 py-3">{{ $trabajador->name }}</td>
                        <td class="px-6 py-3">{{ $trabajador->email }}</td>
                        <td class="px-6 py-3">
                            <a href="{{ route('admin.trabajadores.edit', $trabajador->id) }}" class="text-blue-600 hover:text-blue-800">Editar</a> |
                            <form action="{{ route('admin.trabajadores.destroy', $trabajador->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
