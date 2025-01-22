<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trabajo</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email Cliente</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Trabajo</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Última Actualización</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($encuestas as $encuesta)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $encuesta->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-indigo-600 hover:text-indigo-900">
                                @if($encuesta->trabajo)
                                    <a href="{{ route('admin.trabajos.show', $encuesta->trabajo_id) }}">
                                        {{ $encuesta->trabajo->titulo ?? 'Sin título' }}
                                    </a>
                                @else
                                    <span class="text-gray-500">Trabajo no disponible</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $encuesta->trabajo?->cliente_email ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $encuesta->trabajo?->fecha_realizacion ? $encuesta->trabajo->fecha_realizacion->format('d/m/Y') : 'No disponible' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @switch($encuesta->estado)
                                    @case('pendiente')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pendiente
                                        </span>
                                        @break
                                    @case('enviada')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Enviada
                                        </span>
                                        @break
                                    @case('respondida')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Respondida
                                        </span>
                                        @break
                                @endswitch
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $encuesta->updated_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('admin.encuestas.show', $encuesta->id) }}" 
                                       class="text-indigo-600 hover:text-indigo-900"
                                       title="Ver detalle">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($encuesta->estado != 'respondida')
                                        <button type="button" 
                                                class="text-blue-600 hover:text-blue-900 enviar-encuesta" 
                                                data-id="{{ $encuesta->id }}"
                                                title="Reenviar encuesta">
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    @endif
                                    <button type="button" 
                                            class="text-red-600 hover:text-red-900 eliminar-encuesta" 
                                            data-id="{{ $encuesta->id }}"
                                            title="Eliminar encuesta">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                No hay encuestas registradas
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Paginación -->
        <div class="mt-4">
            {{ $encuestas->links() }}
        </div>
    </div>
</div>