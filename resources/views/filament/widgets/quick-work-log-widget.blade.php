<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Registro Rápido de Soporte
        </x-slot>

        <form wire:submit="create">
            {{ $this->form }}

            <div class="mt-4 flex justify-end">
                <x-filament::button type="submit">
                    Registrar
                </x-filament::button>
            </div>
        </form>
    </x-filament::section>
</x-filament-widgets::widget>
