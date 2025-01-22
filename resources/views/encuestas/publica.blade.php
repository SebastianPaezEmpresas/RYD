<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encuesta de Satisfacción - RYD Jardinería</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col items-center pt-12 px-4">
        <div class="w-full max-w-2xl">
            <!-- Logo y Título -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Encuesta de Satisfacción</h1>
                <p class="text-gray-600 mt-2">Servicio: {{ $encuesta->trabajo->titulo }}</p>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <form action="{{ route('encuesta.responder', $encuesta->token) }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Calificación General -->
                    <div>
                        <label class="block text-lg font-medium text-gray-700 mb-3">
                            Calificación General del Servicio
                        </label>
                        <div class="flex space-x-4">
                            @for ($i = 1; $i <= 5; $i++)
                            <label class="flex flex-col items-center">
                                <input type="radio" name="calificacion_general" value="{{ $i }}" class="sr-only peer" required>
                                <div class="w-12 h-12 rounded-full flex items-center justify-center border-2 peer-checked:bg-green-500 peer-checked:text-white cursor-pointer hover:bg-green-100">
                                    {{ $i }}
                                </div>
                            </label>
                            @endfor
                        </div>
                    </div>

                    <!-- Puntualidad -->
                    <div>
                        <label class="block text-lg font-medium text-gray-700 mb-3">
                            Puntualidad
                        </label>
                        <div class="flex space-x-4">
                            @for ($i = 1; $i <= 5; $i++)
                            <label class="flex flex-col items-center">
                                <input type="radio" name="puntualidad" value="{{ $i }}" class="sr-only peer" required>
                                <div class="w-12 h-12 rounded-full flex items-center justify-center border-2 peer-checked:bg-green-500 peer-checked:text-white cursor-pointer hover:bg-green-100">
                                    {{ $i }}
                                </div>
                            </label>
                            @endfor
                        </div>
                    </div>

                    <!-- Calidad del Trabajo -->
                    <div>
                        <label class="block text-lg font-medium text-gray-700 mb-3">
                            Calidad del Trabajo
                        </label>
                        <div class="flex space-x-4">
                            @for ($i = 1; $i <= 5; $i++)
                            <label class="flex flex-col items-center">
                                <input type="radio" name="calidad_trabajo" value="{{ $i }}" class="sr-only peer" required>
                                <div class="w-12 h-12 rounded-full flex items-center justify-center border-2 peer-checked:bg-green-500 peer-checked:text-white cursor-pointer hover:bg-green-100">
                                    {{ $i }}
                                </div>
                            </label>
                            @endfor
                        </div>
                    </div>

                    <!-- Profesionalismo -->
                    <div>
                        <label class="block text-lg font-medium text-gray-700 mb-3">
                            Profesionalismo del Personal
                        </label>
                        <div class="flex space-x-4">
                            @for ($i = 1; $i <= 5; $i++)
                            <label class="flex flex-col items-center">
                                <input type="radio" name="profesionalismo" value="{{ $i }}" class="sr-only peer" required>
                                <div class="w-12 h-12 rounded-full flex items-center justify-center border-2 peer-checked:bg-green-500 peer-checked:text-white cursor-pointer hover:bg-green-100">
                                    {{ $i }}
                                </div>
                            </label>
                            @endfor
                        </div>
                    </div>

                    <!-- Comentarios -->
                    <div>
                        <label class="block text-lg font-medium text-gray-700 mb-2">
                            Comentarios Adicionales
                        </label>
                        <textarea name="comentarios" rows="4" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200"
                            placeholder="Por favor, comparta sus comentarios o sugerencias..."></textarea>
                    </div>

                    <!-- Submit -->
                    <div class="text-center">
                        <button type="submit" class="bg-green-600 text-white px-8 py-3 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                            Enviar Evaluación
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>