<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Utilities\Get;

class EditProfile extends BaseEditProfile
{
    public static function getLabel(): string
    {
        return 'Account settings';
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label('Login email')
            ->email()
            ->disabled()
            ->dehydrated(false)
            ->helperText('Contact the site administrator if this login email needs to change.');
    }

    protected function getCurrentPasswordFormComponent(): Component
    {
        return parent::getCurrentPasswordFormComponent()
            ->visible(fn (Get $get): bool => filled($get('password')));
    }
}
