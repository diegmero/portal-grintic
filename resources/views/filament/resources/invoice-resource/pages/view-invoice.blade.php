<x-filament-panels::page>
    <div style="display: flex; flex-direction: row; gap: 1.5rem;">
        {{-- Columna izquierda: Items y Pagos --}}
        <div style="flex: 1; min-width: 0;">
            <x-filament-panels::resources.relation-managers
                :active-manager="$this->activeRelationManager"
                :managers="$this->getRelationManagers()"
                :owner-record="$this->record"
                :page-class="static::class"
            />
        </div>

        {{-- Columna derecha: Información de la factura --}}
        <div style="width: 350px; min-width: 350px; flex-shrink: 0;">
            {{ $this->infolist }}
        </div>
    </div>
</x-filament-panels::page>
