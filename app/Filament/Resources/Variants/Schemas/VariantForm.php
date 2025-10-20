<?php

namespace App\Filament\Resources\Variants\Schemas;

use App\Models\Variant;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class VariantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
               Select::make('product_id')
                    ->required()
                    ->relationship('product', 'name')
                    ->live(),
                Select::make('wick_type')
                    ->options(Variant::WICK_TYPES)
                    ->required(),
                Select::make('size')
                    ->options(Variant::SIZE)
                    ->required(),
                TextInput::make('stock')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('price')
                    ->numeric()
                    ->prefix('LE'),
                TextInput::make('compare_price')
                    ->numeric(),
                TextInput::make('weight')
                    ->numeric(),
            ]);
    }
}
