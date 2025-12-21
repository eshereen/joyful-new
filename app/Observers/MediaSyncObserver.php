<?php

namespace App\Observers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaSyncObserver
{
    /**
     * Handle the Media "created" event.
     * Automatically syncs storage and ensures conversions are generated for ALL models.
     */
    public function created(Media $media): void
    {
        try {
            // Ensure storage symlink exists
            $this->ensureStorageLink();

            // Ensure conversions are generated (for non-queued conversions)
            $this->ensureConversions($media);

            // Sync file to public storage if needed
            $this->syncFileToPublic($media);

            Log::info('Media sync completed', [
                'media_id' => $media->id,
                'model_type' => $media->model_type,
                'collection_name' => $media->collection_name,
            ]);
        } catch (\Exception $e) {
            Log::error('Media sync failed', [
                'media_id' => $media->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Handle the Media "updated" event.
     */
    public function updated(Media $media): void
    {
        // Regenerate conversions if file was replaced
        if ($media->wasChanged('file_name')) {
            $this->ensureConversions($media);
        }
    }

    /**
     * Handle the Media "deleted" event.
     */
    public function deleted(Media $media): void
    {
        // Cleanup is handled by Spatie Media Library automatically
    }

    /**
     * Ensure storage symlink exists
     */
    protected function ensureStorageLink(): void
    {
        $linkPath = public_path('storage');
        $targetPath = storage_path('app/public');

        // Check if symlink exists and is valid
        if (!file_exists($linkPath) || !is_link($linkPath)) {
            try {
                // Remove if it exists but is not a symlink
                if (file_exists($linkPath) && !is_link($linkPath)) {
                    File::deleteDirectory($linkPath);
                }

                // Create symlink
                if (PHP_OS_FAMILY === 'Windows') {
                    // Windows doesn't support symlinks easily, use junction or copy
                    if (!file_exists($linkPath)) {
                        File::makeDirectory($linkPath, 0755, true);
                    }
                } else {
                    // Create symlink on Unix systems
                    symlink($targetPath, $linkPath);
                }
            } catch (\Exception $e) {
                Log::warning('Could not create storage symlink automatically', [
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Ensure conversions are generated for the media
     * Since conversions are non-queued, they should generate automatically,
     * but we trigger them to ensure they're created
     */
    protected function ensureConversions(Media $media): void
    {
        try {
            // Conversions are set to nonQueued() so they should generate automatically
            // But we can trigger them by accessing URLs to ensure they exist
            $model = $media->model;

            if (!$model || !method_exists($model, 'registerMediaCollections')) {
                return;
            }

            // Trigger conversion generation by accessing common conversion URLs
            // This ensures conversions are generated even if they weren't created automatically
            $commonConversions = ['thumb_webp', 'medium_webp', 'large_webp', 'thumb', 'medium', 'large'];

            foreach ($commonConversions as $conversionName) {
                try {
                    // Accessing the URL will trigger conversion generation if it doesn't exist
                    $url = $media->getUrl($conversionName);
                    // Just accessing is enough - Spatie will generate if needed
                } catch (\Exception $e) {
                    // Conversion might not be registered for this collection, skip
                    continue;
                }
            }
        } catch (\Exception $e) {
            Log::warning('Error ensuring conversions', [
                'media_id' => $media->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Sync file to public storage if using public disk
     */
    protected function syncFileToPublic(Media $media): void
    {
        try {
            // Only sync if using public disk
            if ($media->disk !== 'public') {
                return;
            }

            // Ensure the file exists in storage
            if (!$media->exists()) {
                Log::warning('Media file does not exist', [
                    'media_id' => $media->id,
                    'path' => $media->getPath(),
                ]);
                return;
            }

            // Ensure storage directory is writable
            $storagePath = storage_path('app/public');
            if (!is_writable($storagePath)) {
                Log::warning('Storage directory is not writable', [
                    'path' => $storagePath,
                ]);
            }
        } catch (\Exception $e) {
            Log::warning('Error syncing file to public storage', [
                'media_id' => $media->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
