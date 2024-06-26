<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;
class EditProfile extends BaseEditProfile
{
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
            ->operation('edit')
            ->model($this->getUser())
            ->statePath('data')
            ->inlineLabel(! static::isSimple());
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
