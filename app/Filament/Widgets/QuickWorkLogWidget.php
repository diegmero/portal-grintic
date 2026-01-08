<?php

namespace App\Filament\Widgets;

use App\Models\Client;
use App\Models\Project;
use App\Models\WorkLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Widgets\Widget;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;

class QuickWorkLogWidget extends Widget implements HasForms
{
    use InteractsWithForms;

    protected int | string | array $columnSpan = 'full';

    protected static string $view = 'filament.widgets.quick-work-log-widget';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(4)
                    ->schema([
                        Forms\Components\Select::make('client_id')
                            ->label('Cliente')
                            ->options(Client::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn (callable $set) => $set('project_id', null)),
                        
                        Forms\Components\Select::make('project_id')
                            ->label('Proyecto')
                            ->options(fn (callable $get) => 
                                Project::where('client_id', $get('client_id'))->pluck('name', 'id')
                            )
                            ->searchable()
                            ->required(),

                        Forms\Components\TextInput::make('hours')
                            ->label('Horas')
                            ->numeric()
                            ->step(0.5)
                            ->required(),

                        Forms\Components\TextInput::make('description')
                            ->label('Descripción')
                            ->required(),
                    ]),
            ])
            ->statePath('data');
    }

    public function create(): void
    {
        $data = $this->form->getState();

        WorkLog::create($data);

        Notification::make()
            ->title('Horas registradas')
            ->success()
            ->send();

        $this->form->fill();
    }
}
