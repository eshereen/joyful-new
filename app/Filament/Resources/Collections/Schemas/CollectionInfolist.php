<?php

namespace App\Filament\Resources\Collections\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Schemas\Schema;

class CollectionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('slug'),
                TextEntry::make('price')
                    ->money('EGP')
                    ->placeholder('Calculated from products'),
                TextEntry::make('stock')
                    ->label('Stock')
                    ->placeholder('0'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                SpatieMediaLibraryImageEntry::make('main_image')
                    ->collection('main_image')
                    ->placeholder('No image uploaded')
                    ->columnSpanFull(),
                IconEntry::make('active')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
