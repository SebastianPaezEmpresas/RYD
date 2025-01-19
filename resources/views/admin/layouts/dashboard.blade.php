@extends('admin.layouts.app')

@section('content')
<div class="bg-white shadow-md rounded-lg p-6">
    <h1 class="text-2xl font-bold text-gray-800">Bienvenido al Dashboard</h1>
    <p class="mt-4 text-gray-600">Aquí puedes gestionar las operaciones de RYD Jardinería.</p>
    <div class="mt-6 grid grid-cols-3 gap-4">
        <div class="p-4 bg-indigo-100 rounded-lg">
            <h2 class="text-lg font-bold">Trabajadores</h2>
            <p class="text-sm text-gray-600">Gestiona los trabajadores de la empresa.</p>
        </div>
        <div class="p-4 bg-indigo-100 rounded-lg">
            <h2 class="text-lg font-bold">Trabajos</h2>
            <p class="text-sm text-gray-600">Administra los trabajos asignados.</p>
        </div>
        <div class="p-4 bg-indigo-100 rounded-lg">
            <h2 class="text-lg font-bold">Encuestas</h2>
            <p class="text-sm text-gray-600">Revisa las encuestas de satisfacción.</p>
        </div>
    </div>
</div>
@endsection
