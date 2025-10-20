<?php

namespace App\Filament\Resources\Variants;

use App\Filament\Resources\Variants\Pages\CreateVariant;
use App\Filament\Resources\Variants\Pages\EditVariant;
use App\Filament\Resources\Variants\Pages\ListVariants;
use App\Filament\Resources\Variants\Pages\ViewVariant;
use App\Filament\Resources\Variants\Schemas\VariantForm;
use App\Filament\Resources\Variants\Schemas\VariantInfolist;
use App\Filament\Resources\Variants\Tables\VariantsTable;
use App\Models\Variant;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class VariantResource extends Resource
{
    protected static ?string $model = Variant::class;

    protected static UnitEnum|string|null $navigationGroup = 'Products Details';

    public static function form(Schema $schema): Schema
    {
        return VariantForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return VariantInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VariantsTable::configure($table);
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
            'index' => ListVariants::route('/'),
            'create' => CreateVariant::route('/create'),
            'view' => ViewVariant::route('/{record}'),
            'edit' => EditVariant::route('/{record}/edit'),
        ];
    }
}
