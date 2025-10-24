<?php

namespace App\Filament\Resources\Shippings\Pages;

use App\Filament\Resources\Shippings\ShippingResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditShipping extends EditRecord
{
    protected static string $resource = ShippingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
