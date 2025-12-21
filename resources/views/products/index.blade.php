@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8 text-center">All Products</h1>

    @if($result && $result->count() > 0)
        <!-- Products Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            @foreach($result as $product)
                <div class="bg-white overflow-hidden transition hover:shadow-lg">
                    <!-- Product Image -->
                    <div class="relative aspect-[4/5] overflow-hidden group">
                        <a href="{{ route('products.show', $product->slug) }}">
                            @php
                                $mainImage = $product->getFirstMediaUrl('main_image');
                                if (empty($mainImage)) {
                                    $mainImage = 'https://via.placeholder.com/400x500?text=No+Image';
                                }
                                
                                // Get gallery image for hover effect
                                $galleryImages = $product->getMedia('product_images');
                                $galleryImage = null;
                                foreach($galleryImages as $img) {
                                    $url = $img->getUrl();
                                    if($url && $url !== $mainImage) {
                                        $galleryImage = $url;
                                        break;
                                    }
                                }
                            @endphp
                            
                            <!-- Main Image -->
                            <img src="{{ $mainImage }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-full object-cover transition-opacity duration-300"
                                 style="object-position: top;">
                            
                            <!-- Gallery Image (Hover) -->
                            @if($galleryImage)
                                <img src="{{ $galleryImage }}" 
                                     alt="{{ $product->name }}" 
                                     class="absolute inset-0 w-full h-full object-cover opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                                     style="object-position: top;">
                            @endif
                        </a>
                        
                        <!-- Badges -->
                        @if($product->featured)
                            <span class="absolute top-2 left-2 bg-yellow-500 text-white text-xs font-bold px-2 py-1 rounded uppercase">
                                Featured
                            </span>
                        @endif
                        
                        @if($product->variants && $product->variants->first() && $product->variants->first()->compare_price > 0)
                            <span class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded uppercase">
                                Sale
                            </span>
                        @endif
                    </div>

                    <!-- Product Info -->
                    <div class="p-4">
                        <a href="{{ route('products.show', $product->slug) }}" 
                           class="block text-sm font-semibold text-gray-800 hover:text-red-600 mb-2 line-clamp-2">
                            {{ $product->name }}
                        </a>
                        
                        @if($product->category)
                            <p class="text-xs text-gray-500 mb-2">{{ $product->category->name }}</p>
                        @endif
                        
                        <!-- Price -->
                        @php
                            $variant = $product->variants->first();
                            $displayPrice = $variant->price ?? 0;
                            $displayComparePrice = $variant->compare_price ?? 0;
                        @endphp
                        
                        <div class="flex items-center gap-2">
                            <span class="text-lg font-bold text-gray-900">
                                ${{ number_format($displayPrice, 2) }}
                            </span>
                            
                            @if($displayComparePrice > 0)
                                <span class="text-sm text-gray-500 line-through">
                                    ${{ number_format($displayComparePrice, 2) }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $result->links() }}
        </div>
    @else
        <div class="text-center py-16">
            <p class="text-gray-500 text-lg">No products available at the moment.</p>
        </div>
    @endif
</div>
@endsection
