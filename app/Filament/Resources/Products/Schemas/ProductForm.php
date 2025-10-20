<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required()
                    ->live(),
                TextInput::make('name')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                SpatieMediaLibraryFileUpload::make('main_image')
                    ->collection('main_image')
                    ->disk('public')
                    ->image()
                    ->required(),
                SpatieMediaLibraryFileUpload::make('product_images')
                    ->collection('product_images')
                    ->disk('public')
                    ->image()
                    ->multiple(),
                Toggle::make('featured')
                    ->required(),
                Toggle::make('active')
                    ->required(),
            ]);
    }
}
