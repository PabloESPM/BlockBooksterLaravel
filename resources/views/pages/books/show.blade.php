@extends('layouts.app')

@section('title', 'Project Hail Mary - Book Details')

@section('content')
    <!-- Hero Section -->
    <div class="grid grid-cols-1 md:grid-cols-12 gap-8 mb-16">
        <!-- Cover (Left) -->
        <div class="md:col-span-4 lg:col-span-3">
            <div class="neo-card p-0 relative group">
                <div class="aspect-[2/3] bg-gray-200 border-b-2 border-black relative overflow-hidden">
                    <!-- Placeholder Cover -->
                    <div class="absolute inset-0 flex items-center justify-center bg-brand-yellow">
                        <span class="text-4xl font-black uppercase text-black opacity-20 -rotate-45">Cover</span>
                    </div>
                    <img src="https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&q=80&w=800"
                        alt="Project Hail Mary" class="w-full h-full object-cover">
                </div>
            </div>
            <!-- Mobile Actions (visible only on small screens) -->
            <div class="mt-4 md:hidden space-y-2">
                @auth
                    <button class="w-full neo-btn-primary mb-2">Want to Read</button>
                @endauth
                <a href="#" class="block w-full text-center neo-btn-secondary text-sm">Buy on Amazon</a>
            </div>
        </div>

        <!-- Info (Right) -->
        <div class="md:col-span-8 lg:col-span-9 flex flex-col justify-between">
            <div>
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h1
                            class="text-3xl md:text-5xl font-black font-display uppercase tracking-tighter leading-none mb-2">
                            Project Hail Mary</h1>
                        <h2 class="text-xl font-bold uppercase text-gray-600">by <a href="#"
                                class="text-brand-blue hover:underline">Andy Weir</a></h2>
                    </div>
                    <!-- Rating -->
                    <div class="hidden md:block text-right">
                        <div class="flex items-center gap-1 justify-end">
                            @for($i = 0; $i < 5; $i++)
                                <svg class="w-8 h-8 text-brand-yellow fill-current drop-shadow-[2px_2px_0px_rgba(0,0,0,1)]"
                                    viewBox="0 0 24 24">
                                    <path
                                        d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                </svg>
                            @endfor
                        </div>
                        <div class="text-2xl font-black mt-1">4.8 <span class="text-sm font-bold text-gray-500 uppercase">/
                                5.0</span></div>
                        <div class="text-xs font-bold uppercase text-gray-500">Based on 12.5k ratings</div>
                    </div>
                </div>

                <!-- Meta Data -->
                <div class="flex flex-wrap gap-4 mb-8 text-sm font-bold uppercase border-y-2 border-black py-3">
                    <span class="bg-black text-white px-2 py-0.5">Sci-Fi</span>
                    <span class="bg-gray-200 border border-black px-2 py-0.5">2021</span>
                    <span class="bg-gray-200 border border-black px-2 py-0.5">496 Pages</span>
                    <span class="bg-gray-200 border border-black px-2 py-0.5">English</span>
                    <span class="text-gray-500 py-0.5">ISBN: {{ $isbn ?? '978-0593135204' }}</span>
                </div>

                <!-- Synopsis -->
                <div class="mb-8 font-medium leading-relaxed text-gray-800">
                    <p class="mb-4">Ryland Grace is the sole survivor on a desperate, last-chance mission—and if he fails,
                        humanity and the earth itself will perish.</p>
                    <p class="mb-4">Except that right now, he doesn't know that. He can't even remember his own name, let
                        alone the nature of his assignment or how to complete it.</p>
                    <p>All he knows is that he's been asleep for a very, very long time. And he's just been awakened to find
                        himself millions of miles from home, with nothing but two corpses for company.</p>
                </div>
            </div>

            <!-- Actions Desktop -->
            <div class="hidden md:flex flex-wrap items-center gap-4">
                @auth
                    <!-- Authenticated User Actions -->
                    <div class="flex items-center gap-2 border-r-2 border-black pr-4 mr-2">
                        <button class="neo-btn-primary flex items-center gap-2">
                            <span>Want to Read</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </button>
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="neo-btn-secondary px-3">▼</button>
                            <div x-show="open" @click.outside="open = false"
                                class="absolute top-full left-0 mt-2 w-48 bg-white border-2 border-black shadow-[4px_4px_0px_#000] z-20 flex flex-col">
                                <button
                                    class="text-left px-4 py-2 font-bold uppercase hover:bg-brand-yellow border-b border-black">Currently
                                    Reading</button>
                                <button class="text-left px-4 py-2 font-bold uppercase hover:bg-brand-yellow">Read</button>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Guest Prompt -->
                    <div class="flex items-center gap-4 border-r-2 border-black pr-4 mr-2">
                        <a href="{{ route('login') }}" class="text-sm font-bold uppercase underline hover:text-brand-blue">Login
                            to track reading</a>
                    </div>
                @endauth

                <!-- Affiliate Links -->
                <a href="#" class="neo-btn-secondary text-sm flex items-center gap-2">
                    Buy on Amazon
                </a>
            </div>
        </div>
    </div>

    <!-- Second Row: Reviews & Related -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <!-- Reviews Section (2/3 width) -->
        <div class="lg:col-span-2">
            <div class="flex items-center justify-between mb-6 border-b-2 border-black pb-2">
                <h2 class="text-2xl font-black uppercase flex items-center gap-2">
                    <span class="w-4 h-4 bg-brand-yellow border-2 border-black block"></span>
                    Community Reviews
                </h2>
                @auth
                    <button class="text-sm font-bold uppercase bg-black text-white px-3 py-1 hover:bg-gray-800">Write
                        Review</button>
                @endauth
            </div>

            <div class="space-y-6">
                <!-- Mock Review 1 -->
                <div class="neo-card p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-brand-blue rounded-full border-2 border-black"></div>
                            <div>
                                <h4 class="font-bold uppercase text-sm">SpaceCadet99</h4>
                                <div class="flex text-brand-yellow text-xs gap-0.5">
                                    <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                                </div>
                            </div>
                        </div>
                        <span class="text-xs font-bold text-gray-500 uppercase">2 days ago</span>
                    </div>
                    <p class="text-sm leading-relaxed mb-4">Absolute masterpiece. The science is hard but accessible, and
                        the relationship between Grace and Rocky is genuinely touching. Couldn't put it down.</p>
                    <div class="flex items-center gap-4">
                        <button class="flex items-center gap-1 text-xs font-bold uppercase hover:text-brand-blue">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5">
                                </path>
                            </svg>
                            Like (124)
                        </button>
                    </div>
                </div>

                <!-- Mock Review 2 -->
                <div class="neo-card p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gray-300 rounded-full border-2 border-black"></div>
                            <div>
                                <h4 class="font-bold uppercase text-sm">CriticalReader</h4>
                                <div class="flex text-brand-yellow text-xs gap-0.5">
                                    <span>★</span><span>★</span><span>★</span><span>★</span><span
                                        class="text-gray-300">★</span>
                                </div>
                            </div>
                        </div>
                        <span class="text-xs font-bold text-gray-500 uppercase">1 week ago</span>
                    </div>
                    <p class="text-sm leading-relaxed mb-4">A bit repetitive in the middle, but the ending pays off. Weir
                        knows how to write problem-solving porn.</p>
                    <div class="flex items-center gap-4">
                        <button class="flex items-center gap-1 text-xs font-bold uppercase hover:text-brand-blue">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5">
                                </path>
                            </svg>
                            Like (42)
                        </button>
                    </div>
                </div>
            </div>
            <button class="w-full mt-6 neo-btn-secondary">Load More Reviews</button>
        </div>

        <!-- Sidebar: Related (1/3 width) -->
        <div class="lg:col-span-1">
            <div class="mb-6 border-b-2 border-black pb-2">
                <h2 class="text-xl font-black uppercase">Related Authors</h2>
            </div>
            <div class="space-y-4">
                @for($i = 0; $i < 3; $i++)
                    <div class="neo-card p-4 flex items-center gap-4 cursor-pointer hover:bg-gray-50 transition-colors">
                        <div class="w-12 h-12 bg-gray-200 rounded-full border-2 border-black"></div>
                        <div>
                            <h4 class="font-bold uppercase text-sm">Author Name</h4>
                            <p class="text-xs text-gray-500 uppercase">Sci-Fi • Thriller</p>
                        </div>
                    </div>
                @endfor
            </div>

            <div class="my-8 border-b-2 border-black pb-2">
                <h2 class="text-xl font-black uppercase">Readers Also Liked</h2>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="neo-card p-0 h-32 bg-gray-200 border-2 border-black relative">
                    <div
                        class="absolute inset-0 flex items-center justify-center text-xs font-bold uppercase opacity-30 rotate-45">
                        Book Cover</div>
                </div>
                <div class="neo-card p-0 h-32 bg-gray-200 border-2 border-black relative">
                    <div
                        class="absolute inset-0 flex items-center justify-center text-xs font-bold uppercase opacity-30 rotate-45">
                        Book Cover</div>
                </div>
            </div>
        </div>
    </div>
@endsection