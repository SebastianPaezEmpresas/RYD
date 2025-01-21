<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | RYD Jardinería</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-indigo-600 text-white min-h-screen">
            <div class="p-4 text-center">
                <h2 class="text-xl font-bold">RYD Jardinería</h2>
            </div>
            <nav class="mt-4">
                <a href="{{ route('admin.dashboard') }}" class="block py-2 px-4 hover:bg-indigo-700">Dashboard</a>
                <a href="{{ route('admin.trabajadores.index') }}" class="block py-2 px-4 hover:bg-indigo-700">Trabajadores</a>
                <a href="{{ route('admin.trabajos.index') }}" class="block py-2 px-4 hover:bg-indigo-700">Trabajos</a>
                <a href="{{ route('admin.encuestas.index') }}" class="block py-2 px-4 hover:bg-indigo-700">Encuestas</a>
                <a href="{{ route('logout') }}" class="block py-2 px-4 hover:bg-indigo-700"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Cerrar Sesión</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>
</body>
</html>
