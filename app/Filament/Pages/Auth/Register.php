<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Form;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Register as BaseRegister;

class Register extends BaseRegister
{
    /**
     * Override parent public form  method
     *
     * @param Form $form
     * @return Form
     */
    public function form(Form $form): Form
    {
        return $this->makeForm()
            ->schema([
                $this->getNameFormComponent(),
                $this->getSurnameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ])
            ->statePath('data');
    }

    /**
     * Get surname filament form component
     *
     * @return Component
     */
    protected function getSurnameFormComponent(): Component
    {
        return TextInput::make('surname')
            ->label('Surname')
            ->required()
            ->maxLength(64)
            ->autofocus();
    }
}
