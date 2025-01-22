<!-- Filtros -->
<div class="bg-white shadow rounded-lg mb-6">
    <div class="px-4 py-5 sm:p-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Filtros de Búsqueda</h3>
        <form action="{{ route('admin.encuestas.index') }}" method="GET" id="filtrosForm" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="estado" class="block text-sm font-medium text-gray-700">Estado</label>
                    <select name="estado" id="estado" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="">Todos</option>
                        <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="enviada" {{ request('estado') == 'enviada' ? 'selected' : '' }}>Enviada</option>
                        <option value="respondida" {{ request('estado') == 'respondida' ? 'selected' : '' }}>Respondida</option>
                    </select>
                </div>
                <div>
                    <label for="fecha_desde" class="block text-sm font-medium text-gray-700">Fecha Desde</label>
                    <input type="date" name="fecha_desde" id="fecha_desde" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" value="{{ request('fecha_desde') }}">
                </div>
                <div>
                    <label for="fecha_hasta" class="block text-sm font-medium text-gray-700">Fecha Hasta</label>
                    <input type="date" name="fecha_hasta" id="fecha_hasta" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" value="{{ request('fecha_hasta') }}">
                </div>
                <div>
                    <label for="cliente" class="block text-sm font-medium text-gray-700">Cliente</label>
                    <input type="text" name="cliente" id="cliente" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="Nombre del cliente..." value="{{ request('cliente') }}">
                </div>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="reset" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <i class="fas fa-undo mr-2"></i>
                    Limpiar
                </button>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                    <i class="fas fa-search mr-2"></i>
                    Buscar
                </button>
            </div>
        </form>
    </div>
</div>