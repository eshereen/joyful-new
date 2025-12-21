@extends('layouts.app')
@section('content')
  <!-- Hero -->
  <section class="relative">
    <img src="{{ asset('imgs/review.jpeg') }}" alt="Hero" class="w-full h-64 md:h-96 object-cover">
  </section>

  <!-- Content -->
  <div class="max-w-7xl mx-auto px-4 py-10 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8 my-8">

    <!-- Sidebar Filters -->
    <aside class="space-y-6">
      @livewire('collection-filters', ['collectionSlug' => $collectionSlug])
    </aside>

    <!-- Product Grid -->
    <main class="md:col-span-3 ">
      <!-- Products -->
      @livewire('collection-products', ['collectionSlug' => $collectionSlug])
    </main>
  </div>

@endsection
