@extends('layouts.app')

@section('title', 'My Reviews')

@section('content')
    <div x-data class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar -->
        @include('pages.dashboard.partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1">
            <header class="mb-8 border-b-4 border-black pb-4">
                <h1 class="text-3xl font-black uppercase font-display">My Reviews</h1>
                <p class="text-gray-600 font-bold mt-1">Manage your book ratings</p>
            </header>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($reviews as $review)
                    <x-review-card :review="$review" :showBook="true" :showActions="true" />
                @empty
                    <div
                        class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-12 border-2 border-dashed border-gray-300 bg-gray-50">
                        <p class="text-xl font-bold uppercase text-gray-400 mb-2">No reviews yet</p>
                        <a href="{{ route('books.index') }}" class="neo-btn-primary inline-block text-sm">Browse Books</a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection