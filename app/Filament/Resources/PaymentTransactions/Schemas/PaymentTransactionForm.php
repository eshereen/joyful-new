<?php

namespace App\Filament\Resources\PaymentTransactions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PaymentTransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('payment_id')
                    ->required()
                    ->numeric(),
                TextInput::make('event')
                    ->required(),
                TextInput::make('provider_status'),
                TextInput::make('payload'),
                TextInput::make('idempotency_key'),
            ]);
    }
}
