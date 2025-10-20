<?php

namespace App\Filament\Resources\Variants\Schemas;

use App\Models\Variant;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class VariantInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('product_id')
                    ->numeric(),
                TextEntry::make('sku')
                    ->label('SKU'),
                TextEntry::make('stock')
                    ->numeric(),
                TextEntry::make('price')
                    ->money()
                    ->placeholder('-'),
                TextEntry::make('compare_price')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('weight')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Variant $record): bool => $record->trashed()),
            ]);
    }
}
