<?php

namespace App\Filament\Resources\ReviewResources\Pages;

use App\Filament\Resources\ReviewResources\ReviewResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewReviewResource extends ViewRecord
{
    protected static string $resource = ReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
