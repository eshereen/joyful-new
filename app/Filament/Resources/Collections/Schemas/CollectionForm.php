<?php

namespace App\Filament\Resources\Collections\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Schemas\Schema;

class CollectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('price')
                    ->numeric()
                    ->prefix('EGP')
                    ->step(0.01)
                    ->minValue(0)
                    ->required()
                    ->helperText('Bundle price shown on the storefront.'),
                TextInput::make('stock')
                    ->numeric()
                    ->minValue(0)
                    ->required()
                    ->helperText('Number of collection sets available for purchase.'),
                SpatieMediaLibraryFileUpload::make('main_image')
                    ->collection('main_image')
                    ->disk('public')
                    ->image()
                    ->required()
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        null,
                        '16:9',
                        '4:3',
                        '1:1',
                    ])
                    ->maxSize(10240) // 10MB
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->helperText('Upload collection image. Max size: 10MB'),
                Toggle::make('active')
                    ->required(),
            ]);
    }
}
