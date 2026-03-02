@extends('layouts.app')

@section('title', 'Explore Lists')

@section('content')
    <div class="mb-12 border-b-4 border-black pb-4">
        <h1 class="text-4xl md:text-6xl font-black font-display uppercase tracking-tighter">Explora <span
                class="text-brand-yellow text-shadow-neo">Listas</span></h1>
        <p class="text-lg font-bold mt-2 text-gray-600 uppercase tracking-widest">Colecciones seleccionadas por la comunidad
        </p>
    </div>

    <!-- Section: Public Lists -->
    <section class="mb-16">
        <div class="flex items-center justify-between mb-6 border-b-2 border-black pb-2">
            <h2 class="text-2xl font-black uppercase flex items-center gap-2">
                <span class="w-4 h-4 bg-brand-blue border-2 border-black block"></span>
                Listas Publicas
            </h2>
            <!-- Paginacion superior
                <div class="text-sm font-bold uppercase">
                    {{ $lists->links('pagination::simple-tailwind') }}
                </div>
                -->
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse ($lists as $list)
                <x-list-card :list="$list" />
            @empty
                <div class="col-span-full text-center py-10">
                    <h3 class="text-2xl font-black uppercase text-gray-400">No public lists found.</h3>
                </div>
            @endforelse
        </div>
        <!-- Paginación -->
        <x-modals.pagination :paginator="$lists" />

    </section>

    <!-- Section: Best Rated -->
    <section class="mb-16">
        <div class="flex items-center justify-between mb-6 border-b-2 border-black pb-2">
            <h2 class="text-2xl font-black uppercase flex items-center gap-2">
                <span class="w-4 h-4 bg-brand-yellow border-2 border-black block"></span>
                Highest Rated
            </h2>
            <a href="#" class="text-sm font-bold uppercase hover:underline hover:text-brand-blue">View all -></a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Compact List Cards -->
            @for ($i = 0; $i < 4; $i++)
                <div class="neo-card p-4 hover:bg-yellow-50 transition-colors cursor-pointer group">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold uppercase bg-black text-white px-2 py-0.5">Top 1%</span>
                        <div class="flex gap-1">
                            <div class="w-3 h-3 bg-brand-yellow rounded-full border border-black"></div>
                            <div class="w-3 h-3 bg-brand-yellow rounded-full border border-black"></div>
                            <div class="w-3 h-3 bg-brand-yellow rounded-full border border-black"></div>
                            <div class="w-3 h-3 bg-brand-yellow rounded-full border border-black"></div>
                            <div class="w-3 h-3 bg-brand-yellow rounded-full border border-black"></div>
                        </div>
                    </div>
                    <h3 class="text-lg font-bold uppercase leading-tight group-hover:underline">Best of 2025</h3>
                    <p class="text-xs text-gray-600 mt-1 uppercase">by Curator{{ $i }}</p>
                </div>
            @endfor
        </div>
    </section>

    <!-- Section: Most Shared -->
    <section>
        <div class="flex items-center justify-between mb-6 border-b-2 border-black pb-2">
            <h2 class="text-2xl font-black uppercase flex items-center gap-2">
                <span class="w-4 h-4 bg-black block"></span>
                Trending Shares
            </h2>
            <a href="#" class="text-sm font-bold uppercase hover:underline hover:text-brand-blue">View all -></a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div
                class="bg-brand-blue text-white border-2 border-black shadow-[6px_6px_0px_#000] p-8 flex items-center justify-between">
                <div>
                    <h3 class="text-3xl font-black uppercase mb-2">TikTok BookTok</h3>
                    <p class="font-bold uppercase opacity-80 mb-6">Trending viral hits</p>
                    <button
                        class="bg-white text-black border-2 border-black font-bold uppercase px-6 py-2 shadow-[4px_4px_0px_#000] hover:translate-y-[-2px] hover:shadow-[6px_6px_0px_#000] transition-all">View
                        List</button>
                </div>
                <div class="text-6xl font-black opacity-20 rotate-12">#1</div>
            </div>
            <div
                class="bg-brand-yellow text-black border-2 border-black shadow-[6px_6px_0px_#000] p-8 flex items-center justify-between">
                <div>
                    <h3 class="text-3xl font-black uppercase mb-2">Summer Reads</h3>
                    <p class="font-bold uppercase opacity-80 mb-6">Perfect for the beach</p>
                    <button
                        class="bg-white text-black border-2 border-black font-bold uppercase px-6 py-2 shadow-[4px_4px_0px_#000] hover:translate-y-[-2px] hover:shadow-[6px_6px_0px_#000] transition-all">View
                        List</button>
                </div>
                <div class="text-6xl font-black opacity-20 rotate-12">#2</div>
            </div>
        </div>
    </section>
@endsection