@extends('layouts.app')

@section('content')
    @if($collection && !$product)
        {{-- Collection-only view (standalone bundle) --}}
        @livewire('product-show', ['product' => null, 'collection' => $collection->id])
    @else
        {{-- Product view (with optional collection context) --}}
        @livewire('product-show', ['product' => $product, 'collection' => $collection ? $collection->id : null])
    @endif
@endsection
