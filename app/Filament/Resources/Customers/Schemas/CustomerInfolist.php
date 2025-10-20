<?php

namespace App\Filament\Resources\Customers\Schemas;

use App\Models\Customer;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CustomerInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('country_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('email')
                    ->label('Email address'),
                TextEntry::make('first_name'),
                TextEntry::make('last_name'),
                TextEntry::make('phone_number')
                    ->placeholder('-'),
                TextEntry::make('billing_country_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('billing_state')
                    ->placeholder('-'),
                TextEntry::make('billing_city')
                    ->placeholder('-'),
                TextEntry::make('billing_address')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('billing_building_number')
                    ->placeholder('-'),
                TextEntry::make('shipping_country_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('shipping_state')
                    ->placeholder('-'),
                TextEntry::make('shipping_city')
                    ->placeholder('-'),
                TextEntry::make('shipping_address')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('shipping_building_number')
                    ->placeholder('-'),
                IconEntry::make('use_billing_for_shipping')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Customer $record): bool => $record->trashed()),
            ]);
    }
}
