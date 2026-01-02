<x-filament-panels::page>
    <div style="display: flex; flex-direction: row; gap: 1.5rem;">
        {{-- Columna izquierda: Solo formulario de edición --}}
        <div style="flex: 1; min-width: 0;">
            <x-filament-panels::form
                :wire:key="$this->getId() . '.forms.' . $this->getFormStatePath()"
                wire:submit="save"
            >
                {{ $this->form }}

                <x-filament-panels::form.actions
                    :actions="$this->getCachedFormActions()"
                    :full-width="$this->hasFullWidthFormActions()"
                />
            </x-filament-panels::form>
        </div>

        {{-- Columna derecha: Resumen de la factura --}}
        <div style="width: 350px; min-width: 350px; flex-shrink: 0;">
            {{ $this->infolist }}
        </div>
    </div>
</x-filament-panels::page>
