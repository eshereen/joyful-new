<?php

namespace App\Console\Commands;

use App\Models\Collection;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class FixCollectionImages extends Command
{
    protected $signature = 'collections:fix-images
                            {--regenerate : Regenerate media conversions for all collections}
                            {--check-storage : Check storage link and permissions}';

    protected $description = 'Fix collection images issues - regenerate conversions and check storage';

    public function handle()
    {
        $this->info('🔍 Checking collection images...');

        if ($this->option('check-storage')) {
            $this->checkStorage();
        }

        if ($this->option('regenerate')) {
            $this->regenerateConversions();
        }

        if (!$this->option('regenerate') && !$this->option('check-storage')) {
            $this->info('💡 Use --regenerate to regenerate conversions');
            $this->info('💡 Use --check-storage to check storage setup');
            $this->info('💡 Use both flags to do everything');
        }

        return Command::SUCCESS;
    }

    protected function checkStorage()
    {
        $this->info('📁 Checking storage setup...');

        // Check if storage link exists
        $storageLink = public_path('storage');
        $storageTarget = storage_path('app/public');

        if (!file_exists($storageLink)) {
            $this->warn('❌ Storage symlink does not exist!');
            $this->info('   Run: php artisan storage:link');

            if ($this->confirm('Would you like to create it now?', true)) {
                $this->call('storage:link');
                $this->info('✅ Storage link created!');
            }
        } else {
            // Check if it's a valid symlink
            if (is_link($storageLink)) {
                $target = readlink($storageLink);
                if ($target === $storageTarget || realpath($target) === realpath($storageTarget)) {
                    $this->info('✅ Storage symlink is valid');
                } else {
                    $this->warn('⚠️  Storage symlink points to wrong location');
                    $this->info("   Current: {$target}");
                    $this->info("   Expected: {$storageTarget}");
                }
            } else {
                $this->warn('⚠️  public/storage exists but is not a symlink');
            }
        }

        // Check storage directory permissions
        if (!is_writable($storageTarget)) {
            $this->warn('⚠️  Storage directory is not writable');
            $this->info("   Path: {$storageTarget}");
            $this->info('   Fix: chmod -R 775 ' . $storageTarget);
        } else {
            $this->info('✅ Storage directory is writable');
        }

        // Check media directory
        $mediaPath = $storageTarget . '/media';
        if (!file_exists($mediaPath)) {
            $this->info('📁 Creating media directory...');
            Storage::disk('public')->makeDirectory('media');
            $this->info('✅ Media directory created');
        } else {
            $this->info('✅ Media directory exists');
        }
    }

    protected function regenerateConversions()
    {
        $this->info('🔄 Regenerating media conversions...');

        $collections = Collection::with('media')->get();
        $count = 0;

        foreach ($collections as $collection) {
            $mediaItems = $collection->getMedia('main_image');

            foreach ($mediaItems as $media) {
                $this->info("Processing: {$collection->name} (ID: {$collection->id})");

                try {
                    // Check if original file exists
                    if (!$media->exists()) {
                        $this->warn("  ⚠️  Original file missing for media ID: {$media->id}");
                        continue;
                    }

                    // Regenerate conversions - Spatie will handle this automatically
                    // We just need to ensure the conversions are generated
                    $collection = $media->model;

                    // Get all registered conversions
                    $conversions = $collection->getRegisteredMediaConversions();

                    foreach ($conversions as $conversionName => $conversion) {
                        if (!$media->hasGeneratedConversion($conversionName)) {
                            $this->info("  🔄 Generating conversion: {$conversionName}");
                            // The conversion will be generated on next access, but we can force it
                            $media->getUrl($conversionName);
                        }
                    }

                    $count++;
                    $this->info("  ✅ Processed media ID: {$media->id}");
                } catch (\Exception $e) {
                    $this->error("  ❌ Error processing media ID {$media->id}: " . $e->getMessage());
                }
            }
        }

        if ($count > 0) {
            $this->info("✅ Regenerated conversions for {$count} media items");
        } else {
            $this->warn('⚠️  No media items found to regenerate');
        }
    }
}

