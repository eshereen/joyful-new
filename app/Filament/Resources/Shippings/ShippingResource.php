<?php

namespace App\Filament\Resources\Shippings;

use App\Filament\Resources\Shippings\Pages\CreateShipping;
use App\Filament\Resources\Shippings\Pages\EditShipping;
use App\Filament\Resources\Shippings\Pages\ListShippings;
use App\Filament\Resources\Shippings\Pages\ViewShipping;
use App\Filament\Resources\Shippings\Schemas\ShippingForm;
use App\Filament\Resources\Shippings\Schemas\ShippingInfolist;
use App\Filament\Resources\Shippings\Tables\ShippingsTable;
use App\Models\Shipping;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class ShippingResource extends Resource
{
    protected static ?string $model = Shipping::class;

    protected static UnitEnum|string|null $navigationGroup = 'Orders Details';

    public static function form(Schema $schema): Schema
    {
        return ShippingForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ShippingInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ShippingsTable::configure($table);
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
            'index' => ListShippings::route('/'),
            'create' => CreateShipping::route('/create'),
            'view' => ViewShipping::route('/{record}'),
            'edit' => EditShipping::route('/{record}/edit'),
        ];
    }
}
