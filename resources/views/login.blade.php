<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | RYD Jardinería</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-sm">
        <div class="text-center mb-6">
            <img src="/images/logo.png" alt="Logo RYD Jardinería" class="w-24 mx-auto">
            <h1 class="text-2xl font-bold text-gray-800 mt-4">Iniciar Sesión</h1>
        </div>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                <input type="email" id="email" name="email" class="mt-1 w-full px-4 py-2 border rounded-md focus:ring focus:ring-indigo-300 focus:outline-none" placeholder="Ingresa tu correo" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                <input type="password" id="password" name="password" class="mt-1 w-full px-4 py-2 border rounded-md focus:ring focus:ring-indigo-300 focus:outline-none" placeholder="Ingresa tu contraseña" required>
            </div>
            <div class="flex items-center justify-between mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" class="form-checkbox h-5 w-5 text-indigo-600">
                    <span class="ml-2 text-gray-700 text-sm">Recordarme</span>
                </label>
                <a href="#" class="text-sm text-indigo-600 hover:underline">¿Olvidaste tu contraseña?</a>
            </div>
            <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 transition duration-200">
                Iniciar Sesión
            </button>
        </form>
    </div>
</body>
</html>
