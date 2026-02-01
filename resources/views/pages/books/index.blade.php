@extends('layouts.app')

@section('title', 'Browse Books')

@section('content')
    <!-- Top Bar: Advanced Search -->
    <div class="neo-card p-6 mb-12 bg-gray-100">
        <h2 class="text-xl font-black uppercase mb-4 flex items-center gap-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            Advanced Search
        </h2>
        <form action="{{ route('books.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Preserve other filters if present, or maybe just let advanced search start fresh? 
                 Let's keep it simple: Advanced search starts a new search, but we persist input values. -->
            <input type="text" name="title" value="{{ request('title') }}" placeholder="Title" class="neo-input bg-white">
            <input type="text" name="author" value="{{ request('author') }}" placeholder="Author" class="neo-input bg-white">
            <input type="text" name="isbn" value="{{ request('isbn') }}" placeholder="ISBN" class="neo-input bg-white">
            <button type="submit" class="neo-btn-primary md:col-span-1">
                Search
            </button>
        </form>
    </div>

    <div class="flex flex-col lg:flex-row gap-12">

        <!-- Sidebar Filters (Desktop) -->
        <aside class="w-full lg:w-72 flex-shrink-0 hidden lg:block">
            <x-card class="sticky top-24 space-y-8 bg-white" class="p-6">
                <form action="{{ route('books.index') }}" method="GET">
                    
                    <!-- Preserve search params from Advanced Search -->
                    @if(request('title')) <input type="hidden" name="title" value="{{ request('title') }}"> @endif
                    @if(request('author')) <input type="hidden" name="author" value="{{ request('author') }}"> @endif
                    @if(request('isbn')) <input type="hidden" name="isbn" value="{{ request('isbn') }}"> @endif

                <!-- Sort By -->
                <div class="mb-6">
                    <h3 class="font-black text-sm mb-2 uppercase border-b-2 border-black pb-1">Sort By</h3>
                    <select name="sort" class="neo-input w-full text-sm">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                        <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>Title A-Z</option>
                        <option value="title_desc" {{ request('sort') == 'title_desc' ? 'selected' : '' }}>Title Z-A</option>
                    </select>
                </div>

                <!-- Genres -->
                <div class="mb-6">
                    <h3 class="font-black text-sm mb-2 uppercase border-b-2 border-black pb-1">Genre</h3>
                    <select name="genre" class="neo-input w-full text-sm">
                        <option value="">All Genres</option>
                        @foreach($genres as $genre)
                            <option value="{{ $genre->id }}" {{ request('genre') == $genre->id ? 'selected' : '' }}>{{ $genre->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Author Country -->
                <div class="mb-6">
                    <h3 class="font-black text-sm mb-2 uppercase border-b-2 border-black pb-1">Author Country</h3>
                    <select name="country" class="neo-input w-full text-sm">
                        <option value="">All Countries</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}" {{ request('country') == $country->id ? 'selected' : '' }}>
                                {{ $country->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Language -->
                <div class="mb-6">
                    <h3 class="font-black text-sm mb-2 uppercase border-b-2 border-black pb-1">Language</h3>
                    <select name="language" class="neo-input w-full text-sm">
                        <option value="">All Languages</option>
                        <option value="en" {{ request('language') == 'en' ? 'selected' : '' }}>English</option>
                        <option value="es" {{ request('language') == 'es' ? 'selected' : '' }}>Spanish</option>
                        <option value="fr" {{ request('language') == 'fr' ? 'selected' : '' }}>French</option>
                        <option value="de" {{ request('language') == 'de' ? 'selected' : '' }}>German</option>
                    </select>
                </div>

                <!-- Page Range -->
                <div class="mb-6">
                    <h3 class="font-black text-sm mb-2 uppercase border-b-2 border-black pb-1">Page Range</h3>
                    <div class="flex gap-2">
                        <input type="number" name="pages_from" value="{{ request('pages_from') }}" placeholder="Min" class="neo-input w-full text-sm px-2">
                        <input type="number" name="pages_to" value="{{ request('pages_to') }}" placeholder="Max" class="neo-input w-full text-sm px-2">
                    </div>
                </div>

                <!-- Year Range -->
                <div class="mb-6">
                    <h3 class="font-black text-sm mb-2 uppercase border-b-2 border-black pb-1">Publication Year</h3>
                    <div class="flex gap-2">
                        <input type="number" name="year_from" value="{{ request('year_from') }}" placeholder="From" class="neo-input w-full text-sm px-2">
                        <input type="number" name="year_to" value="{{ request('year_to') }}" placeholder="To" class="neo-input w-full text-sm px-2">
                    </div>
                </div>

                <!-- Rating -->
                <div class="mb-6">
                    <h3
                        class="font-black text-sm mb-4 uppercase inline-block bg-brand-yellow px-2 py-0.5 border border-black">
                        Rating</h3>
                    <div class="space-y-2 font-bold text-sm">
                        @foreach([5, 4, 3, 2, 1] as $rating)
                            <label class="flex items-center space-x-3 cursor-pointer group">
                                <input type="radio" name="rating" value="{{ $rating }}" {{ request('rating') == $rating ? 'checked' : '' }}
                                    class="w-4 h-4 border-2 border-black rounded-full focus:ring-0 checked:bg-brand-yellow checked:text-black">
                                <span class="group-hover:translate-x-1 transition-transform flex items-center gap-1">
                                    {{ $rating }}+ <span class="text-brand-yellow text-lg leading-none">★</span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
                
                <button type="submit" class="neo-btn-primary w-full text-center text-sm mb-4">Apply Filters</button>
                <a href="{{ route('books.index') }}" class="neo-btn-secondary w-full block text-center text-sm">Reset Filters</a>
                
                </form>
            </x-card>
        </aside>

        <!-- Main Grid -->
        <div class="flex-1">
            <div class="flex justify-between items-end mb-8">
                <h1 class="text-4xl font-display font-black uppercase flex items-center">
                    <span
                        class="bg-brand-yellow px-2 border-2 border-black mr-4 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] text-2xl">{{ $books->total() }}</span>
                    Books
                </h1>
                <!-- Mobile Filter Toggle (Visible only on small screens) -->
                <button class="lg:hidden font-bold uppercase border-2 border-black px-4 py-2 hover:bg-gray-100">
                    Filters (+3)
                </button>
            </div>


            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($books as $book)
                    <div class="h-full">
                        <x-book-card id="{{$book->isbn}}" :title="$book->title"
                            :author="$book->authors->first()->name ?? 'Unknown Author'"
                            :cover="$book->cover ?? 'https://via.placeholder.com/600x900'"
                            :rating="4.5" />
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-xl font-bold uppercase text-gray-500">No books found.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <!-- Pagination -->
            @if ($books->hasPages())
                <div class="mt-16 flex justify-center items-center gap-2">

                    {{-- Previous --}}
                    @if ($books->onFirstPage())
                        <span
                            class="w-10 h-10 flex items-center justify-center border-2 border-black bg-gray-100 font-bold text-gray-400 cursor-not-allowed">
                &lt;
            </span>
                    @else
                        <a href="{{ $books->previousPageUrl() }}"
                           class="w-10 h-10 flex items-center justify-center border-2 border-black bg-white font-bold hover:bg-black hover:text-white transition-colors">
                            &lt;
                        </a>
                    @endif

                    {{-- Page Numbers --}}
                    @foreach ($books->getUrlRange(
                        max(1, $books->currentPage() - 2),
                        min($books->lastPage(), $books->currentPage() + 2)
                    ) as $page => $url)

                        @if ($page == $books->currentPage())
                            <span
                                class="w-10 h-10 flex items-center justify-center border-2 border-black bg-brand-blue text-white font-bold shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                    {{ $page }}
                </span>
                        @else
                            <a href="{{ $url }}"
                               class="w-10 h-10 flex items-center justify-center border-2 border-black bg-white font-bold hover:bg-black hover:text-white transition-colors">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach

                    {{-- Ellipsis + Last Page --}}
                    @if ($books->currentPage() + 2 < $books->lastPage())
                        <span class="flex items-end font-bold px-2">...</span>

                        <a href="{{ $books->url($books->lastPage()) }}"
                           class="w-10 h-10 flex items-center justify-center border-2 border-black bg-white font-bold hover:bg-black hover:text-white transition-colors">
                            {{ $books->lastPage() }}
                        </a>
                    @endif

                    {{-- Next --}}
                    @if ($books->hasMorePages())
                        <a href="{{ $books->nextPageUrl() }}"
                           class="w-10 h-10 flex items-center justify-center border-2 border-black bg-white font-bold hover:bg-black hover:text-white transition-colors">
                            &gt;
                        </a>
                    @else
                        <span
                            class="w-10 h-10 flex items-center justify-center border-2 border-black bg-gray-100 font-bold text-gray-400 cursor-not-allowed">
                &gt;
            </span>
                    @endif

                </div>
            @endif

        </div>
    </div>
@endsection
