@extends('layouts.app')

@section('title', 'Welcome')

@section('content')

    <!-- Hero Section -->
    <section class="mb-20 text-center border-b-4 border-black pb-16">
        <h1 class="text-5xl md:text-7xl font-display font-black tracking-tighter mb-6 uppercase leading-none">
            Track <span class="bg-brand-blue text-white px-2 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">books</span><br>like a
            prose.
        </h1>
        <p class="text-xl font-bold mb-10 max-w-2xl mx-auto text-gray-700">
            The no-nonsense social network for book lovers. <br>Rate. Review. Share.
        </p>

        <!-- Search Bar -->
        <div class="max-w-2xl mx-auto mb-10 relative">
            <input type="text" class="neo-input text-lg py-4 pl-12" placeholder="Search for books, authors, or lists...">
            <svg class="w-6 h-6 absolute left-4 top-1/2 -translate-y-1/2 text-gray-500" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>

        @guest
            <div class="flex justify-center gap-6">
                <a href="{{ route('register') }}" class="neo-btn-primary text-lg px-8 py-4">Start Collection</a>
                <a href="#discovery" class="neo-btn-secondary text-lg px-8 py-4">Explore</a>
            </div>
        @endguest
    </section>

    <!-- Auth CTA (Guest Only) -->
    @guest
        <section class="mb-20 bg-black text-white p-8 md:p-12 shadow-[8px_8px_0px_#000] relative overflow-hidden">
            <div class="absolute -right-20 -top-20 w-64 h-64 bg-brand-yellow rounded-full opacity-20 blur-3xl"></div>
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
                <div>
                    <h2 class="text-3xl md:text-4xl font-black uppercase mb-2">Join the Club</h2>
                    <p class="font-bold text-gray-400">Create your profile, track your reading, and join the discussion.</p>
                </div>
                <a href="{{ route('register') }}"
                    class="bg-brand-yellow text-black border-2 border-white font-black uppercase px-8 py-4 shadow-[4px_4px_0px_#fff] hover:translate-y-[-2px] hover:shadow-[6px_6px_0px_#fff] transition-all">
                    Create Account
                </a>
            </div>
        </section>
    @endguest

    <div id="discovery"></div>

    <!-- Latest Arrivals -->
    <section class="mb-16">
        <div class="flex items-end justify-between mb-8 border-b-2 border-black pb-2">
            <h2 class="text-3xl font-display font-black uppercase tracking-tight">Latest <span
                    class="text-brand-blue">Arrivals</span></h2>
            <a href="/books"
                class="font-bold underline decoration-2 decoration-brand-yellow hover:bg-brand-yellow hover:text-black transition-colors px-2">VIEW
                ALL</a>
        </div>

        <div class="flex overflow-x-auto pb-10 space-x-6 snap-x hide-scrollbar">
            @for($i = 0; $i < 6; $i++)
                <div class="w-48 flex-none snap-start">
                    <!-- Updated to use book-card component -->
                    <x-book-card id="{{ $i }}" title="Book Title {{ $i }}" author="Author Name"
                        cover="https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&q=80&w=600"
                        rating="4.{{$i}}" />
                </div>
            @endfor
        </div>
    </section>

    <!-- Best Rated -->
    <section class="mb-16">
        <div class="flex items-end justify-between mb-8 border-b-2 border-black pb-2">
            <h2 class="text-3xl font-display font-black uppercase tracking-tight">Best <span
                    class="text-brand-yellow text-shadow-neo">Rated</span></h2>
            <a href="/books"
                class="font-bold underline decoration-2 decoration-brand-yellow hover:bg-brand-yellow hover:text-black transition-colors px-2">VIEW
                ALL</a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
            @for($i = 0; $i < 5; $i++)
                <!-- Updated to use book-card component -->
                <x-book-card id="rated-{{ $i }}" title="Masterpiece {{ $i }}" author="Top Author"
                    cover="https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&q=80&w=600"
                    rating="4.9" />
            @endfor
        </div>
    </section>

    <!-- By Genre -->
    <section class="mb-16">
        <div class="flex items-end justify-between mb-8 border-b-2 border-black pb-2">
            <h2 class="text-3xl font-display font-black uppercase tracking-tight">Top <span
                    class="underline decoration-4 decoration-brand-blue">Genres</span></h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="neo-card p-6 hover:bg-gray-50 transition-colors cursor-pointer">
                <h3 class="text-2xl font-black uppercase mb-4">Sci-Fi</h3>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm font-bold border-b border-gray-300 pb-1"><span>Dune</span>
                        <span class="text-gray-500">4.8★</span></div>
                    <div class="flex justify-between text-sm font-bold border-b border-gray-300 pb-1"><span>Project Hail
                            Mary</span> <span class="text-gray-500">4.9★</span></div>
                    <div class="flex justify-between text-sm font-bold border-b border-gray-300 pb-1">
                        <span>Neuromancer</span> <span class="text-gray-500">4.7★</span></div>
                </div>
                <a href="#" class="block mt-4 text-xs font-black uppercase text-brand-blue hover:underline">View Genre
                    -></a>
            </div>
            <div class="neo-card p-6 hover:bg-gray-50 transition-colors cursor-pointer">
                <h3 class="text-2xl font-black uppercase mb-4">Horror</h3>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm font-bold border-b border-gray-300 pb-1"><span>It</span> <span
                            class="text-gray-500">4.8★</span></div>
                    <div class="flex justify-between text-sm font-bold border-b border-gray-300 pb-1"><span>The
                            Shining</span> <span class="text-gray-500">4.9★</span></div>
                    <div class="flex justify-between text-sm font-bold border-b border-gray-300 pb-1"><span>Dracula</span>
                        <span class="text-gray-500">4.6★</span></div>
                </div>
                <a href="#" class="block mt-4 text-xs font-black uppercase text-brand-blue hover:underline">View Genre
                    -></a>
            </div>
            <div class="neo-card p-6 hover:bg-gray-50 transition-colors cursor-pointer">
                <h3 class="text-2xl font-black uppercase mb-4">Fantasy</h3>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm font-bold border-b border-gray-300 pb-1"><span>The
                            Hobbit</span> <span class="text-gray-500">5.0★</span></div>
                    <div class="flex justify-between text-sm font-bold border-b border-gray-300 pb-1"><span>Harry
                            Potter</span> <span class="text-gray-500">4.8★</span></div>
                    <div class="flex justify-between text-sm font-bold border-b border-gray-300 pb-1"><span>Mistborn</span>
                        <span class="text-gray-500">4.9★</span></div>
                </div>
                <a href="#" class="block mt-4 text-xs font-black uppercase text-brand-blue hover:underline">View Genre
                    -></a>
            </div>
        </div>
    </section>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 mb-16">
        <!-- Most Searched (Left col) -->
        <div class="lg:col-span-4">
            <div class="mb-6 border-b-2 border-black pb-2">
                <h2 class="text-2xl font-black uppercase">Most Searched</h2>
                <p class="text-xs font-bold uppercase text-gray-500">Last 30 days</p>
            </div>
            <div class="space-y-2">
                @php $searches = ['The Great Gatsby', '1984', 'Atomic Habits', 'Fourth Wing', 'Iron Flame']; @endphp
                @foreach($searches as $index => $search)
                    <div
                        class="flex items-center justify-between p-3 border-2 border-black hover:bg-brand-yellow hover:text-black transition-colors cursor-pointer group">
                        <span class="font-bold uppercase text-sm">#{{ $index + 1 }} {{ $search }}</span>
                        <span class="opacity-0 group-hover:opacity-100 font-black">-></span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Featured Lists (Right col) -->
        <div class="lg:col-span-8">
            <div class="mb-6 border-b-2 border-black pb-2 flex justify-between items-end">
                <h2 class="text-2xl font-black uppercase">Featured Lists</h2>
                <a href="/lists" class="text-xs font-bold uppercase underline hover:text-brand-blue">View All</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div
                    class="neo-card p-0 flex flex-col md:flex-row h-full group hover:shadow-[6px_6px_0px_#000] cursor-pointer">
                    <div class="w-full md:w-1/3 bg-gray-800 p-4 flex items-center justify-center text-white">
                        <span class="font-black text-4xl">100</span>
                    </div>
                    <div class="p-4 flex flex-col justify-center">
                        <h3 class="font-black uppercase text-lg group-hover:text-brand-blue">Books to read before you die
                        </h3>
                        <p class="text-xs font-bold text-gray-500 mt-1">Curated by BBC</p>
                    </div>
                </div>
                <div
                    class="neo-card p-0 flex flex-col md:flex-row h-full group hover:shadow-[6px_6px_0px_#000] cursor-pointer">
                    <div class="w-full md:w-1/3 bg-brand-blue p-4 flex items-center justify-center text-white">
                        <span class="font-black text-4xl">50</span>
                    </div>
                    <div class="p-4 flex flex-col justify-center">
                        <h3 class="font-black uppercase text-lg group-hover:text-brand-blue">Best Sci-Fi of the 21st Century
                        </h3>
                        <p class="text-xs font-bold text-gray-500 mt-1">Curated by Polygon</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Users -->
    <section class="mb-20">
        <div class="flex items-end justify-between mb-8 border-b-2 border-black pb-2">
            <h2 class="text-3xl font-display font-black uppercase tracking-tight">Top <span
                    class="bg-black text-white px-2">Community</span></h2>
            <a href="/community"
                class="font-bold underline decoration-2 decoration-brand-yellow hover:bg-brand-yellow hover:text-black transition-colors px-2">VIEW
                ALL</a>
        </div>
        <div class="flex flex-wrap gap-8 justify-center">
            @for($i = 0; $i < 5; $i++)
                <div class="flex flex-col items-center group cursor-pointer">
                    <div
                        class="w-20 h-20 bg-gray-200 rounded-full border-2 border-black mb-2 shadow-[2px_2px_0px_#000] group-hover:-translate-y-1 transition-transform">
                    </div>
                    <span class="font-bold uppercase text-sm group-hover:text-brand-blue">User{{ $i }}</span>
                </div>
            @endfor
        </div>
    </section>

    <!-- Popular Reviews (Grid) (Brutal Opinions - Preserved) -->
    <section class="mb-16">
        <h2 class="text-3xl font-display font-black uppercase tracking-tight mb-8 border-b-2 border-black pb-2">Brutal
            Opinions</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @for($i = 0; $i < 3; $i++)
                <div class="neo-card flex flex-col h-full bg-brand-yellow/10 p-6">
                    <!-- Variant color & raw card structure for preservation -->
                    <div class="flex items-center mb-4 border-b-2 border-black pb-2">
                        <div class="w-10 h-10 bg-black rounded-full mr-3"></div> <!-- Avatar placeholder -->
                        <div>
                            <p class="font-bold text-sm uppercase">Reviewer {{$i}}</p>
                            <div class="flex text-black text-xs gap-0.5">
                                <span>★</span><span>★</span><span>★</span><span>★</span><span class="text-gray-400">★</span>
                            </div>
                        </div>
                    </div>
                    <h3 class="font-display font-bold text-xl mb-2 leading-tight">"A wild ride from start to finish"</h3>
                    <p class="text-sm font-medium line-clamp-4 mb-4 flex-grow">
                        Normally I don't read sci-fi, but this book completely changed my mind. The pacing was incredible and
                        the characters felt so real...
                    </p>
                    <a href="#" class="text-xs font-black uppercase underline hover:text-brand-blue">Read Full Review</a>
                </div>
            @endfor
        </div>
    </section>
@endsection