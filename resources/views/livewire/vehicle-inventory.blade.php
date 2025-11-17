<div>
    {{-- CABECERA --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-zinc-800 dark:text-zinc-200 leading-tight">
            {{ __('Gestión de Inventario') }}
        </h2>
    </x-slot>

    {{-- CONTENIDO --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- MENSAJE DE ÉXITO --}}
            @if (session()->has('success'))
                <div class="mb-6 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-200 px-4 py-3 rounded-md relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-zinc-900 dark:text-zinc-100">

                    {{-- BARRA DE ACCIONES --}}
                    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
                        <input 
                            wire:model.live.debounce.300ms="search"
                            type="text" 
                            placeholder="Buscar por marca, modelo o chasis..." 
                            class="w-full sm:w-1/2 rounded-md shadow-sm border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100 focus:border-indigo-500 focus:ring-indigo-500"
                        >

                        @role('Jefe de Almacén')
                        <button 
                            wire:click="openCreateModal"
                            class="w-full sm:w-auto px-4 py-2 bg-indigo-600 dark:bg-indigo-500 text-white font-semibold rounded-md shadow-sm hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-800 transition ease-in-out duration-150"
                        >
                            <span class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                Registrar Vehículo
                            </span>
                        </button>
                        @endrole
                    </div>

                    {{-- TABLA DE VEHÍCULOS --}}
                    <div class="overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                            <thead class="bg-zinc-50 dark:bg-zinc-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">Marca / Modelo</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">Año / Color</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">N° Chasis</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">Precio</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">Estado</th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Acciones</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-zinc-800 divide-y divide-zinc-200 dark:divide-zinc-700">
                                @forelse ($vehicles as $vehicle)
                                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $vehicle->marca }}</div>
                                            <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ $vehicle->modelo }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-zinc-900 dark:text-zinc-100">{{ $vehicle->anio_fabricacion }}</div>
                                            <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ $vehicle->color }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400 font-mono">{{ $vehicle->numero_chasis }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-zinc-900 dark:text-zinc-100">${{ number_format($vehicle->precio_venta, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $estadoClasses = match($vehicle->estado) {
                                                    'Disponible' => 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200',
                                                    'Vendido' => 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200',
                                                    'En Traslado' => 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200',
                                                    default => 'bg-zinc-100 dark:bg-zinc-900 text-zinc-800 dark:text-zinc-200',
                                                };
                                            @endphp
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $estadoClasses }}">
                                                {{ $vehicle->estado }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            @role('Jefe de Almacén')
                                                @if($vehicle->estado == 'Disponible')
                                                    <button 
                                                        wire:click="openEditModal({{ $vehicle->id }})"
                                                        class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300"
                                                    >
                                                        Editar
                                                    </button>
                                                @else
                                                    <span class="text-zinc-400 dark:text-zinc-600 cursor-not-allowed">Editar</span>
                                                @endif
                                            @endrole
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center text-zinc-500 dark:text-zinc-400">
                                            No se encontraron vehículos.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- PAGINACIÓN --}}
                    <div class="mt-6">
                        {{ $vehicles->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- MODAL DE CREACIÓN (REUTILIZANDO COMPONENTES) --}}
    {{-- ============================================ --}}
    <x-vehicle-modal
        show="showCreateModal"
        title="Registrar Nuevo Vehículo"
        subtitle="Complete todos los campos requeridos"
        submit-action="saveVehicle"
        submit-text="Guardar Vehículo"
        icon="create"
    >
        <x-vehicle-form mode="create" prefix="create" />
    </x-vehicle-modal>

    {{-- ============================================ --}}
    {{-- MODAL DE EDICIÓN (REUTILIZANDO COMPONENTES) --}}
    {{-- ============================================ --}}
    @if($editingVehicleId)
    <x-vehicle-modal
        show="showEditModal"
        title="Editar Vehículo"
        subtitle="Modifique los campos necesarios"
        submit-action="updateVehicle"
        submit-text="Guardar Cambios"
        icon="edit"
    >
        <x-vehicle-form mode="edit" prefix="edit" />
    </x-vehicle-modal>
    @endif

</div>