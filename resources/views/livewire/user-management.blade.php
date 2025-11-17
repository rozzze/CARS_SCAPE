<div>
    {{-- 1. CABECERA DE LA PÁGINA --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-zinc-800 dark:text-zinc-200 leading-tight">
            {{ __('Gestión de Usuarios') }}
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

                    <!-- BARRA DE ACCIONES: Buscador y Botón -->
                    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
                        <!-- Buscador -->
                        <input 
                            wire:model.live.debounce.300ms="search"
                            type="text" 
                            placeholder="Buscar por nombre, email o DNI..." 
                            class="w-full sm:w-1/2 rounded-md shadow-sm border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100 focus:border-indigo-500 focus:ring-indigo-500"
                        >

                        <!-- Botón Nuevo Usuario -->
                        <button 
                            wire:click="openCreateModal"
                            class="w-full sm:w-auto px-4 py-2 bg-indigo-600 dark:bg-indigo-500 text-white font-semibold rounded-md shadow-sm hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-800 transition ease-in-out duration-150"
                        >
                            <span class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.5 21h-5.085A12.318 12.318 0 014 19.235z" />
                                </svg>
                                Registrar Usuario
                            </span>
                        </button>
                    </div>

                    <!-- TABLA DE USUARIOS -->
                    <div class="overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                            <thead class="bg-zinc-50 dark:bg-zinc-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">Nombre</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">Contacto</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">Rol</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">Estado</th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Acciones</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-zinc-800 divide-y divide-zinc-200 dark:divide-zinc-700">
                                @forelse ($users as $user)
                                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $user->name }}</div>
                                            <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ $user->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-zinc-900 dark:text-zinc-100 font-mono">{{ $user->dni ?? 'N/A' }}</div>
                                            <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ $user->telefono ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                                            {{ $user->roles->first()->name ?? 'Sin Rol' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $estadoClasses = $user->is_active
                                                    ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200'
                                                    : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200';
                                            @endphp
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $estadoClasses }}">
                                                {{ $user->is_active ? 'Activo' : 'Inactivo' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button 
                                                wire:click="openEditModal({{ $user->id }})"
                                                class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300"
                                            >
                                                Editar
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-zinc-500 dark:text-zinc-400">
                                            No se encontraron usuarios.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- PAGINACIÓN -->
                    <div class="mt-6">
                        {{ $users->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- ================================================== --}}
    {{-- MODAL DE CREACIÓN DE USUARIO --}}
    {{-- ================================================== --}}
    <x-crud-modal
        show="showCreateModal"
        title="Registrar Nuevo Usuario"
        subtitle="Complete la información del empleado y asigne un rol"
        submit-action="saveUser"
        submit-text="Guardar Usuario"
    >
        <x-slot name="icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-white">
              <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.5 21h-5.085A12.318 12.318 0 014 19.235z" />
            </svg>
        </x-slot>

        <x-slot name="submitIcon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </x-slot>

        <x-user-form mode="create" prefix="create" :roles="$roles" />
    </x-crud-modal>

    {{-- ================================================== --}}
    {{-- MODAL DE EDICIÓN DE USUARIO --}}
    {{-- ================================================== --}}
    @if($editingUserId)
    <x-crud-modal
        show="showEditModal"
        title="Editar Usuario"
        subtitle="Modifique los campos necesarios"
        submit-action="updateUser"
        submit-text="Guardar Cambios"
    >
        <x-slot name="icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-white">
              <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
            </svg>
        </x-slot>

        <x-slot name="submitIcon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </x-slot>

        <x-user-form mode="edit" prefix="edit" :roles="$roles" />
    </x-crud-modal>
    @endif

</div>