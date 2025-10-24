<?php

namespace App\Filament\Resources\Shippings\Pages;

use App\Filament\Resources\Shippings\ShippingResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewShipping extends ViewRecord
{
    protected static string $resource = ShippingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
