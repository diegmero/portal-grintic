@props(['files', 'label' => 'Ver Informes'])

<div x-data="{ open: false }" class="inline-block">
    <button 
        type="button" 
        @click="open = true" 
        class="text-sm font-medium text-primary-600 hover:text-primary-500 hover:underline dark:text-primary-400 dark:hover:text-primary-300"
        style="color: rgb(var(--primary-600));"
    >
        📎 {{ $label }} ({{ count($files) }})
    </button>

    <div
        x-show="open"
        style="display: none;"
        x-on:keydown.escape.window="open = false"
        class="fixed inset-0 z-[80] overflow-y-auto overflow-x-hidden flex items-center justify-center p-4 sm:p-6"
        aria-labelledby="modal-title" 
        role="dialog" 
        aria-modal="true"
    >
        <!-- Backdrop -->
        <div 
            x-show="open"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-950/75 dark:bg-gray-950/80 backdrop-blur-sm transition-opacity" 
            @click="open = false"
        ></div>

        <!-- Modal Panel -->
        <div 
            x-show="open"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative w-full max-w-5xl transform rounded-xl bg-white dark:bg-gray-900 text-left shadow-2xl transition-all ring-1 ring-gray-950/5 dark:ring-white/10 flex flex-col"
            style="max-height: 90vh;"
        >
            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-white/10">
                <h3 class="text-xl font-bold tracking-tight text-gray-950 dark:text-white" id="modal-title">
                    Informes de Soporte
                </h3>
                
                <button 
                    type="button" 
                    class="rounded-full p-2 text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-white/5 dark:hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-500" 
                    @click="open = false"
                >
                    <span class="sr-only">Cerrar</span>
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Content -->
            <div class="p-6 overflow-y-auto flex-1">
                @include('filament.components.file-gallery', ['files' => $files])
            </div>
            
            <!-- Removed Footer -->
            <div class="hidden"></div>
        </div>
    </div>
</div>
