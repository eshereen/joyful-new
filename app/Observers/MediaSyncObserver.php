<?php

namespace App\Observers;

use Illuminate\Support\Facades\Artisan;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


class MediaSyncObserver
{
    /**
     * Handle the Media "created" event.
     */
    public function created(Media $media): void
    {
           // Only sync when admin uploads (for Products or Collections)
           $model = $media->model_type;

           if (in_array($model, [
               \App\Models\Product::class,
               \App\Models\Collection::class,
           ])) {
               // Run your manual sync command
               Artisan::call('storage:sync');
           }
    }

    /**
     * Handle the Media "updated" event.
     */
    public function updated(Media $media): void
    {
        //
    }

    /**
     * Handle the Media "deleted" event.
     */
    public function deleted(Media $media): void
    {
        //
    }

    /**
     * Handle the Media "restored" event.
     */
    public function restored(Media $media): void
    {
        //
    }

    /**
     * Handle the Media "force deleted" event.
     */
    public function forceDeleted(Media $media): void
    {
        //
    }
}
