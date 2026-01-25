@extends('layouts.app')

@section('title', 'Community')

@section('content')
    <div class="mb-12 border-b-4 border-black pb-4">
        <h1 class="text-4xl md:text-6xl font-black font-display uppercase tracking-tighter">Community <span
                class="text-brand-yellow text-shadow-neo">Hub</span></h1>
        <p class="text-lg font-bold mt-2 text-gray-600 uppercase tracking-widest">Connect with fellow readers</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <!-- Column 1: Most Followed -->
        <section>
            <div class="bg-black text-white p-4 mb-6 shadow-[4px_4px_0px_#888]">
                <h2 class="text-xl font-black uppercase text-center tracking-widest">Most Followed</h2>
            </div>

            <div class="space-y-6">
                @for ($i = 1; $i <= 5; $i++)
                    <div class="neo-card p-4 flex items-center gap-4 hover:translate-x-1 transition-transform">
                        <div class="text-2xl font-black text-gray-300 w-8">#{{ $i }}</div>
                        <div class="w-12 h-12 bg-brand-blue rounded-full border-2 border-black flex-shrink-0"></div>
                        <div class="flex-grow">
                            <h3 class="font-bold uppercase text-sm">ReaderName{{ $i }}</h3>
                            <p class="text-xs font-bold text-gray-500">1{{ $i }}.5k Followers</p>
                        </div>
                        <button
                            class="text-xs font-black uppercase border-2 border-black px-3 py-1 hover:bg-brand-yellow transition-colors">Follow</button>
                    </div>
                @endfor
            </div>
        </section>

        <!-- Column 2: Most Lists Created -->
        <section>
            <div class="bg-brand-blue text-white p-4 mb-6 shadow-[4px_4px_0px_#000]">
                <h2 class="text-xl font-black uppercase text-center tracking-widest">Top Curators</h2>
            </div>

            <div class="space-y-6">
                @for ($i = 1; $i <= 5; $i++)
                    <div class="neo-card p-4 flex items-center gap-4 hover:translate-x-1 transition-transform">
                        <div class="text-2xl font-black text-gray-300 w-8">#{{ $i }}</div>
                        <div class="w-12 h-12 bg-brand-yellow rounded-full border-2 border-black flex-shrink-0"></div>
                        <div class="flex-grow">
                            <h3 class="font-bold uppercase text-sm">CuratorKing{{ $i }}</h3>
                            <p class="text-xs font-bold text-gray-500">{{ 100 - ($i * 10) }} Lists Created</p>
                        </div>
                    </div>
                @endfor
            </div>
        </section>

        <!-- Column 3: Most Active -->
        <section>
            <div class="bg-brand-yellow text-black border-2 border-black p-4 mb-6 shadow-[4px_4px_0px_#000]">
                <h2 class="text-xl font-black uppercase text-center tracking-widest">Most Active</h2>
            </div>

            <div class="space-y-6">
                @for ($i = 1; $i <= 5; $i++)
                    <div class="neo-card p-4 flex items-center gap-4 hover:translate-x-1 transition-transform">
                        <div class="text-2xl font-black text-gray-300 w-8">#{{ $i }}</div>
                        <div class="w-12 h-12 bg-gray-200 rounded-full border-2 border-black flex-shrink-0"></div>
                        <div class="flex-grow">
                            <h3 class="font-bold uppercase text-sm">HyperReader{{ $i }}</h3>
                            <div class="flex items-center gap-2 text-xs font-bold text-gray-500">
                                <span>{{ 500 - ($i * 20) }} Reviews</span>
                            </div>
                        </div>
                        <div class="w-3 h-3 bg-green-500 rounded-full border border-black" title="Online now"></div>
                    </div>
                @endfor
            </div>
        </section>
    </div>
@endsection