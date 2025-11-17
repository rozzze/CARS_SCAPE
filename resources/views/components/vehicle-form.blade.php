{{-- 
    Componente: resources/views/components/vehicle-form.blade.php
    
    Props:
    - $mode: 'create' | 'edit' (para diferenciar los wire:model)
    - $prefix: 'create' | 'edit' (para los IDs de los inputs)
--}}

@props(['mode' => 'create', 'prefix' => 'create'])

@php
    // Definimos el wire:model base según el modo
    $wireModelBase = $mode === 'edit' ? 'editingVehicle.' : '';
@endphp

<div class="space-y-6">
    
    {{-- SECCIÓN: Información General --}}
    <div>
        <h4 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-indigo-600">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
            </svg>
            Información General
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            
            {{-- Marca --}}
            <div>
                <label for="{{ $prefix }}_marca" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                    Marca <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="{{ $prefix }}_marca" 
                    wire:model="{{ $wireModelBase }}marca" 
                    placeholder="Ej: Toyota, Honda, Ford"
                    class="w-full rounded-lg shadow-sm border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 transition-colors"
                >
                @error($wireModelBase . 'marca') 
                    <span class="text-red-500 text-xs mt-1 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                        {{ $message }}
                    </span> 
                @enderror
            </div>

            {{-- Modelo --}}
            <div>
                <label for="{{ $prefix }}_modelo" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                    Modelo <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="{{ $prefix }}_modelo" 
                    wire:model="{{ $wireModelBase }}modelo" 
                    placeholder="Ej: Corolla, Civic, Mustang"
                    class="w-full rounded-lg shadow-sm border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 transition-colors"
                >
                @error($wireModelBase . 'modelo') 
                    <span class="text-red-500 text-xs mt-1 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                        {{ $message }}
                    </span> 
                @enderror
            </div>

            {{-- Año --}}
            <div>
                <label for="{{ $prefix }}_anio_fabricacion" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                    Año de Fabricación <span class="text-red-500">*</span>
                </label>
                <input 
                    type="number" 
                    id="{{ $prefix }}_anio_fabricacion" 
                    wire:model="{{ $wireModelBase }}anio_fabricacion" 
                    placeholder="Ej: 2024"
                    min="1950"
                    class="w-full rounded-lg shadow-sm border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 transition-colors"
                >
                @error($wireModelBase . 'anio_fabricacion') 
                    <span class="text-red-500 text-xs mt-1 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                        {{ $message }}
                    </span> 
                @enderror
            </div>

            {{-- Color --}}
            <div>
                <label for="{{ $prefix }}_color" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                    Color <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="{{ $prefix }}_color" 
                    wire:model="{{ $wireModelBase }}color" 
                    placeholder="Ej: Rojo, Azul, Negro"
                    class="w-full rounded-lg shadow-sm border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 transition-colors"
                >
                @error($wireModelBase . 'color') 
                    <span class="text-red-500 text-xs mt-1 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                        {{ $message }}
                    </span> 
                @enderror
            </div>
        </div>
    </div>

    <div class="border-t border-zinc-200 dark:border-zinc-700"></div>

    {{-- SECCIÓN: Especificaciones Técnicas --}}
    <div>
        <h4 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-indigo-600">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z" />
            </svg>
            Especificaciones Técnicas
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            
            {{-- Número de Motor --}}
            <div>
                <label for="{{ $prefix }}_numero_motor" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                    Número de Motor <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="{{ $prefix }}_numero_motor" 
                    wire:model="{{ $wireModelBase }}numero_motor" 
                    placeholder="Debe ser único"
                    class="w-full rounded-lg shadow-sm border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 transition-colors font-mono"
                >
                @error($wireModelBase . 'numero_motor') 
                    <span class="text-red-500 text-xs mt-1 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                        {{ $message }}
                    </span> 
                @enderror
            </div>

            {{-- Número de Chasis --}}
            <div>
                <label for="{{ $prefix }}_numero_chasis" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                    Número de Chasis (VIN) <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="{{ $prefix }}_numero_chasis" 
                    wire:model="{{ $wireModelBase }}numero_chasis" 
                    placeholder="Debe ser único"
                    class="w-full rounded-lg shadow-sm border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 transition-colors font-mono"
                >
                @error($wireModelBase . 'numero_chasis') 
                    <span class="text-red-500 text-xs mt-1 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                        {{ $message }}
                    </span> 
                @enderror
            </div>

            {{-- Tipo de Combustible --}}
            <div>
                <label for="{{ $prefix }}_tipo_combustible" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                    Tipo de Combustible <span class="text-red-500">*</span>
                </label>
                <select 
                    id="{{ $prefix }}_tipo_combustible" 
                    wire:model="{{ $wireModelBase }}tipo_combustible" 
                    class="w-full rounded-lg shadow-sm border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 transition-colors"
                >
                    <option value="">Seleccione...</option>
                    <option value="Gasolina">Gasolina</option>
                    <option value="Diesel">Diesel</option>
                    <option value="Eléctrico">Eléctrico</option>
                    <option value="Híbrido">Híbrido</option>
                </select>
                @error($wireModelBase . 'tipo_combustible') 
                    <span class="text-red-500 text-xs mt-1 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                        {{ $message }}
                    </span> 
                @enderror
            </div>

            {{-- Transmisión --}}
            <div>
                <label for="{{ $prefix }}_transmision" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                    Transmisión <span class="text-red-500">*</span>
                </label>
                <select 
                    id="{{ $prefix }}_transmision" 
                    wire:model="{{ $wireModelBase }}transmision" 
                    class="w-full rounded-lg shadow-sm border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 transition-colors"
                >
                    <option value="">Seleccione...</option>
                    <option value="Manual">Manual</option>
                    <option value="Automática">Automática</option>
                </select>
                @error($wireModelBase . 'transmision') 
                    <span class="text-red-500 text-xs mt-1 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                        {{ $message }}
                    </span> 
                @enderror
            </div>

            {{-- Kilometraje --}}
            <div>
                <label for="{{ $prefix }}_kilometraje" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                    Kilometraje <span class="text-red-500">*</span>
                </label>
                <input 
                    type="number" 
                    id="{{ $prefix }}_kilometraje" 
                    wire:model="{{ $wireModelBase }}kilometraje" 
                    placeholder="0"
                    min="0"
                    class="w-full rounded-lg shadow-sm border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 transition-colors"
                >
                @error($wireModelBase . 'kilometraje') 
                    <span class="text-red-500 text-xs mt-1 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                        {{ $message }}
                    </span> 
                @enderror
            </div>

            {{-- Precio de Venta --}}
            <div>
                <label for="{{ $prefix }}_precio_venta" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                    Precio de Venta ($) <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-zinc-500 dark:text-zinc-400 font-semibold">$</span>
                    </div>
                    <input 
                        type="number" 
                        step="0.01" 
                        id="{{ $prefix }}_precio_venta" 
                        wire:model="{{ $wireModelBase }}precio_venta" 
                        placeholder="0.00"
                        min="0"
                        class="w-full pl-8 rounded-lg shadow-sm border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 transition-colors"
                    >
                </div>
                @error($wireModelBase . 'precio_venta') 
                    <span class="text-red-500 text-xs mt-1 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                        {{ $message }}
                    </span> 
                @enderror
            </div>
        </div>
    </div>

    <div class="border-t border-zinc-200 dark:border-zinc-700"></div>

    {{-- SECCIÓN: Características Adicionales --}}
    <div>
        <h4 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-indigo-600">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
            </svg>
            Características Adicionales
        </h4>
        <div>
            <label for="{{ $prefix }}_caracteristicas_adicionales" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                Descripción (Opcional)
            </label>
            <textarea 
                id="{{ $prefix }}_caracteristicas_adicionales" 
                wire:model="{{ $wireModelBase }}caracteristicas_adicionales" 
                rows="4" 
                placeholder="Ej: Aire acondicionado, sistema de navegación, asientos de cuero, cámara de reversa..."
                class="w-full rounded-lg shadow-sm border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 transition-colors resize-none"
            ></textarea>
            @error($wireModelBase . 'caracteristicas_adicionales') 
                <span class="text-red-500 text-xs mt-1 flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                    {{ $message }}
                </span> 
            @enderror
        </div>
    </div>

</div>