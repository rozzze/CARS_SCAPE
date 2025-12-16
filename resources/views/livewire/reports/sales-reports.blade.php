<div class="p-6 space-y-6">
    <div class="flex flex-col gap-2">
        <h2 class="text-2xl font-bold text-slate-800">Generación de Reportes de Ventas</h2>
        <p class="text-slate-500">Utiliza los filtros para personalizar tu reporte y descárgalo en formato PDF.</p>
    </div>

    <!-- Filters Card -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- Date Range -->
            <div class="flex flex-col gap-2">
                <label class="block text-sm font-medium text-slate-700">Fecha Inicio</label>
                <input 
                    type="date" 
                    wire:model.live="start_date" 
                    class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2 px-3"
                >
            </div>

            <div class="flex flex-col gap-2">
                <label class="block text-sm font-medium text-slate-700">Fecha Fin</label>
                <input 
                    type="date" 
                    wire:model.live="end_date" 
                    class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2 px-3"
                >
            </div>
            
            <!-- Search Text -->
            <div class="flex flex-col gap-2 lg:col-span-2">
                <label class="block text-sm font-medium text-slate-700">Buscar Cliente, Documento o Boleta</label>
                <div class="relative rounded-md shadow-sm">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5 text-gray-400">
                            <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search" 
                        class="block w-full rounded-lg border-slate-300 pl-10 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2" 
                        placeholder="Ej: Juan Perez, 12345678, BOL-0001"
                    >
                </div>
            </div>

            <!-- Customer Filter (Optional Select) -->
             <div class="flex flex-col gap-2">
                <label class="block text-sm font-medium text-slate-700">Cliente (Selección directa)</label>
                <select 
                    wire:model.live="customer_id" 
                    class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2 px-3"
                >
                    <option value="">Todos los clientes</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->nombres_completos }} ({{ $customer->numero_documento }})</option>
                    @endforeach
                </select>
            </div>

            <!-- Vendedor Filter (Secondary) -->
            <div class="flex flex-col gap-2">
                <label class="block text-sm font-medium text-slate-700">Vendedor</label>
                <select 
                    wire:model.live="user_id" 
                    class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2 px-3"
                >
                    <option value="">Todos los vendedores</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

        </div>

        <div class="mt-6 flex justify-end">
            <button 
                wire:click="generatePdf" 
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
                wire:loading.attr="disabled"
            >
                <svg wire:loading.remove xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>
                <svg wire:loading xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 animate-spin">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                Descargar PDF
            </button>
        </div>
    </div>

    <!-- Preview Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-4 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
            <h3 class="font-semibold text-slate-700">Vista Previa de Resultados ({{ $sales_preview->count() }} registros)</h3>
            <span class="text-xs text-slate-500">Ordenados por fecha reciente</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-medium">
                    <tr>
                        <th class="px-4 py-3 border-b">Fecha</th>
                        <th class="px-4 py-3 border-b">Boleta</th>
                        <th class="px-4 py-3 border-b">Cliente</th>
                        <th class="px-4 py-3 border-b">Doc. Cliente</th>
                        <th class="px-4 py-3 border-b">Vendedor</th>
                        <th class="px-4 py-3 border-b">Vehículo</th>
                        <th class="px-4 py-3 border-b text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($sales_preview as $sale)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-4 py-3">{{ $sale->created_at->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 font-medium text-indigo-600">{{ $sale->numero_boleta }}</td>
                            <td class="px-4 py-3 font-semibold text-slate-800">{{ $sale->customer->nombres_completos ?? 'N/A' }}</td>
                            <td class="px-4 py-3">{{ $sale->customer->numero_documento ?? 'N/A' }}</td>
                            <td class="px-4 py-3">{{ $sale->user->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3">{{ $sale->vehicle->marca ?? '' }} {{ $sale->vehicle->modelo ?? '' }}</td>
                            <td class="px-4 py-3 text-right font-bold text-slate-800">${{ number_format($sale->monto_total, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 opacity-50">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                    </svg>
                                    <span>No se encontraron ventas con los filtros seleccionados.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
