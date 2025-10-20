<?php

namespace App\Filament\Resources\PaymentTransactions;

use App\Filament\Resources\PaymentTransactions\Pages\CreatePaymentTransaction;
use App\Filament\Resources\PaymentTransactions\Pages\EditPaymentTransaction;
use App\Filament\Resources\PaymentTransactions\Pages\ListPaymentTransactions;
use App\Filament\Resources\PaymentTransactions\Pages\ViewPaymentTransaction;
use App\Filament\Resources\PaymentTransactions\Schemas\PaymentTransactionForm;
use App\Filament\Resources\PaymentTransactions\Schemas\PaymentTransactionInfolist;
use App\Filament\Resources\PaymentTransactions\Tables\PaymentTransactionsTable;
use App\Models\PaymentTransaction;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PaymentTransactionResource extends Resource
{
    protected static ?string $model = PaymentTransaction::class;
    protected static UnitEnum|string|null $navigationGroup = 'Orders Details';

    public static function form(Schema $schema): Schema
    {
        return PaymentTransactionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PaymentTransactionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PaymentTransactionsTable::configure($table);
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
            'index' => ListPaymentTransactions::route('/'),
            'create' => CreatePaymentTransaction::route('/create'),
            'view' => ViewPaymentTransaction::route('/{record}'),
            'edit' => EditPaymentTransaction::route('/{record}/edit'),
        ];
    }
}
