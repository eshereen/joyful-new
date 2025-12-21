<?php

namespace App\Filament\Resources\ReviewResources\Pages;

use App\Filament\Resources\ReviewResources\ReviewResource;
use Filament\Resources\Pages\CreateRecord;

class CreateReviewResource extends CreateRecord
{
    protected static string $resource = ReviewResource::class;
}
