<?php

namespace App\Filament\Resources\ReviewResources;

use App\Filament\Resources\ReviewResources\Pages\CreateReviewResource;
use App\Filament\Resources\ReviewResources\Pages\EditReviewResource;
use App\Filament\Resources\ReviewResources\Pages\ListReviewResources;
use App\Filament\Resources\ReviewResources\Pages\ViewReviewResource;
use App\Filament\Resources\ReviewResources\Schemas\ReviewResourceForm;
use App\Filament\Resources\ReviewResources\Schemas\ReviewResourceInfolist;
use App\Filament\Resources\ReviewResources\Tables\ReviewResourcesTable;
use App\Models\Review;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return ReviewResourceForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ReviewResourceInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReviewResourcesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListReviewResources::route('/'),
            'create' => CreateReviewResource::route('/create'),
            'view' => ViewReviewResource::route('/{record}'),
            'edit' => EditReviewResource::route('/{record}/edit'),
        ];
    }
}
