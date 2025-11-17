<div>
    {{-- 1. CABECERA DE LA PÁGINA --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-zinc-800 dark:text-zinc-200 leading-tight">
            {{ __('Gestión de Traslados') }}
        </h2>
    </x-slot>

    {{-- 2. CONTENIDO --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- MENSAJE DE ÉXITO -->
            @if (session()->has('success'))
                <div class="mb-6 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-200 px-4 py-3 rounded-md relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-zinc-900 dark:text-zinc-100">

                    <!-- BARRA DE ACCIONES: Filtros -->
                    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
                        <input 
                            wire:model.live.debounce.300ms="search"
                            type="text" 
                            placeholder="Buscar por N° Boleta, cliente..." 
                            class="w-full sm:w-1/2 rounded-md shadow-sm border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100 focus:border-indigo-500 focus:ring-indigo-500"
                        >
                        <select 
                            wire:model.live="filter_estado" 
                            class="w-full sm:w-auto rounded-md shadow-sm border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50"
                        >
                            <option value="">Todos los Estados</option>
                            <option value="Pendiente">Pendiente</option>
                            <option value="En Tránsito">En Tránsito</option>
                            <option value="Entregado">Entregado</option>
                        </select>
                    </div>

                    <!-- TABLA DE TRASLADOS -->
                    <div class="overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                            <thead class="bg-zinc-50 dark:bg-zinc-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">Boleta / Cliente</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">Vehículo</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">Destino</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">Fechas</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">Estado</th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Acciones</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-zinc-800 divide-y divide-zinc-200 dark:divide-zinc-700">
                                @forelse ($shipments as $shipment)
                                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $shipment->sale->numero_boleta ?? 'N/A' }}</div>
                                            <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ $shipment->sale->customer->nombres_completos ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $shipment->sale->vehicle->marca ?? 'N/A' }} {{ $shipment->sale->vehicle->modelo ?? '' }}</div>
                                            <div class="text-sm text-zinc-500 dark:text-zinc-400 font-mono">{{ $shipment->sale->vehicle->numero_chasis ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $shipment->ciudad_destino }}</div>
                                            <div class="text-sm text-zinc-500 dark:text-zinc-400 truncate max-w-xs">{{ $shipment->direccion_entrega_completa }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                                            <div>Salida: {{ $shipment->fecha_salida ? \Carbon\Carbon::parse($shipment->fecha_salida)->format('d/m/Y') : '---' }}</div>
                                            <div>Entrega: {{ $shipment->fecha_entrega ? \Carbon\Carbon::parse($shipment->fecha_entrega)->format('d/m/Y') : '---' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $estadoClasses = match($shipment->estado) {
                                                    'Pendiente' => 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200',
                                                    'En Tránsito' => 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200',
                                                    'Entregado' => 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200',
                                                    default => 'bg-zinc-100 dark:bg-zinc-900 text-zinc-800 dark:text-zinc-200',
                                                };
                                            @endphp
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $estadoClasses }}">
                                                {{ $shipment->estado }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                            <button 
                                                wire:click="openViewModal({{ $shipment->id }})"
                                                class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300"
                                            >
                                                Ver
                                            </button>
                                            
                                            @role('Encargado de Traslado')
                                                @if($shipment->estado != 'Entregado')
                                                    <button 
                                                        wire:click="openUpdateModal({{ $shipment->id }}, '{{ $shipment->estado }}')"
                                                        class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300"
                                                    >
                                                        Actualizar
                                                    </button>
                                                @else
                                                    <span class="text-zinc-400 dark:text-zinc-600 cursor-not-allowed">Actualizar</span>
                                                @endif
                                            @endrole
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-12 text-center text-zinc-500 dark:text-zinc-400">
                                            No se encontraron traslados.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- PAGINACIÓN -->
                    <div class="mt-6">
                        {{ $shipments->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- ================================================== --}}
    {{-- MODAL DE VISTA DE DETALLE (RF-32) --}}
    {{-- ================================================== --}}
    @if($viewingShipmentId)
    <x-crud-modal
        show="showViewModal"
        title="Detalle de Traslado"
        subtitle="Boleta N° {{ $viewingShipmentData['sale']['numero_boleta'] ?? '...' }}"
        submit-action="" {{-- Sin acción de submit --}}
    >
        <x-slot name="icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-white">
              <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25" />
            </svg>
        </x-slot>

        {{-- Cuerpo del Modal (Solo Vista) --}}
        <div class="space-y-6">

            {{-- SECCIÓN: Cliente --}}
            <div>
                <h4 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-indigo-600"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                    Datos del Cliente
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="font-semibold text-zinc-700 dark:text-zinc-300">Nombre:</span>
                        <span class="text-zinc-900 dark:text-zinc-100">{{ $viewingShipmentData['sale']['customer']['nombres_completos'] ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="font-semibold text-zinc-700 dark:text-zinc-300">Documento:</span>
                        <span class="text-zinc-900 dark:text-zinc-100">{{ $viewingShipmentData['sale']['customer']['numero_documento'] ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="font-semibold text-zinc-700 dark:text-zinc-300">Teléfono:</span>
                        <span class="text-zinc-900 dark:text-zinc-100">{{ $viewingShipmentData['sale']['customer']['telefono'] ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="font-semibold text-zinc-700 dark:text-zinc-300">Vendedor:</span>
                        <span class="text-zinc-900 dark:text-zinc-100">{{ $viewingShipmentData['sale']['user']['name'] ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <div class="border-t border-zinc-200 dark:border-zinc-700"></div>

            {{-- SECCIÓN: Vehículo --}}
            <div>
                <h4 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-indigo-600"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" /></svg>
                    Datos del Vehículo
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="font-semibold text-zinc-700 dark:text-zinc-300">Vehículo:</span>
                        <span class="text-zinc-900 dark:text-zinc-100">{{ $viewingShipmentData['sale']['vehicle']['marca'] ?? 'N/A' }} {{ $viewingShipmentData['sale']['vehicle']['modelo'] ?? '' }}</span>
                    </div>
                    <div>
                        <span class="font-semibold text-zinc-700 dark:text-zinc-300">N° Chasis:</span>
                        <span class="text-zinc-900 dark:text-zinc-100 font-mono">{{ $viewingShipmentData['sale']['vehicle']['numero_chasis'] ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <div class="border-t border-zinc-200 dark:border-zinc-700"></div>

            {{-- SECCIÓN: Traslado --}}
            <div>
                <h4 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-indigo-600"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25" /></svg>
                    Datos del Traslado
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="font-semibold text-zinc-700 dark:text-zinc-300">Estado:</span>
                        <span class="text-zinc-900 dark:text-zinc-100">{{ $viewingShipmentData['estado'] }}</span>
                    </div>
                    <div>
                        <span class="font-semibold text-zinc-700 dark:text-zinc-300">Ciudad Destino:</span>
                        <span class="text-zinc-900 dark:text-zinc-100">{{ $viewingShipmentData['ciudad_destino'] }}</span>
                    </div>
                    <div class="md:col-span-2">
                        <span class="font-semibold text-zinc-700 dark:text-zinc-300">Dirección de Entrega:</span>
                        <span class="text-zinc-900 dark:text-zinc-100">{{ $viewingShipmentData['direccion_entrega_completa'] }}</span>
                    </div>
                    <div>
                        <span class="font-semibold text-zinc-700 dark:text-zinc-300">Fecha Salida:</span>
                        <span class="text-zinc-900 dark:text-zinc-100">{{ $viewingShipmentData['fecha_salida'] ? \Carbon\Carbon::parse($viewingShipmentData['fecha_salida'])->format('d/m/Y h:ia') : 'Pendiente' }}</span>
                    </div>
                    <div>
                        <span class="font-semibold text-zinc-700 dark:text-zinc-300">Fecha Entrega:</span>
                        <span class="text-zinc-900 dark:text-zinc-100">{{ $viewingShipmentData['fecha_entrega'] ? \Carbon\Carbon::parse($viewingShipmentData['fecha_entrega'])->format('d/m/Y h:ia') : 'Pendiente' }}</span>
                    </div>
                    <div class="md:col-span-2">
                        <span class="font-semibold text-zinc-700 dark:text-zinc-300">Observaciones:</span>
                        <pre class="text-zinc-900 dark:text-zinc-100 font-sans whitespace-pre-wrap">{{ $viewingShipmentData['observaciones'] ?: 'Ninguna' }}</pre>
                    </div>
                </div>
            </div>

        </div>
    </x-crud-modal>
    @endif
    
    {{-- ================================================== --}}
    {{-- MODAL DE ACTUALIZACIÓN DE ESTADO (RF-30) --}}
    {{-- ================================================== --}}
    @if($updatingShipmentId)
    <x-crud-modal
        show="showUpdateModal"
        title="Actualizar Estado del Traslado"
        subtitle="Boleta N° {{ $viewingShipmentData['sale']['numero_boleta'] ?? '...' }}"
        submit-action="updateShipmentStatus"
        submit-text="Confirmar Actualización"
    >
        <x-slot name="icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-white">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
            </svg>
        </x-slot>

        {{-- Cuerpo del Modal (Confirmación) --}}
        <div class="space-y-6">
            <p class="text-lg text-center text-zinc-800 dark:text-zinc-200">
                ¿Desea confirmar la actualización del estado de:
            </p>
            
            <div class="flex justify-center items-center gap-4">
                <span class="px-3 py-1 text-md font-semibold rounded-full {{ match($currentStatus) {
                    'Pendiente' => 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200',
                    'En Tránsito' => 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200',
                    default => 'bg-zinc-100 dark:bg-zinc-900 text-zinc-800 dark:text-zinc-200',
                } }}">
                    {{ $currentStatus }}
                </span>
                
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8 text-zinc-500">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
                
                <span class="px-3 py-1 text-md font-semibold rounded-full {{ match($nextStatus) {
                    'En Tránsito' => 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200',
                    'Entregado' => 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200',
                    default => 'bg-zinc-100 dark:bg-zinc-900 text-zinc-800 dark:text-zinc-200',
                } }}">
                    {{ $nextStatus }}
                </span>
            </div>

            <div>
                <label for="update_observaciones" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                    Observaciones / Confirmación (Opcional)
                </label>
                <textarea 
                    id="update_observaciones" 
                    wire:model="update_observaciones" 
                    rows="3" 
                    placeholder="Ej: Salió del almacén a las 10:00 AM / Entregado conforme por [Nombre]"
                    class="w-full rounded-lg shadow-sm border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 transition-colors resize-none"
                ></textarea>
                @error('update_observaciones') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

        </div>
    </x-crud-modal>
    @endif

</div>