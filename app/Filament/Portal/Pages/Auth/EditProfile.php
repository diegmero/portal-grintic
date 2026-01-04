<?php

namespace App\Filament\Portal\Pages\Auth;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class EditProfile extends BaseEditProfile
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Read-only Company Name
                \Filament\Forms\Components\Placeholder::make('company_name')
                    ->label('Empresa')
                    ->content(fn () => auth()->user()->client?->company_name ?? 'Sin Empresa Asignada'),

                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                
                \Filament\Forms\Components\TextInput::make('password')
                    ->label(__('filament-panels::pages/auth/edit-profile.form.password.label'))
                    ->password()
                    ->revealable()
                    ->rule(Password::default())
                    ->autocomplete('new-password')
                    ->dehydrated(fn ($state): bool => filled($state))
                    ->live(debounce: 500)
                    ->same('passwordConfirmation'),

                \Filament\Forms\Components\TextInput::make('passwordConfirmation')
                    ->label(__('filament-panels::pages/auth/edit-profile.form.password_confirmation.label'))
                    ->password()
                    ->revealable()
                    ->required(fn ($get) => filled($get('password')))
                    ->dehydrated(false),
            ]);
    }
}
