<div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
    {{-- Header del bloque con título y botón --}}
    <header class="fi-section-header flex flex-col gap-3 px-6 py-4">
        <div class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <x-heroicon-o-document-text class="fi-section-header-icon h-6 w-6 text-gray-400 dark:text-gray-500" />
                <h3 class="fi-section-header-heading text-base font-semibold leading-6 text-gray-950 dark:text-white">
                    Documentación del Proyecto
                </h3>
            </div>
            
            {{-- Botón de editar integrado --}}
            <div>
                {{ $this->table }}
            </div>
        </div>
    </header>

    {{-- Contenido del bloque --}}
    <div class="fi-section-content-ctn border-t border-gray-200 dark:border-white/10">
        <div class="fi-section-content p-6">
            {{ $infolist }}
        </div>
    </div>
</div>
