@props(['genres', 'countries'])

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
                        <option value="{{ $genre->id }}" {{ request('genre') == $genre->id ? 'selected' : '' }}>
                            {{ $genre->name }}</option>
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
                    <input type="number" name="pages_from" value="{{ request('pages_from') }}" placeholder="Min"
                        class="neo-input w-full text-sm px-2">
                    <input type="number" name="pages_to" value="{{ request('pages_to') }}" placeholder="Max"
                        class="neo-input w-full text-sm px-2">
                </div>
            </div>

            <!-- Year Range -->
            <div class="mb-6">
                <h3 class="font-black text-sm mb-2 uppercase border-b-2 border-black pb-1">Publication Year</h3>
                <div class="flex gap-2">
                    <input type="number" name="year_from" value="{{ request('year_from') }}" placeholder="From"
                        class="neo-input w-full text-sm px-2">
                    <input type="number" name="year_to" value="{{ request('year_to') }}" placeholder="To"
                        class="neo-input w-full text-sm px-2">
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
            <a href="{{ route('books.index') }}" class="neo-btn-secondary w-full block text-center text-sm">Reset
                Filters</a>

        </form>
    </x-card>
</aside>