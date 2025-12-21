@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-40">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto text-center">
            @php
                $currencyService = app(\App\Services\CountryCurrencyService::class);
                $orderCurrency = $order->currency ?? 'USD';
                $orderSymbol = $currencyService->getCurrencySymbol($orderCurrency);
            @endphp

            <!-- Success Icon -->
            <div class="mb-8">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100">
                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>

            <!-- Thank You Message -->
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Thank You for Your Order!</h1>
            @if($order->payment_method === 'instapay')
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-blue-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-lg font-semibold text-blue-900 mb-2">We are waiting for the screen shot of your payment</p>
                            <p class="text-sm text-blue-800 mb-3">
                                Please send the payment screenshot via WhatsApp to <strong>{{ config('app.instapay_phone', '+20 100 000 0000') }}</strong>
                            </p>
                            <a
                                href="https://wa.me/{{ str_replace([' ', '+', '-'], '', config('app.instapay_phone', '+201000000000')) }}?text=Order%20{{ $order->order_number }}%20Payment%20Screenshot"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors text-sm font-medium"
                            >
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                </svg>
                                Send Payment Screenshot
                            </a>
                        </div>
                    </div>
                </div>
            @endif
            <p class="text-lg text-gray-600 mb-8">
                Your order has been successfully placed. We've sent a confirmation email to <strong>{{ $order->email }}</strong>
            </p>

            <!-- Order Details -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8 text-left">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Order Details</h2>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <span class="text-gray-600">Order Number:</span>
                        <span class="font-medium text-gray-900">{{ $order->order_number }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Order Date:</span>
                        <span class="font-medium text-gray-900">{{ $order->created_at->format('M d, Y') }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <span class="text-gray-600">Total Amount:</span>
                        <span class="font-medium text-gray-900">{{ $orderSymbol }}{{ number_format($order->total_amount, 2) }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Payment Method:</span>
                        <span class="font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-gray-600">Status:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-dark-brown">
                            {{ ucfirst($order->status->value) }}
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-600">Payment Status:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-dark-brown">
                            {{ ucfirst($order->payment_status->value) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8 text-left">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Order Items</h2>



                @foreach($order->items as $item)
                <div class="flex items-center justify-between py-3 border-b border-gray-200 last:border-b-0">
                    <div class="flex items-center">
                        @if($item->product)
                            <img src="{{ $item->product->getFirstMediaUrl('main_image','small_webp') }}"
                                 alt="{{ $item->product->name }}"
                                 class="w-12 h-12 object-cover rounded mr-3">
                        @endif
                        <div>
                            <h3 class="font-medium text-gray-900">{{ $item->product->name ?? 'Product' }}</h3>
                            @if($item->variant)
                                <p class="text-sm text-gray-600">{{ $item->variant->wick_type }}, {{ $item->variant->size }}gm</p>
                            @endif
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-gray-900">Qty: {{ $item->quantity }}</p>
                        @php
                            $itemPrice = $item->price;
                            if ($orderCurrency !== 'USD') {
                                $itemPrice = $currencyService->convertFromUSD($item->price, $orderCurrency);
                            }
                        @endphp
                        <p class="font-medium text-gray-900">{{ $orderSymbol }}{{ number_format($itemPrice, 2) }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('products.index') }}"
                   class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-dark-brown hover:bg-yellow-700 transition-colors">
                    Continue Shopping
                </a>

                @if($order->is_guest)
                    <a href="{{ route('checkout.confirmation', $order) }}?token={{ $order->guest_token }}"
                       class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        View Order Details
                    </a>
                @else
                    <a href="{{ route('checkout.confirmation', $order) }}"
                       class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        View Order Details
                    </a>
                @endif
            </div>



            <!-- Additional Info -->
            <div class="mt-8 text-sm text-gray-500">
                <p>If you have any questions about your order, please contact our customer support.</p>
                <p class="mt-2">Email: support@Joyfulegy.com </p>
            </div>
        </div>
    </div>
</div>
@endsection
<!--facebook pixel-->
 @push('scripts')
 <script>
  @php
    // Calculate the correct values for Facebook Pixel Purchase tracking
    $fbPixelContentIds = [];
    $fbPixelNumItems = 0;
    $fbPixelValue = 0;
    
    // Extract product IDs from order items
    if (isset($order) && $order->items && $order->items->isNotEmpty()) {
        // Get product IDs, filtering out null values (in case of collections)
        $fbPixelContentIds = $order->items
            ->pluck('product_id')
            ->filter()
            ->unique()
            ->values()
            ->toArray();
        
        // Count total number of items
        $fbPixelNumItems = $order->items->sum('quantity');
    }
    
    // Use the order's total amount
    if (isset($order) && isset($order->total_amount)) {
        $fbPixelValue = $order->total_amount;
    }
    
    // Get currency from order, fallback to EGP
    $fbPixelCurrency = $order->currency ?? 'EGP';
    
    // Get order number for transaction ID
    $fbPixelTransactionId = $order->order_number ?? $order->id ?? '';
  @endphp
  
  fbq('track', 'Purchase', {
    content_ids: @json($fbPixelContentIds),
    content_type: 'product',
    num_items: {{ $fbPixelNumItems }},
    value: {{ $fbPixelValue }},
    currency: '{{ $fbPixelCurrency }}',
    transaction_id: '{{ $fbPixelTransactionId }}'
  });
</script>
@endpush

