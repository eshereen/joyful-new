<x-mail::message>
    @php
        $customerName = optional($order->customer)->full_name
            ?? trim(($order->first_name ?? '') . ' ' . ($order->last_name ?? ''))
            ?: 'Customer';
    @endphp
    Hello {{ $customerName }}

    Your order has been created successfully.
    Order ID: {{ $order->id }}
    Order Date: {{ optional($order->created_at)->format('d-m-Y') }}
    Order Status: {{ $order->status->value }}
    Order Total: {{ $order->total_amount }} EGP

    @if($order->relationLoaded('items') || $order->items()->exists())
        @foreach ($order->items as $item)
            {{ optional($item->product)->name ?? 'Item' }} - {{ $item->quantity }} - {{ $item->price }} EGP
        @endforeach
    @endif

<x-mail::button :url="config('app.url')">
Visit our website
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
