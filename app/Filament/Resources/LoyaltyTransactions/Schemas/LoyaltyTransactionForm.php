<?php

namespace App\Filament\Resources\LoyaltyTransactions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LoyaltyTransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('points')
                    ->required()
                    ->numeric(),
                TextInput::make('action')
                    ->required(),
                TextInput::make('description'),
                TextInput::make('source_type'),
                TextInput::make('source_id')
                    ->numeric(),
            ]);
    }
}
