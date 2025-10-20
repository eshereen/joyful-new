<?php

namespace App\Filament\Resources\Countries\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CountryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('code')
                    ->required(),
                TextInput::make('phone_code')
                    ->tel()
                    ->required(),
                TextInput::make('currency_code')
                    ->required(),
                TextInput::make('currency_sympol'),
                TextInput::make('tax_rate')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                Toggle::make('active')
                    ->required(),
            ]);
    }
}
