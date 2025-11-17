<div>
    {{-- 1. CABECERA DE LA PÁGINA --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-zinc-800 dark:text-zinc-200 leading-tight">
            {{ __('Registrar Nueva Venta') }}
        </h2>
    </x-slot>

    {{-- 2. CONTENIDO --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- MENSAJE DE ERROR (SI LA TRANSACCIÓN FALLA) -->
            @if (session()->has('error'))
                <div class="mb-6 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-200 px-4 py-3 rounded-md relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            {{-- Usamos wire:submit en el div exterior ya que no es un modal --}}
            <form wire:submit="saveSale" class="space-y-8">

                <!-- PASO 1: SELECCIÓN DE CLIENTE -->
                <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-zinc-200 dark:border-zinc-700 bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-zinc-900 dark:to-zinc-800">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-indigo-600 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-zinc-900 dark:text-zinc-100">Paso 1: Seleccionar Cliente</h3>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400">Busque y seleccione el cliente que realiza la compra.</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <input 
                            wire:model.live.debounce.300ms="searchCustomer"
                            type="text" 
                            placeholder="Buscar cliente por nombre o documento..." 
                            class="w-full mb-4 rounded-lg shadow-sm border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50"
                        >
                        <select 
                            wire:model="customer_id" 
                            class="w-full rounded-lg shadow-sm border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50"
                        >
                            <option value="">Seleccione un cliente...</option>
                            @foreach($this->customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->nombres_completos }} ({{ $customer->numero_documento }})</option>
                            @endforeach
                        </select>
                        @error('customer_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- PASO 2: SELECCIÓN DE VEHÍCULO -->
                <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-zinc-200 dark:border-zinc-700 bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-zinc-900 dark:to-zinc-800">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-indigo-600 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-zinc-900 dark:text-zinc-100">Paso 2: Seleccionar Vehículo</h3>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400">Busque y seleccione un vehículo (solo aparecen los "Disponibles").</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <input 
                            wire:model.live.debounce.300ms="searchVehicle"
                            type="text" 
                            placeholder="Buscar vehículo por marca, modelo o chasis..." 
                            class="w-full mb-4 rounded-lg shadow-sm border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50"
                        >
                        <select 
                            wire:model.live="vehicle_id" 
                            class="w-full rounded-lg shadow-sm border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50"
                        >
                            <option value="">Seleccione un vehículo...</option>
                            @foreach($this->vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}">{{ $vehicle->marca }} {{ $vehicle->modelo }} ({{ $vehicle->anio_fabricacion }}) - ${{ number_format($vehicle->precio_venta, 2) }}</option>
                            @endforeach
                        </select>
                        @error('vehicle_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- PASO 3: DATOS DE VENTA Y TRASLADO -->
                <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-zinc-200 dark:border-zinc-700 bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-zinc-900 dark:to-zinc-800">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-indigo-600 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.75A.75.75 0 013 4.5h.75m0 0H21m-12 6h9m-9 3h9m-9 3h9M3.75 6a13.5 13.5 0 1013.5 13.5h4.062c.421 0 .613-.509.309-.766a11.95 11.95 0 00-9.255-8.991C10.72 10.313 10.5 10 10.188 10h-1.438c-.313 0-.53.313-.53.688v3.469a.75.75 0 00.75.75h4.062c.421 0 .613-.509.309-.766A11.95 11.95 0 0010.188 10h-1.438c-.313 0-.53.313-.53.688v3.469a.75.75 0 00.75.75h4.062" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-zinc-900 dark:text-zinc-100">Paso 3: Detalles de Pago y Traslado</h3>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400">Confirme el monto y si la venta requiere traslado.</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 space-y-6">
                        {{-- Detalles de Pago --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="monto_total" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                                    Monto Total de Venta ($) <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-zinc-500 dark:text-zinc-400 font-semibold">$</span>
                                    </div>
                                    <input 
                                        type="number" 
                                        step="0.01" 
                                        id="monto_total" 
                                        wire:model="monto_total" 
                                        placeholder="0.00"
                                        class="w-full pl-8 rounded-lg shadow-sm border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 transition-colors"
                                    >
                                </div>
                                @error('monto_total') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="metodo_pago" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                                    Método de Pago <span class="text-red-500">*</span>
                                </label>
                                <select 
                                    id="metodo_pago" 
                                    wire:model="metodo_pago" 
                                    class="w-full rounded-lg shadow-sm border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 transition-colors"
                                >
                                    <option value="Contado">Contado</option>
                                    <option value="Financiado">Financiado</option>
                                </select>
                                @error('metodo_pago') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label for="observaciones" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                                    Observaciones (Opcional)
                                </label>
                                <textarea 
                                    id="observaciones" 
                                    wire:model="observaciones" 
                                    rows="3" 
                                    placeholder="Detalles adicionales de la venta..."
                                    class="w-full rounded-lg shadow-sm border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 transition-colors resize-none"
                                ></textarea>
                                @error('observaciones') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="border-t border-zinc-200 dark:border-zinc-700"></div>

                        {{-- Checkbox de Traslado (RF-26) --}}
                        <div class="relative flex items-start">
                            <div class="flex h-6 items-center">
                                <input 
                                    id="requires_shipment" 
                                    wire:model.live="requires_shipment" 
                                    type="checkbox" 
                                    class="h-4 w-4 rounded border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 text-indigo-600 focus:ring-indigo-600"
                                >
                            </div>
                            <div class="ml-3 text-sm leading-6">
                                <label for="requires_shipment" class="font-medium text-zinc-900 dark:text-zinc-100">
                                    ¿La venta es fuera de Arequipa y requiere traslado?
                                </label>
                                <p class="text-zinc-500 dark:text-zinc-400">Marque esta casilla para registrar los datos de envío.</p>
                            </div>
                        </div>

                        {{-- Campos de Traslado (Condicionales) --}}
                        @if($requires_shipment)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 p-5 bg-zinc-50 dark:bg-zinc-900 rounded-lg border border-indigo-200 dark:border-indigo-800 animate-fade-in">
                            <h4 class="md:col-span-2 text-md font-semibold text-indigo-800 dark:text-indigo-200 mb-2">
                                Datos del Traslado (RF-27)
                            </h4>
                            
                            <div class="md:col-span-2">
                                <label for="direccion_entrega_completa" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                                    Dirección de Entrega Completa <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="direccion_entrega_completa" 
                                    wire:model="direccion_entrega_completa" 
                                    placeholder="Av. Principal 456, Urb. La Esperanza"
                                    class="w-full rounded-lg shadow-sm border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50"
                                >
                                @error('direccion_entrega_completa') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label for="ciudad_destino" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                                    Ciudad de Destino <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="ciudad_destino" 
                                    wire:model="ciudad_destino" 
                                    placeholder="Ej: Lima, Cusco, Puno"
                                    class="w-full rounded-lg shadow-sm border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50"
                                >
                                @error('ciudad_destino') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="fecha_estimada_entrega" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                                    Fecha Estimada de Entrega (Opcional)
                                </label>
                                <input 
                                    type="date" 
                                    id="fecha_estimada_entrega" 
                                    wire:model="fecha_estimada_entrega" 
                                    class="w-full rounded-lg shadow-sm border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50"
                                >
                                @error('fecha_estimada_entrega') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- BOTÓN FINAL DE GUARDAR -->
                <div class="flex justify-end">
                    <button 
                        type="submit" 
                        class="px-8 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 dark:from-green-500 dark:to-emerald-500 dark:hover:from-green-600 dark:hover:to-emerald-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                        wire:loading.attr="disabled"
                    >
                        <span wire:loading.remove wire:target="saveSale" class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Confirmar y Registrar Venta
                        </span>
                        <span wire:loading wire:target="saveSale" class="flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Procesando Venta...
                        </span>
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- Añadimos un pequeño CSS para la animación de fade-in --}}
@push('styles')
<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.3s ease-out;
    }
</style>
@endpush
</div>

