<?php

namespace App\Filament\Resources\LoyaltyTransactions;

use App\Filament\Resources\LoyaltyTransactions\Pages\CreateLoyaltyTransaction;
use App\Filament\Resources\LoyaltyTransactions\Pages\EditLoyaltyTransaction;
use App\Filament\Resources\LoyaltyTransactions\Pages\ListLoyaltyTransactions;
use App\Filament\Resources\LoyaltyTransactions\Pages\ViewLoyaltyTransaction;
use App\Filament\Resources\LoyaltyTransactions\Schemas\LoyaltyTransactionForm;
use App\Filament\Resources\LoyaltyTransactions\Schemas\LoyaltyTransactionInfolist;
use App\Filament\Resources\LoyaltyTransactions\Tables\LoyaltyTransactionsTable;
use App\Models\LoyaltyTransaction;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class LoyaltyTransactionResource extends Resource
{
    protected static ?string $model = LoyaltyTransaction::class;

protected static UnitEnum|string|null $navigationGroup = 'Orders Details';

    public static function form(Schema $schema): Schema
    {
        return LoyaltyTransactionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return LoyaltyTransactionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LoyaltyTransactionsTable::configure($table);
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
            'index' => ListLoyaltyTransactions::route('/'),
            'create' => CreateLoyaltyTransaction::route('/create'),
            'view' => ViewLoyaltyTransaction::route('/{record}'),
            'edit' => EditLoyaltyTransaction::route('/{record}/edit'),
        ];
    }
}
