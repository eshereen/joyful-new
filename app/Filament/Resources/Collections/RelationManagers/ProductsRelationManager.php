<?php

namespace App\Filament\Resources\Collections\RelationManagers;

use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('main_image')
                    ->collection('main_image')
                    ->circular()
                    ->extraAttributes(['style' => 'width: 50px; height: 50px;']),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable(),
                TextColumn::make('slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('featured')
                    ->boolean()
                    ->sortable(),
                IconColumn::make('active')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('active')
                    ->options([
                        1 => 'Active',
                        0 => 'Inactive',
                    ])
                    ->label('Status'),
                SelectFilter::make('featured')
                    ->options([
                        1 => 'Featured',
                        0 => 'Not Featured',
                    ])
                    ->label('Featured'),
            ])
            ->headerActions([
                AttachAction::make()
                    ->preloadRecordSelect()
                    ->recordSelectOptionsQuery(fn ($query) => $query->where('active', true))
                    ->recordSelectSearchColumns(['name', 'slug'])
                    ->multiple()
                    ->label('Add Products'),
            ])
            ->actions([
                DetachAction::make()
                    ->label('Remove'),
            ])
            ->bulkActions([
                DetachBulkAction::make()
                    ->label('Remove Selected'),
            ])
            ->defaultSort('created_at', 'desc');
    }
}

