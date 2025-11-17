{{-- 
    Componente: resources/views/components/vehicle-modal.blade.php
    
    Props:
    - $show: nombre de la propiedad Livewire para controlar visibilidad
    - $title: título del modal
    - $subtitle: subtítulo del modal
    - $submitAction: nombre del método Livewire a ejecutar
    - $submitText: texto del botón de submit
    - $icon: tipo de icono ('create' o 'edit')
--}}

@props([
    'show',
    'title',
    'subtitle',
    'submitAction',
    'submitText',
    'icon' => 'create'
])

<div 
    x-data="{ show: @entangle($show) }"
    x-show="show"
    x-on:keydown.escape.window="show = false"
    class="fixed inset-0 z-50 overflow-y-auto"
    style="display: none;"
    x-cloak
>
    {{-- Overlay --}}
    <div 
        x-show="show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/75 backdrop-blur-sm"
    ></div>

    {{-- Modal Container --}}
    <div 
        x-show="show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="relative w-full max-w-4xl mx-auto my-8 px-4"
        @click.away="show = false"
    >
        {{-- Modal Panel --}}
        <div class="relative bg-white dark:bg-zinc-800 rounded-xl shadow-2xl">
            
            <form wire:submit="{{ $submitAction }}">
                {{-- Header --}}
                <div class="flex items-center justify-between p-6 border-b border-zinc-200 dark:border-zinc-700 bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-zinc-900 dark:to-zinc-800 rounded-t-xl">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-indigo-600 rounded-lg">
                            @if($icon === 'create')
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-zinc-900 dark:text-zinc-100">{{ $title }}</h3>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $subtitle }}</p>
                        </div>
                    </div>
                    <button 
                        type="button" 
                        @click="show = false" 
                        class="text-zinc-400 hover:text-zinc-500 dark:hover:text-zinc-300 transition-colors p-2 hover:bg-zinc-100 dark:hover:bg-zinc-700 rounded-lg"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Body --}}
                <div class="p-6 max-h-[calc(100vh-250px)] overflow-y-auto">
                    {{ $slot }}
                </div>

                {{-- Footer --}}
                <div class="flex items-center justify-end gap-3 p-6 bg-zinc-50 dark:bg-zinc-900 border-t border-zinc-200 dark:border-zinc-700 rounded-b-xl">
                    <button 
                        type="button" 
                        @click="show = false" 
                        class="px-5 py-2.5 bg-white dark:bg-zinc-700 text-zinc-700 dark:text-zinc-200 font-semibold rounded-lg shadow-sm border border-zinc-300 dark:border-zinc-600 hover:bg-zinc-50 dark:hover:bg-zinc-600 transition-all duration-200 flex items-center gap-2"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Cancelar
                    </button>
                    
                    <button 
                        type="submit" 
                        class="px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 dark:from-indigo-500 dark:to-blue-500 dark:hover:from-indigo-600 dark:hover:to-blue-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                        wire:loading.attr="disabled"
                        wire:target="{{ $submitAction }}"
                    >
                        <span wire:loading.remove wire:target="{{ $submitAction }}" class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $submitText }}
                        </span>
                        <span wire:loading wire:target="{{ $submitAction }}" class="flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Guardando...
                        </span>
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>