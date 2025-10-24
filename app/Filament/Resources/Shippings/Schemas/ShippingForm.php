<?php

namespace App\Filament\Resources\Shippings\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class ShippingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('state_id')
                    ->relationship('state', 'name'),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('EGP'),
            ]);
    }
}
