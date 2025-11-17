@props(['mode' => 'create', 'prefix' => 'create', 'roles' => []])

@php
    // Tu métrica: $wireModelBase será 'editingUser.' en modo edición, o '' en modo creación.
    $wireModelBase = $mode === 'edit' ? 'editingUser.' : '';
@endphp

<div class="space-y-6">

    {{-- SECCIÓN: Información de Acceso --}}
    <div>
        <h4 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-indigo-600">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
            </svg>
            Información de Acceso y Rol
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            
            {{-- Nombres Completos --}}
            <div>
                <label for="{{ $prefix }}_name" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                    Nombres Completos <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="{{ $prefix }}_name" 
                    wire:model="{{ $wireModelBase }}name" 
                    placeholder="Juan Perez"
                    class="w-full rounded-lg shadow-sm border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 transition-colors"
                >
                @error($wireModelBase . 'name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Email --}}
            <div>
                <label for="{{ $prefix }}_email" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                    Email (debe ser único) <span class="text-red-500">*</span>
                </label>
                <input 
                    type="email" 
                    id="{{ $prefix }}_email" 
                    wire:model="{{ $wireModelBase }}email" 
                    placeholder="usuario@carsescape.com"
                    class="w-full rounded-lg shadow-sm border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 transition-colors"
                >
                @error($wireModelBase . 'email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Rol del Sistema (RF-05) --}}
            <div>
                <label for="{{ $prefix }}_role_id" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                    Rol del Sistema <span class="text-red-500">*</span>
                </label>
                <select 
                    id="{{ $prefix }}_role_id" 
                    wire:model="{{ $wireModelBase }}role_id" 
                    class="w-full rounded-lg shadow-sm border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 transition-colors"
                >
                    <option value="">Seleccione un rol...</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
                @error($wireModelBase . 'role_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Estado (RF-07) --}}
            <div>
                <label for="{{ $prefix }}_is_active" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                    Estado <span class="text-red-500">*</span>
                </label>
                <select 
                    id="{{ $prefix }}_is_active" 
                    wire:model="{{ $wireModelBase }}is_active" 
                    class="w-full rounded-lg shadow-sm border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 transition-colors"
                >
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
                @error($wireModelBase . 'is_active') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

        </div>
    </div>

    <div class="border-t border-zinc-200 dark:border-zinc-700"></div>

    {{-- SECCIÓN: Información Personal --}}
    <div>
        <h4 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-indigo-600">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h3.75" />
            </svg>
            Información Adicional (RF-04)
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            {{-- DNI --}}
            <div>
                <label for="{{ $prefix }}_dni" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                    DNI (debe ser único)
                </label>
                <input 
                    type="text" 
                    id="{{ $prefix }}_dni" 
                    wire:model="{{ $wireModelBase }}dni" 
                    placeholder="Ej: 12345678"
                    class="w-full rounded-lg shadow-sm border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 transition-colors"
                >
                @error($wireModelBase . 'dni') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Teléfono --}}
            <div>
                <label for="{{ $prefix }}_telefono" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                    Teléfono
                </label>
                <input 
                    type="tel" 
                    id="{{ $prefix }}_telefono" 
                    wire:model="{{ $wireModelBase }}telefono" 
                    placeholder="Ej: 987654321"
                    class="w-full rounded-lg shadow-sm border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 transition-colors"
                >
                @error($wireModelBase . 'telefono') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Dirección --}}
            <div class="md:col-span-2">
                <label for="{{ $prefix }}_direccion" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                    Dirección
                </label>
                <input 
                    type="text" 
                    id="{{ $prefix }}_direccion" 
                    wire:model="{{ $wireModelBase }}direccion" 
                    placeholder="Ej: Av. Principal 123"
                    class="w-full rounded-lg shadow-sm border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 transition-colors"
                >
                @error($wireModelBase . 'direccion') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>

    <div class="border-t border-zinc-200 dark:border-zinc-700"></div>

    {{-- SECCIÓN: Contraseña --}}
    <div>
        <h4 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-indigo-600">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
            </svg>
            Contraseña
        </h4>
        @if($mode === 'edit')
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-4">Dejar en blanco para no cambiar la contraseña.</p>
        @endif
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            {{-- Contraseña --}}
            <div>
                <label for="{{ $prefix }}_password" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                    Contraseña @if($mode === 'create')<span class="text-red-500">*</span>@endif
                </label>
                <input 
                    type="password" 
                    id="{{ $prefix }}_password" 
                    wire:model="{{ $wireModelBase }}password" 
                    class="w-full rounded-lg shadow-sm border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 transition-colors"
                >
                @error($wireModelBase . 'password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Confirmar Contraseña --}}
            <div>
                <label for="{{ $prefix }}_password_confirmation" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                    Confirmar Contraseña @if($mode === 'create')<span class="text-red-500">*</span>@endif
                </label>
                <input 
                    type="password" 
                    id="{{ $prefix }}_password_confirmation" 
                    wire:model="{{ $wireModelBase }}password_confirmation" 
                    class="w-full rounded-lg shadow-sm border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 transition-colors"
                >
            </div>
        </div>
    </div>
</div>