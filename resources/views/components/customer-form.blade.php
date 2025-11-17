@props(['mode' => 'create', 'prefix' => 'create'])

@php
    // Tu métrica: $wireModelBase será 'editingCustomer.' en modo edición, o '' en modo creación.
    $wireModelBase = $mode === 'edit' ? 'editingCustomer.' : '';
@endphp

<div class="space-y-6">

    {{-- SECCIÓN: Información Personal --}}
    <div>
        <h4 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-indigo-600">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
            </svg>
            Información Personal
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            
            {{-- Tipo Documento --}}
            <div>
                <label for="{{ $prefix }}_tipo_documento" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                    Tipo de Documento <span class="text-red-500">*</span>
                </label>
                <select 
                    id="{{ $prefix }}_tipo_documento" 
                    wire:model="{{ $wireModelBase }}tipo_documento" 
                    class="w-full rounded-lg shadow-sm border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 transition-colors"
                >
                    <option value="DNI">DNI</option>
                    <option value="RUC">RUC</option>
                </select>
                @error($wireModelBase . 'tipo_documento') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
            
            {{-- Número Documento --}}
            <div>
                <label for="{{ $prefix }}_numero_documento" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                    Número de Documento <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="{{ $prefix }}_numero_documento" 
                    wire:model="{{ $wireModelBase }}numero_documento" 
                    placeholder="Debe ser único"
                    class="w-full rounded-lg shadow-sm border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 transition-colors"
                >
                @error($wireModelBase . 'numero_documento') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Nombres Completos / Razón Social --}}
            <div class="md:col-span-2">
                <label for="{{ $prefix }}_nombres_completos" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                    Nombres Completos / Razón Social <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="{{ $prefix }}_nombres_completos" 
                    wire:model="{{ $wireModelBase }}nombres_completos" 
                    placeholder="Juan Perez o Cars Escape S.A.C."
                    class="w-full rounded-lg shadow-sm border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 transition-colors"
                >
                @error($wireModelBase . 'nombres_completos') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>

    <div class="border-t border-zinc-200 dark:border-zinc-700"></div>

    {{-- SECCIÓN: Información de Contacto --}}
    <div>
        <h4 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-indigo-600">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
            </svg>
            Información de Contacto y Ubicación
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            
            {{-- Dirección Completa --}}
            <div class="md:col-span-2">
                <label for="{{ $prefix }}_direccion_completa" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                    Dirección Completa <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="{{ $prefix }}_direccion_completa" 
                    wire:model="{{ $wireModelBase }}direccion_completa" 
                    placeholder="Av. Siempre Viva 123"
                    class="w-full rounded-lg shadow-sm border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 transition-colors"
                >
                @error($wireModelBase . 'direccion_completa') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Ciudad --}}
            <div>
                <label for="{{ $prefix }}_ciudad" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                    Ciudad <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="{{ $prefix }}_ciudad" 
                    wire:model="{{ $wireModelBase }}ciudad" 
                    placeholder="Arequipa"
                    class="w-full rounded-lg shadow-sm border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 transition-colors"
                >
                @error($wireModelBase . 'ciudad') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Teléfono --}}
            <div>
                <label for="{{ $prefix }}_telefono" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                    Teléfono (Opcional)
                </label>
                <input 
                    type="tel" 
                    id="{{ $prefix }}_telefono" 
                    wire:model="{{ $wireModelBase }}telefono" 
                    placeholder="987654321"
                    class="w-full rounded-lg shadow-sm border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 transition-colors"
                >
                @error($wireModelBase . 'telefono') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Correo Electrónico --}}
            <div class="md:col-span-2">
                <label for="{{ $prefix }}_correo_electronico" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                    Correo Electrónico (Opcional)
                </label>
                <input 
                    type="email" 
                    id="{{ $prefix }}_correo_electronico" 
                    wire:model="{{ $wireModelBase }}correo_electronico" 
                    placeholder="cliente@correo.com"
                    class="w-full rounded-lg shadow-sm border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 transition-colors"
                >
                @error($wireModelBase . 'correo_electronico') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

        </div>
    </div>
</div>