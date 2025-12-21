<?php

namespace App\Livewire;

use App\Contracts\CartServiceContract;
use App\Models\Collection;
use Exception;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class CollectionsGrid extends Component
{
    public SupportCollection $collections;

    public string $search = '';

    protected CartServiceContract $cartService;

    public function boot(CartServiceContract $cartService): void
    {
        $this->cartService = $cartService;
    }

    public function mount($collections = null): void
    {
        if ($collections instanceof SupportCollection) {
            // Ensure products are loaded for each collection with active filter
            foreach ($collections as $collection) {
                if (!$collection->relationLoaded('products')) {
                    $collection->load(['products' => fn ($q) => $q->where('active', true)]);
                }
            }
            $this->collections = $collections;
        } elseif (is_array($collections)) {
            $this->collections = collect($collections);
        } else {
            $this->loadCollections();
        }
    }

    public function loadCollections(): void
    {
        $this->collections = Collection::where('active', true)
            ->with([
                'media' => fn ($query) => $query->where('collection_name', 'main_image'),
                'products' => fn ($query) => $query->where('active', true),
            ])
            ->withCount('products')
            ->orderBy('name')
            ->get();
    }

    public function updatingSearch(): void
    {
        $this->loadCollections();
    }

    public function addCollectionToCart(int $collectionId): void
    {
        try {
            $collection = $this->collections->firstWhere('id', $collectionId)
                ?? Collection::with('products.variants')->find($collectionId);

            if (!$collection) {
                $this->dispatch('showNotification', [
                    'message' => 'Collection not found.',
                    'type' => 'error',
                ]);

                return;
            }

            if (($collection->stock ?? 0) <= 0) {
                $this->dispatch('showNotification', [
                    'message' => "Collection '{$collection->name}' is out of stock.",
                    'type' => 'error',
                ]);

                return;
            }

            $result = $this->cartService->addCollection($collection);

            $this->dispatch('cartUpdated');
            $this->dispatch('showNotification', [
                'message' => "Collection '{$collection->name}' added to cart.",
                'type' => 'success',
            ]);
        } catch (Exception $e) {
            Log::error('CollectionsGrid: unable to add collection to cart', [
                'collection_id' => $collectionId,
                'error' => $e->getMessage(),
            ]);

            $this->dispatch('showNotification', [
                'message' => $e->getMessage() ?: 'Could not add collection to cart.',
                'type' => 'error',
            ]);
        }
    }

    public function render()
    {
        $filteredCollections = $this->collections;

        if ($this->search) {
            $filteredCollections = $this->collections
                ->filter(function ($collection) {
                    return str_contains(
                        mb_strtolower($collection->name),
                        mb_strtolower($this->search)
                    );
                })
                ->values();
        }

        return view('livewire.collections-grid', [
            'filteredCollections' => $filteredCollections,
        ]);
    }
}
