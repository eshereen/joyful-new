<?php

namespace App\Filament\Resources\States\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class StateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('country_id')
                    ->relationship('country', 'name'),
                TextInput::make('name')
                    ->required(),
            ]);
    }
}
