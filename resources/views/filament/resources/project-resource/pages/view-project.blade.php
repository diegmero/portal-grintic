<x-filament-panels::page>
    <style>
        /* Force equal height cards in the info grid */
        .fi-in-grid {
            display: flex !important;
            align-items: stretch !important;
        }
        .fi-in-grid > div {
            display: flex;
            flex: 1;
        }
        .fi-in-grid > div > .fi-in-section {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .fi-in-grid > div > .fi-in-section > .fi-in-section-content-ctn {
            flex-grow: 1;
        }
    </style>

    {{-- Información del proyecto con polling para métricas en vivo --}}
    <div class="mb-6" wire:poll.5s>
        {{ $this->infolist }}
    </div>

    {{-- Relaciones: Tareas, Documentación, Recursos, Facturas --}}
    <x-filament-panels::resources.relation-managers
        :active-manager="$this->activeRelationManager"
        :managers="$this->getRelationManagers()"
        :owner-record="$this->record"
        :page-class="static::class"
    />
</x-filament-panels::page>
