<?php

namespace App\Filament\App\Pages\Auth;

use App\Models\User;
use App\Models\Customer;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Auth\Register as BaseRegister;

class Register extends BaseRegister
{

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Login Credentials')
                    ->schema([
                        $this->getNameFormComponent()
                            ->label('Username'),
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                    ]),

                Section::make('Personal Information')
                    ->schema([
                        $this->getFullNameFormComponent(),
                        $this->getPhoneFormComponent(),
                        $this->getDateFormComponent(),
                        $this->getAddressFormComponent(),
                        $this->getZipFormComponent(),
                        $this->getCityFormComponent(),
                    ])
            ])
            ->statePath('data');
    }


    protected function getRoleFormComponent(): Component
    {
        return Select::make('role')
            ->options([
                'user' => 'user',
                'admin' => 'admin',
            ])
            ->default('buyer')
            ->required();
    }

    protected function getFullNameFormComponent(): TextInput
    {
        return TextInput::make('full_name')
            ->label('Full name')
            ->required();
    }
    protected function getPhoneFormComponent(): TextInput
    {
        return TextInput::make('phone')
            ->label('Phone')
            ->numeric()
            ->required();
    }
    protected function getDateFormComponent(): DatePicker
    {
        return DatePicker::make('date_of_birth')
            ->label('Date of Birth')
            ->required();
    }
    protected function getAddressFormComponent(): TextInput
    {
        return TextInput::make('address')
            ->label('Address')
            ->required();
    }
    protected function getZipFormComponent(): TextInput
    {
        return TextInput::make('zip_code')
            ->label('Zip Code')
            ->required();
    }
    protected function getCityFormComponent(): TextInput
    {
        return TextInput::make('city')
            ->label('City')
            ->required();
    }
}
