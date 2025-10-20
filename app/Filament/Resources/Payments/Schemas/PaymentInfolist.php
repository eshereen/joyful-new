<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PaymentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('order.id')
                    ->label('Order'),
                TextEntry::make('provider'),
                TextEntry::make('provider_reference')
                    ->placeholder('-'),
                TextEntry::make('status'),
                TextEntry::make('currency'),
                TextEntry::make('amount_minor')
                    ->numeric(),
                TextEntry::make('return_url')
                    ->placeholder('-'),
                TextEntry::make('cancel_url')
                    ->placeholder('-'),
                TextEntry::make('webhook_signature')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
