<?php

namespace App\Filament\Resources\LoyaltyTransactions\Pages;

use App\Filament\Resources\LoyaltyTransactions\LoyaltyTransactionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLoyaltyTransaction extends CreateRecord
{
    protected static string $resource = LoyaltyTransactionResource::class;
}
