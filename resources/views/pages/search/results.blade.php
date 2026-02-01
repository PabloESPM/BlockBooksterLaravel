@extends('layouts.app')

@section('title', 'Search Results: ' . $query)

@section('content')
    <!-- Header -->
    <div class="mb-8 border-b-4 border-black pb-4">
        <h1 class="text-4xl md:text-5xl font-black uppercase font-display">
            Search Results for "<span class="text-brand-blue">{{ $query }}</span>"
        </h1>
        <p class="text-lg font-bold text-gray-600 mt-2 uppercase">{{ $totalResults }} {{ Str::plural('result', $totalResults) }} found</p>
    </div>

    <!-- Search Bar -->
    <form action="{{ route('search') }}" method="GET" class="mb-12 flex gap-3">
        <div class="flex-1 relative">
            <input type="text" name="q" value="{{ $query }}" class="neo-input py-3 pl-12 w-full" 
                placeholder="Search books, authors, users, lists, genres, ISBN..." required>
            <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
        <button type="submit" class="neo-btn-primary px-6">Search</button>
    </form>

    @if($totalResults === 0)
        <!-- Empty State -->
        <x-card class="text-center py-16">
            <svg class="w-24 h-24 mx-auto mb-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <h2 class="text-3xl font-black uppercase mb-3">No Results Found</h2>
            <p class="text-gray-600 font-bold text-lg">Try different keywords or check your spelling</p>
        </x-card>
    @else
        <!-- Books Section -->
        @if($books->isNotEmpty())
            <section class="mb-12">
                <h2 class="text-2xl font-black uppercase mb-6 flex items-center gap-2 border-b-2 border-black pb-2">
                    <span class="w-3 h-3 bg-brand-blue border border-black"></span>
                    Books ({{ $books->count() }})
                </h2>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                    @foreach($books as $book)
                        <x-book-card 
                            :id="$book->isbn"
                            :title="$book->title"
                            :author="$book->authors->pluck('name')->join(', ') ?: 'Unknown'"
                            :cover="$book->cover_image ?? 'https://via.placeholder.com/300x450'"
                            :rating="0"
                        />
                    @endforeach
                </div>
            </section>
        @endif

        <!-- Authors Section -->
        @if($authors->isNotEmpty())
            <section class="mb-12">
                <h2 class="text-2xl font-black uppercase mb-6 flex items-center gap-2 border-b-2 border-black pb-2">
                    <span class="w-3 h-3 bg-brand-yellow border border-black"></span>
                    Authors ({{ $authors->count() }})
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($authors as $author)
                        <x-card class="flex items-center gap-4 hover:-translate-y-1 transition-transform">
                            <a href="{{ route('authors.show', $author->id) }}" class="flex items-center gap-4 flex-1">
                                <div class="w-16 h-16 bg-gray-200 rounded-full border-2 border-black overflow-hidden flex-shrink-0">
                                    <img src="{{ $author->photo ?? 'https://ui-avatars.com/api/?name=' . urlencode($author->name) . '&background=random' }}" 
                                        alt="{{ $author->name }}" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-bold uppercase text-sm hover:text-brand-blue truncate">{{ $author->name }}</h3>
                                    <p class="text-xs text-gray-500 font-bold">{{ $author->books_count }} {{ Str::plural('Book', $author->books_count) }}</p>
                                </div>
                            </a>
                        </x-card>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- Users Section -->
        @if($users->isNotEmpty())
            <section class="mb-12">
                <h2 class="text-2xl font-black uppercase mb-6 flex items-center gap-2 border-b-2 border-black pb-2">
                    <span class="w-3 h-3 bg-brand-pink border border-black"></span>
                    Users ({{ $users->count() }})
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($users as $user)
                        <x-card class="flex items-center gap-4 hover:-translate-y-1 transition-transform">
                            <a href="{{ route('users.show', $user->id) }}" class="flex items-center gap-4 flex-1">
                                <div class="w-16 h-16 bg-gray-200 rounded-full border-2 border-black overflow-hidden flex-shrink-0">
                                    <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random' }}" 
                                        alt="{{ $user->name }}" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-bold uppercase text-sm hover:text-brand-blue truncate">{{ $user->name }}</h3>
                                    <p class="text-xs text-gray-500 font-bold">{{ $user->followers_count }} {{ Str::plural('Follower', $user->followers_count) }}</p>
                                </div>
                            </a>
                        </x-card>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- Lists Section -->
        @if($lists->isNotEmpty())
            <section class="mb-12">
                <h2 class="text-2xl font-black uppercase mb-6 flex items-center gap-2 border-b-2 border-black pb-2">
                    <span class="w-3 h-3 bg-green-500 border border-black"></span>
                    Lists ({{ $lists->count() }})
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($lists as $list)
                        <x-card class="hover:-translate-y-1 transition-transform">
                            <a href="{{ route('lists.show', $list->id) }}">
                                <div class="flex items-center justify-between mb-4 pb-2 border-b-2 border-black/10">
                                    <h3 class="font-black uppercase text-lg hover:text-brand-blue truncate">{{ $list->name }}</h3>
                                    <span class="bg-green-100 text-green-800 text-xs font-bold uppercase px-2 py-0.5 border border-black flex-shrink-0 ml-2">Public</span>
                                </div>
                                <div class="flex items-center gap-2 mb-3">
                                    <div class="w-6 h-6 rounded-full bg-gray-200 border border-black overflow-hidden">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($list->user->name ?? 'User') }}&background=random" class="w-full h-full object-cover">
                                    </div>
                                    <span class="text-xs font-bold text-gray-500 uppercase">{{ $list->user->name ?? 'User' }}</span>
                                </div>
                                <div class="flex justify-between items-center text-xs font-bold text-gray-500 uppercase">
                                    <span>{{ $list->books_count }} {{ Str::plural('Book', $list->books_count) }}</span>
                                    <span>{{ $list->updated_at->diffForHumans() }}</span>
                                </div>
                            </a>
                        </x-card>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- Genres Section -->
        @if($genres->isNotEmpty())
            <section class="mb-12">
                <h2 class="text-2xl font-black uppercase mb-6 flex items-center gap-2 border-b-2 border-black pb-2">
                    <span class="w-3 h-3 bg-purple-500 border border-black"></span>
                    Genres ({{ $genres->count() }})
                </h2>
                <div class="flex flex-wrap gap-3">
                    @foreach($genres as $genre)
                        <a href="{{ route('books.index', ['genre' => $genre->id]) }}" 
                            class="neo-btn-secondary py-2 px-4 text-sm hover:bg-purple-500 hover:text-white transition-colors">
                            {{ $genre->name }}
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    @endif
@endsection
