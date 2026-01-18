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

        <div class="flex justify-center gap-6">
            <x-primary-button class="text-lg px-8 py-4">Start Collection</x-primary-button>
            <x-neutral-button class="text-lg px-8 py-4">Explore</x-neutral-button>
        </div>
    </section>

    <!-- Shelf: Trending -->
    <section class="mb-16">
        <div class="flex items-end justify-between mb-8 border-b-2 border-black pb-2">
            <h2 class="text-3xl font-display font-black uppercase tracking-tight">Trending</h2>
            <a href="/books"
                class="font-bold underline decoration-2 decoration-brand-yellow hover:bg-brand-yellow hover:text-black transition-colors px-2">VIEW
                ALL</a>
        </div>

        <!-- Horizontal Scroll Container -->
        <div class="flex overflow-x-auto pb-10 space-x-6 snap-x">
            @for($i = 0; $i < 6; $i++)
                <div class="w-48 flex-none snap-start">
                    <x-book-card title="The Midnight Library" author="Matt Haig"
                        cover="https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&q=80&w=600"
                        :rating="4.5" />
                </div>
            @endfor
        </div>
    </section>

    <!-- Popular Reviews (Grid) -->
    <section class="mb-16">
        <h2 class="text-3xl font-display font-black uppercase tracking-tight mb-8 border-b-2 border-black pb-2">Brutal
            Opinions</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @for($i = 0; $i < 3; $i++)
                <x-card class="flex flex-col h-full bg-brand-yellow/10"> <!-- Variant color -->
                    <div class="flex items-center mb-4 border-b-2 border-black pb-2">
                        <div class="w-10 h-10 bg-black rounded-full mr-3"></div> <!-- Avatar placeholder -->
                        <div>
                            <p class="font-bold text-sm uppercase">Reviewer {{$i}}</p>
                            <x-rating-stars :rating="4" class="w-4 h-4 text-black" />
                        </div>
                    </div>
                    <h3 class="font-display font-bold text-xl mb-2 leading-tight">"A wild ride from start to finish"</h3>
                    <p class="text-sm font-medium line-clamp-4 mb-4 flex-grow">
                        Normally I don't read sci-fi, but this book completely changed my mind. The pacing was incredible and
                        the characters felt so real...
                    </p>
                    <a href="#" class="text-xs font-black uppercase underline">Read Full Review</a>
                </x-card>
            @endfor
        </div>
    </section>

@endsection