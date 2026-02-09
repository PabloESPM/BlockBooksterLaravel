@extends('layouts.app')

@section('title', 'My Reviews')

@section('content')
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar -->
        @include('pages.dashboard.partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1">
            <header class="mb-8 border-b-4 border-black pb-4">
                <h1 class="text-3xl font-black uppercase font-display">My Reviews</h1>
                <p class="text-gray-600 font-bold mt-1">Manage your book ratings</p>
            </header>

            <div class="space-y-6">
                @forelse($reviews as $review)
                    <x-card class="flex flex-col md:flex-row gap-6">
                        <div class="w-20 flex-shrink-0">
                            <a href="{{ route('books.show', $review->book->isbn) }}">
                                <img src="{{ $review->book->cover ?? 'https://via.placeholder.com/200x300' }}"
                                    alt="{{ $review->book->title }}"
                                    class="w-full border-2 border-black shadow-[2px_2px_0px_#000] hover:scale-105 transition-transform">
                            </a>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between items-start mb-2">
                                <a href="{{ route('books.show', $review->book->isbn) }}"
                                    class="font-bold uppercase text-lg leading-tight hover:text-brand-blue">{{ $review->book->title }}</a>
                                <div class="flex text-brand-yellow text-xs gap-0.5">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <span>★</span>
                                        @else
                                            <span class="text-gray-300">★</span>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                            <p class="text-sm italic text-gray-700 mb-4 bg-gray-50 p-3 border border-gray-200">
                                "{{ $review->comment }}"
                            </p>

                            <div class="flex justify-between items-center border-t-2 border-black/10 pt-3">
                                <span class="text-xs font-bold text-gray-500 uppercase">Written on
                                    {{ $review->created_at->format('M d, Y') }}</span>
                                <div class="flex gap-2">
                                    <button @click="$dispatch('open-edit-review-modal', { 
                                                    reviewId: '{{ $review->id }}', 
                                                    rating: {{ $review->rating }}, 
                                                    comment: '{{ addslashes($review->comment) }}',
                                                    updateUrl: '{{ route('reviews.update', $review) }}'
                                                })" class="text-xs font-black uppercase hover:text-brand-blue underline">
                                        Edit
                                    </button>
                                    <button class="text-xs font-black uppercase hover:text-red-600 underline">Delete</button>
                                </div>
                            </div>
                        </div>
                    </x-card>
                @empty
                    <div class="text-center py-12 border-2 border-dashed border-gray-300 bg-gray-50">
                        <p class="text-xl font-bold uppercase text-gray-400 mb-2">No reviews yet</p>
                        <a href="{{ route('books.index') }}" class="neo-btn-primary inline-block text-sm">Browse Books</a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection