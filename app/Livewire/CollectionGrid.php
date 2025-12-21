<?php

namespace App\Livewire;

use App\Contracts\CartServiceContract;
use App\Models\Collection;
use Exception;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class CollectionGrid extends Component
{
    /**
     * The collections to display.
     *
     * @var \Illuminate\Support\Collection<int,\App\Models\Collection>
     */
    public SupportCollection $collections;

    protected CartServiceContract $cartService;

    /**
     * Create the component instance.
     */
    public function mount($collections = null): void
    {
        if ($collections instanceof SupportCollection) {
            $this->collections = $collections;
        } elseif (is_array($collections)) {
            $this->collections = collect($collections);
        } else {
            $this->collections = Collection::with([
                'media' => fn ($query) => $query->where('collection_name', 'main_image'),
            ])
                ->withCount([
                    'products as active_products_count' => fn ($query) => $query->where('products.active', true),
                ])
                ->where('active', true)
                ->orderByDesc('created_at')
                ->take(6)
                ->get();
        }
    }

    public function boot(CartServiceContract $cartService): void
    {
        $this->cartService = $cartService;
    }

    /**
     * Handle adding the collection to the cart.
     */
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

            $this->cartService->addCollection($collection);

            $this->dispatch('cartUpdated');
            $this->dispatch('showNotification', [
                'message' => "Collection '{$collection->name}' added to cart.",
                'type' => 'success',
            ]);
        } catch (Exception $e) {
            Log::error('CollectionGrid: Unable to add collection to cart', [
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
        return view('livewire.collection-grid', [
            'collections' => $this->collections,
        ]);
    }
}
