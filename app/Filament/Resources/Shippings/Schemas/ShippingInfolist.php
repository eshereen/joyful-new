<?php

namespace App\Filament\Resources\Shippings\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ShippingInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('state_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('price')
                    ->money(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
