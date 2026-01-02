<?php

namespace App\Filament\Resources;

use App\Enums\ProjectStatus;
use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\Widgets;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';
    
    protected static ?string $navigationLabel = 'Proyectos';
    
    protected static ?string $modelLabel = 'Proyecto';
    
    protected static ?string $pluralModelLabel = 'Proyectos';
    
    protected static ?string $navigationGroup = 'CRM';
    
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Select::make('client_id')
                            ->label('Cliente')
                            ->relationship('client', 'company_name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->columnSpan('full'),
                        
                        Forms\Components\TextInput::make('name')
                            ->label('Nombre del Proyecto')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan('full'),
                        
                        Forms\Components\Textarea::make('description')
                            ->label('Descripción')
                            ->rows(3)
                            ->columnSpan('full'),
                        
                        Forms\Components\TextInput::make('total_budget')
                            ->label('Presupuesto Total')
                            ->numeric()
                            ->required()
                            ->prefix('$')
                            ->minValue(0),
                        
                        Forms\Components\Select::make('status')
                            ->label('Estado')
                            ->options(ProjectStatus::class)
                            ->default(ProjectStatus::PLANNING)
                            ->required(),
                        
                        Forms\Components\DatePicker::make('started_at')
                            ->label('Fecha de Inicio'),
                        
                        Forms\Components\DatePicker::make('deadline')
                            ->label('Fecha Límite'),
                        
                        Forms\Components\DatePicker::make('completed_at')
                            ->label('Fecha de Finalización')
                            ->visible(fn (Forms\Get $get) => $get('status') === 'done'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('client.company_name')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('name')
                    ->label('Proyecto')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('total_budget')
                    ->label('Presupuesto')
                    ->money('USD')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('progress')
                    ->label('Progreso')
                    ->state(fn ($record) => $record->progress . '%')
                    ->badge()
                    ->color(fn ($record) => match(true) {
                        $record->progress >= 100 => 'success',
                        $record->progress >= 50 => 'warning',
                        default => 'gray',
                    }),
                
                Tables\Columns\TextColumn::make('deadline')
                    ->label('Fecha Límite')
                    ->date('d/m/Y')
                    ->sortable()
                    ->color(fn ($record) => $record?->is_overdue ? 'danger' : null)
                    ->icon(fn ($record) => $record?->is_overdue ? 'heroicon-o-exclamation-triangle' : null),
                
                Tables\Columns\TextColumn::make('started_at')
                    ->label('Inicio')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('completed_at')
                    ->label('Fin')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('client')
                    ->relationship('client', 'company_name')
                    ->searchable()
                    ->preload(),
                
                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options(ProjectStatus::class),
                
                Tables\Filters\Filter::make('active')
                    ->label('Solo Activos')
                    ->query(fn ($query) => $query->active())
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Ver'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('bill')
                    ->label('Facturar Proyecto')
                    ->icon('heroicon-o-document-text')
                    ->color('success')
                    ->visible(fn ($record) => 
                        $record->status === ProjectStatus::DONE && 
                        !$record->invoiceItems()->exists()
                    )
                    ->action(function ($record) {
                        return \DB::transaction(function () use ($record) {
                            // Crear factura (invoice_number se genera automáticamente)
                            $invoice = \App\Models\Invoice::create([
                                'client_id' => $record->client_id,
                                'issue_date' => now(),
                                'due_date' => now()->addDays(30),
                                'subtotal' => 0,
                                'tax_percentage' => 0,
                                'tax_amount' => 0,
                                'total' => 0,
                                'status' => 'draft',
                            ]);
                            
                            // Agregar item
                            $description = "Proyecto: {$record->name}";
                            if ($record->description) {
                                $description .= " - {$record->description}";
                            }
                            
                            $invoice->invoiceItems()->create([
                                'description' => $description,
                                'quantity' => 1,
                                'unit_price' => $record->total_budget,
                                'subtotal' => $record->total_budget,
                                'itemable_type' => \App\Models\Project::class,
                                'itemable_id' => $record->id,
                            ]);
                            
                            // Calcular totales
                            $invoice->calculateTotals();
                            
                            \Filament\Notifications\Notification::make()
                                ->success()
                                ->title('Factura generada')
                                ->body("Factura {$invoice->invoice_number} creada para el proyecto")
                                ->send();
                            
                            return redirect()->route('filament.admin.resources.invoices.edit', ['record' => $invoice]);
                        });
                    })
                    ->requiresConfirmation(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('started_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'view' => Pages\ViewProject::route('/{record}'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            Widgets\OverdueProjectsWidget::class,
            Widgets\OverdueTasksWidget::class,
        ];
    }
}