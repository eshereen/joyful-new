<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->numeric(),
                TextInput::make('country_id')
                    ->numeric(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('first_name')
                    ->required(),
                TextInput::make('last_name')
                    ->required(),
                TextInput::make('phone_number')
                    ->tel(),
                TextInput::make('billing_country_id')
                    ->numeric(),
                TextInput::make('billing_state'),
                TextInput::make('billing_city'),
                Textarea::make('billing_address')
                    ->columnSpanFull(),
                TextInput::make('billing_building_number'),
                TextInput::make('shipping_country_id')
                    ->numeric(),
                TextInput::make('shipping_state'),
                TextInput::make('shipping_city'),
                Textarea::make('shipping_address')
                    ->columnSpanFull(),
                TextInput::make('shipping_building_number'),
                Toggle::make('use_billing_for_shipping')
                    ->required(),
            ]);
    }
}
