<div>
    {{-- 1. CABECERA DE LA PÁGINA --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-zinc-800 dark:text-zinc-200 leading-tight">
            {{ __('Historial de Ventas') }}
        </h2>
    </x-slot>

    {{-- 2. CONTENIDO --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-zinc-900 dark:text-zinc-100">

                    <!-- BARRA DE ACCIONES: Buscador -->
                    <div class="flex justify-between items-center mb-6">
                        <input 
                            wire:model.live.debounce.300ms="search"
                            type="text" 
                            placeholder="Buscar por N° Boleta, cliente, vehículo..." 
                            class="w-full sm:w-1/2 rounded-md shadow-sm border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100 focus:border-indigo-500 focus:ring-indigo-500"
                        >
                    </div>

                    <!-- TABLA DE VENTAS -->
                    <div class="overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                            <thead class="bg-zinc-50 dark:bg-zinc-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">Boleta / Fecha</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">Cliente</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">Vehículo</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">Monto</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">Estado</th>
                                    @role('Administrador')
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">Vendedor</th>
                                    @endrole
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Acciones</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-zinc-800 divide-y divide-zinc-200 dark:divide-zinc-700">
                                @forelse ($sales as $sale)
                                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $sale->numero_boleta }}</div>
                                            <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ \Carbon\Carbon::parse($sale->created_at)->format('d/m/Y h:ia') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $sale->customer->nombres_completos ?? 'N/A' }}</div>
                                            <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ $sale->customer->numero_documento ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $sale->vehicle->marca ?? 'N/A' }} {{ $sale->vehicle->modelo ?? '' }}</div>
                                            <div class="text-sm text-zinc-500 dark:text-zinc-400 font-mono">{{ $sale->vehicle->numero_chasis ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-zinc-900 dark:text-zinc-100">${{ number_format($sale->monto_total, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                // Determinamos el estado (si tiene traslado o no)
                                                $estado = $sale->shipment ? $sale->shipment->estado : 'Entregado (Local)';
                                                $estadoClasses = match($estado) {
                                                    'Pendiente' => 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200',
                                                    'En Tránsito' => 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200',
                                                    'Entregado' => 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200',
                                                    default => 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200',
                                                };
                                            @endphp
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $estadoClasses }}">
                                                {{ $estado }}
                                            </span>
                                        </td>
                                        @role('Administrador')
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">{{ $sale->user->name ?? 'N/A' }}</td>
                                        @endrole
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button 
                                                wire:click="openViewModal({{ $sale->id }})"
                                                class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300"
                                            >
                                                Ver Detalle
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ auth()->user()->hasRole('Administrador') ? '8' : '7' }}" class="px-6 py-12 text-center text-zinc-500 dark:text-zinc-400">
                                            No se encontraron ventas registradas.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- PAGINACIÓN -->
                    <div class="mt-6">
                        {{ $sales->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- ================================================== --}}
    {{-- MODAL DE VISTA DE DETALLE --}}
    {{-- ================================================== --}}
    @if($viewingSaleId)
    <x-crud-modal
        show="showViewModal"
        title="Detalle de Venta"
        subtitle="Boleta N° {{ $viewingSaleData['numero_boleta'] ?? '...' }}"
        submit-action="" {{-- Dejar vacío para ocultar el botón de guardar --}}
    >
        <x-slot name="icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-white">
              <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.75A.75.75 0 013 4.5h.75m0 0H21m-12 6h9m-9 3h9m-9 3h9M3.75 6a13.5 13.5 0 1013.5 13.5h4.062c.421 0 .613-.509.309-.766a11.95 11.95 0 00-9.255-8.991C10.72 10.313 10.5 10 10.188 10h-1.438c-.313 0-.53.313-.53.688v3.469a.75.75 0 00.75.75h4.062c.421 0 .613-.509.309-.766A11.95 11.95 0 0010.188 10h-1.438c-.313 0-.53.313-.53.688v3.469a.75.75 0 00.75.75h4.062" />
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
                        <span class="text-zinc-900 dark:text-zinc-100">{{ $viewingSaleData['customer']['nombres_completos'] ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="font-semibold text-zinc-700 dark:text-zinc-300">Documento:</span>
                        <span class="text-zinc-900 dark:text-zinc-100">{{ $viewingSaleData['customer']['tipo_documento'] ?? '' }} {{ $viewingSaleData['customer']['numero_documento'] ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="font-semibold text-zinc-700 dark:text-zinc-300">Teléfono:</span>
                        <span class="text-zinc-900 dark:text-zinc-100">{{ $viewingSaleData['customer']['telefono'] ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="font-semibold text-zinc-700 dark:text-zinc-300">Email:</span>
                        <span class="text-zinc-900 dark:text-zinc-100">{{ $viewingSaleData['customer']['correo_electronico'] ?? 'N/A' }}</span>
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
                        <span class="text-zinc-900 dark:text-zinc-100">{{ $viewingSaleData['vehicle']['marca'] ?? 'N/A' }} {{ $viewingSaleData['vehicle']['modelo'] ?? '' }}</span>
                    </div>
                    <div>
                        <span class="font-semibold text-zinc-700 dark:text-zinc-300">Año:</span>
                        <span class="text-zinc-900 dark:text-zinc-100">{{ $viewingSaleData['vehicle']['anio_fabricacion'] ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="font-semibold text-zinc-700 dark:text-zinc-300">N° Chasis:</span>
                        <span class="text-zinc-900 dark:text-zinc-100 font-mono">{{ $viewingSaleData['vehicle']['numero_chasis'] ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="font-semibold text-zinc-700 dark:text-zinc-300">N° Motor:</span>
                        <span class="text-zinc-900 dark:text-zinc-100 font-mono">{{ $viewingSaleData['vehicle']['numero_motor'] ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <div class="border-t border-zinc-200 dark:border-zinc-700"></div>

            {{-- SECCIÓN: Venta --}}
            <div>
                <h4 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-indigo-600"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.768 0-1.536.219-2.121.659L9 15.182z" /></svg>
                    Datos de la Venta
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="font-semibold text-zinc-700 dark:text-zinc-300">Monto Total:</span>
                        <span class="text-zinc-900 dark:text-zinc-100 font-bold">${{ number_format($viewingSaleData['monto_total'] ?? 0, 2) }}</span>
                    </div>
                    <div>
                        <span class="font-semibold text-zinc-700 dark:text-zinc-300">Método de Pago:</span>
                        <span class="text-zinc-900 dark:text-zinc-100">{{ $viewingSaleData['metodo_pago'] ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="font-semibold text-zinc-700 dark:text-zinc-300">Vendedor:</span>
                        <span class="text-zinc-900 dark:text-zinc-100">{{ $viewingSaleData['user']['name'] ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="font-semibold text-zinc-700 dark:text-zinc-300">Fecha de Venta:</span>
                        <span class="text-zinc-900 dark:text-zinc-100">{{ \Carbon\Carbon::parse($viewingSaleData['created_at'])->format('d/m/Y h:ia') }}</span>
                    </div>
                    <div class="md:col-span-2">
                        <span class="font-semibold text-zinc-700 dark:text-zinc-300">Observaciones:</span>
                        <span class="text-zinc-900 dark:text-zinc-100">{{ $viewingSaleData['observaciones'] ?: 'Ninguna' }}</span>
                    </div>
                </div>
            </div>

            {{-- SECCIÓN: Traslado (Condicional) --}}
            @if($viewingSaleData['shipment'])
            <div class="border-t border-zinc-200 dark:border-zinc-700"></div>
            <div>
                <h4 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-indigo-600"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25" /></svg>
                    Datos del Traslado
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="font-semibold text-zinc-700 dark:text-zinc-300">Estado:</span>
                        <span class="text-zinc-900 dark:text-zinc-100">{{ $viewingSaleData['shipment']['estado'] }}</span>
                    </div>
                    <div>
                        <span class="font-semibold text-zinc-700 dark:text-zinc-300">Ciudad Destino:</span>
                        <span class="text-zinc-900 dark:text-zinc-100">{{ $viewingSaleData['shipment']['ciudad_destino'] }}</span>
                    </div>
                    <div class="md:col-span-2">
                        <span class="font-semibold text-zinc-700 dark:text-zinc-300">Dirección de Entrega:</span>
                        <span class="text-zinc-900 dark:text-zinc-100">{{ $viewingSaleData['shipment']['direccion_entrega_completa'] }}</span>
                    </div>
                    <div>
                        <span class="font-semibold text-zinc-700 dark:text-zinc-300">Fecha Salida:</span>
                        <span class="text-zinc-900 dark:text-zinc-100">{{ $viewingSaleData['shipment']['fecha_salida'] ? \Carbon\Carbon::parse($viewingSaleData['shipment']['fecha_salida'])->format('d/m/Y h:ia') : 'Pendiente' }}</span>
                    </div>
                    <div>
                        <span class="font-semibold text-zinc-700 dark:text-zinc-300">Fecha Entrega:</span>
                        <span class="text-zinc-900 dark:text-zinc-100">{{ $viewingSaleData['shipment']['fecha_entrega'] ? \Carbon\Carbon::parse($viewingSaleData['shipment']['fecha_entrega'])->format('d/m/Y h:ia') : 'Pendiente' }}</span>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </x-crud-modal>
    @endif

</div>