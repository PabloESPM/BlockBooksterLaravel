@extends('layouts.app')

@section('title', 'Authors')

@section('content')
    <div class="mb-12 border-b-4 border-black pb-4">
        <h1 class="text-4xl md:text-6xl font-black font-display uppercase tracking-tighter">Meet the <span
                class="text-brand-yellow text-shadow-neo">Authors</span></h1>
        <p class="text-lg font-bold mt-2 text-gray-600 uppercase tracking-widest">The minds behind the stories</p>
    </div>

    <!-- Section: Popular Authors -->
    <section class="mb-16">
        <div class="flex items-center justify-between mb-6 border-b-2 border-black pb-2">
            <h2 class="text-2xl font-black uppercase flex items-center gap-2">
                <span class="w-4 h-4 bg-brand-blue border-2 border-black block"></span>
                Popular Now
            </h2>
            <a href="#" class="text-sm font-bold uppercase hover:underline hover:text-brand-blue">View all -></a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
            @for ($i = 0; $i < 6; $i++)
                <div class="neo-card p-4 text-center group hover:bg-blue-50 transition-colors cursor-pointer">
                    <div class="w-24 h-24 mx-auto bg-gray-300 rounded-full border-2 border-black mb-3 overflow-hidden">
                        <!-- Placeholder for author image -->
                        <img src="https://ui-avatars.com/api/?name=Author+{{ $i }}&background=random" alt="Author"
                            class="w-full h-full object-cover">
                    </div>
                    <h3 class="text-sm font-bold uppercase mb-1 group-hover:underline">Stephen King</h3>
                    <div class="text-xs font-bold text-gray-500">1.2m Followers</div>
                    <button
                        class="mt-3 w-full text-xs font-black uppercase border-2 border-black py-1 hover:bg-black hover:text-white transition-colors">Follow</button>
                </div>
            @endfor
        </div>
    </section>

    <!-- Section: Classics -->
    <section class="mb-16">
        <div class="flex items-center justify-between mb-6 border-b-2 border-black pb-2">
            <h2 class="text-2xl font-black uppercase flex items-center gap-2">
                <span class="w-4 h-4 bg-black border-2 border-black block"></span>
                The Classics
            </h2>
            <a href="#" class="text-sm font-bold uppercase hover:underline hover:text-brand-blue">View all -></a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="neo-card p-6 flex items-center gap-6">
                <div class="w-20 h-20 bg-gray-200 border-2 border-black flex-shrink-0"></div>
                <div>
                    <h3 class="text-xl font-black uppercase">Jane Austen</h3>
                    <p class="text-sm font-bold text-gray-600 mb-2">Romance, Social Commentary</p>
                    <div class="flex items-center gap-1">
                        <span class="text-xs font-black bg-brand-yellow px-2 border border-black">4.8</span>
                        <span class="text-xs font-bold uppercase text-gray-500">Avg Rating</span>
                    </div>
                </div>
            </div>
            <div class="neo-card p-6 flex items-center gap-6">
                <div class="w-20 h-20 bg-gray-200 border-2 border-black flex-shrink-0"></div>
                <div>
                    <h3 class="text-xl font-black uppercase">George Orwell</h3>
                    <p class="text-sm font-bold text-gray-600 mb-2">Dystopian, Political</p>
                    <div class="flex items-center gap-1">
                        <span class="text-xs font-black bg-brand-yellow px-2 border border-black">4.9</span>
                        <span class="text-xs font-bold uppercase text-gray-500">Avg Rating</span>
                    </div>
                </div>
            </div>
            <div class="neo-card p-6 flex items-center gap-6">
                <div class="w-20 h-20 bg-gray-200 border-2 border-black flex-shrink-0"></div>
                <div>
                    <h3 class="text-xl font-black uppercase">J.R.R. Tolkien</h3>
                    <p class="text-sm font-bold text-gray-600 mb-2">Fantasy</p>
                    <div class="flex items-center gap-1">
                        <span class="text-xs font-black bg-brand-yellow px-2 border border-black">5.0</span>
                        <span class="text-xs font-bold uppercase text-gray-500">Avg Rating</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section: Most Rated -->
    <section class="mb-16">
        <div class="flex items-center justify-between mb-6 border-b-2 border-black pb-2">
            <h2 class="text-2xl font-black uppercase flex items-center gap-2">
                <span class="w-4 h-4 bg-brand-yellow border-2 border-black block"></span>
                Most Rated
            </h2>
            <a href="#" class="text-sm font-bold uppercase hover:underline hover:text-brand-blue">View all -></a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            @for ($i = 0; $i < 5; $i++)
                <div class="neo-card p-3 text-center">
                    <h3 class="font-bold uppercase text-sm truncate">Author Name</h3>
                    <div class="text-3xl font-black text-brand-blue my-2">{{ 500 - ($i * 50) }}k</div>
                    <div class="text-xs font-bold uppercase text-gray-500">Ratings</div>
                </div>
            @endfor
        </div>
    </section>

    <!-- Section: New Authors -->
    <section>
        <div class="flex items-center justify-between mb-6 border-b-2 border-black pb-2">
            <h2 class="text-2xl font-black uppercase flex items-center gap-2">
                <span class="w-4 h-4 bg-green-500 border-2 border-black block"></span>
                Rising Stars
            </h2>
            <a href="#" class="text-sm font-bold uppercase hover:underline hover:text-brand-blue">View all -></a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @for ($i = 0; $i < 4; $i++)
                <div class="neo-card p-4 hover:-translate-y-1 transition-transform">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-black rounded-full text-white flex items-center justify-center font-bold">New
                        </div>
                        <div>
                            <h3 class="font-bold uppercase text-sm">Debut Author</h3>
                            <p class="text-xs text-gray-500">Published 2 days ago</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-700 italic border-l-2 border-gray-300 pl-3">"A stunning debut that shakes the
                        genre..."</p>
                </div>
            @endfor
        </div>
    </section>
@endsection