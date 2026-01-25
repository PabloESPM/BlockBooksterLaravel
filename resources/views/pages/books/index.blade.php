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
        <form action="" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="text" name="title" placeholder="Title" class="neo-input bg-white">
            <input type="text" name="author" placeholder="Author" class="neo-input bg-white">
            <input type="text" name="isbn" placeholder="ISBN" class="neo-input bg-white">
            <button type="submit" class="neo-btn-primary md:col-span-1">
                Search
            </button>
        </form>
    </div>

    <div class="flex flex-col lg:flex-row gap-12">

        <!-- Sidebar Filters (Desktop) -->
        <aside class="w-full lg:w-72 flex-shrink-0 hidden lg:block">
            <x-card class="sticky top-24 space-y-8 bg-white" class="p-6">

                <!-- Sort By -->
                <div>
                    <h3 class="font-black text-sm mb-2 uppercase border-b-2 border-black pb-1">Sort By</h3>
                    <select name="sort" class="neo-input w-full text-sm">
                        <option value="popularity_desc">Popularity (High to Low)</option>
                        <option value="popularity_asc">Popularity (Low to High)</option>
                        <option value="rating_desc">Rating (High to Low)</option>
                        <option value="newest">Newest First</option>
                        <option value="oldest">Oldest First</option>
                    </select>
                </div>

                <!-- Genres -->
                <div>
                    <h3 class="font-black text-sm mb-2 uppercase border-b-2 border-black pb-1">Genre</h3>
                    <select name="genre" class="neo-input w-full text-sm">
                        <option value="">All Genres</option>
                        <!-- Assuming $genres is passed from controller, falling back to static list for view dev -->
                        @if(isset($genres))
                            @foreach($genres as $genre)
                                <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                            @endforeach
                        @else
                            @foreach(['Science Fiction', 'Mystery', 'Romance', 'Fantasy', 'Horror', 'Biography', 'History', 'Thriller', 'Young Adult'] as $genre)
                                <option value="{{ Str::slug($genre) }}">{{ $genre }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Language -->
                <div>
                    <h3 class="font-black text-sm mb-2 uppercase border-b-2 border-black pb-1">Language</h3>
                    <select name="language" class="neo-input w-full text-sm">
                        <option value="">All Languages</option>
                        <option value="en">English</option>
                        <option value="es">Spanish</option>
                        <option value="fr">French</option>
                        <option value="de">German</option>
                    </select>
                </div>

                <!-- Year Range -->
                <div>
                    <h3 class="font-black text-sm mb-2 uppercase border-b-2 border-black pb-1">Publication Year</h3>
                    <div class="flex gap-2">
                        <input type="number" name="year_from" placeholder="From" class="neo-input w-full text-sm px-2">
                        <input type="number" name="year_to" placeholder="To" class="neo-input w-full text-sm px-2">
                    </div>
                </div>

                <!-- Rating -->
                <div>
                    <h3
                        class="font-black text-sm mb-4 uppercase inline-block bg-brand-yellow px-2 py-0.5 border border-black">
                        Rating</h3>
                    <div class="space-y-2 font-bold text-sm">
                        @foreach([5, 4, 3, 2, 1] as $rating)
                            <label class="flex items-center space-x-3 cursor-pointer group">
                                <input type="radio" name="rating" value="{{ $rating }}"
                                    class="w-4 h-4 border-2 border-black rounded-full focus:ring-0 checked:bg-brand-yellow checked:text-black">
                                <span class="group-hover:translate-x-1 transition-transform flex items-center gap-1">
                                    {{ $rating }}+ <span class="text-brand-yellow text-lg leading-none">★</span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Tags -->
                <div>
                    <h3 class="font-black text-sm mb-4 uppercase border-b-2 border-black pb-1">Popular Tags</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['bestseller', 'award-winner', 'classic', 'dystopian', 'booktok', 'summer-read'] as $tag)
                            <button type="button"
                                class="text-xs font-bold uppercase border-2 border-black px-2 py-1 hover:bg-black hover:text-white transition-colors">
                                #{{ $tag }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <x-secondary-button class="w-full text-center text-sm">Reset Filters</x-secondary-button>
            </x-card>
        </aside>

        <!-- Main Grid -->
        <div class="flex-1">
            <div class="flex justify-between items-end mb-8">
                <h1 class="text-4xl font-display font-black uppercase flex items-center">
                    <span
                        class="bg-brand-yellow px-2 border-2 border-black mr-4 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] text-2xl">482</span>
                    Books
                </h1>
                <!-- Mobile Filter Toggle (Visible only on small screens) -->
                <button class="lg:hidden font-bold uppercase border-2 border-black px-4 py-2 hover:bg-gray-100">
                    Filters (+3)
                </button>
            </div>


            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @for($i = 0; $i < 12; $i++)
                    <div class="h-full">
                        <x-book-card id="{{$i}}" :title="'Book Title ' . $i" author="Author Name"
                            cover="https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&q=80&w=600"
                            :rating="rand(3, 5)" />
                    </div>
                @endfor
            </div>

            <!-- Pagination -->
            <div class="mt-16 flex justify-center gap-2">
                <button
                    class="w-10 h-10 border-2 border-black bg-white font-bold hover:bg-black hover:text-white transition-colors">
                    <</button>
                        <button
                            class="w-10 h-10 border-2 border-black bg-brand-blue text-white font-bold shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">1</button>
                        <button
                            class="w-10 h-10 border-2 border-black bg-white font-bold hover:bg-black hover:text-white transition-colors">2</button>
                        <button
                            class="w-10 h-10 border-2 border-black bg-white font-bold hover:bg-black hover:text-white transition-colors">3</button>
                        <span class="flex items-end font-bold px-2">...</span>
                        <button
                            class="w-10 h-10 border-2 border-black bg-white font-bold hover:bg-black hover:text-white transition-colors">42</button>
                        <button
                            class="w-10 h-10 border-2 border-black bg-white font-bold hover:bg-black hover:text-white transition-colors">></button>
            </div>
        </div>
    </div>
@endsection
