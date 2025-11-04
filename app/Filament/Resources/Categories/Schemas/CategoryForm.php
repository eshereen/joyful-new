<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                    RichEditor::make('description')
                    ->toolbarButtons([
                        'bold',
                        'italic',
                        'strike',
                        'link',
                        'blockquote',
                        'codeBlock',
                        'h2',
                        'h3',
                        'bulletList',
                        'orderedList',
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
