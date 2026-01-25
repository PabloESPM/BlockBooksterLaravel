@extends('layouts.app')

@section('title', 'Stephen King - Author Profile')

@section('content')
    <!-- Author Header -->
    <div class="neo-card p-6 md:p-10 mb-12 relative overflow-hidden">
        <!-- Background decorative element -->
        <div
            class="absolute top-0 right-0 w-64 h-64 bg-brand-yellow rounded-full translate-x-1/2 -translate-y-1/2 border-2 border-black opacity-20">
        </div>

        <div class="flex flex-col md:flex-row items-center md:items-start gap-8 relative z-10">
            <!-- Author Photo -->
            <div class="flex-shrink-0">
                <div
                    class="w-48 h-48 bg-gray-300 rounded-full border-4 border-black shadow-[8px_8px_0px_#000] overflow-hidden">
                    <img src="https://ui-avatars.com/api/?name=Stephen+King&background=random&size=256" alt="Stephen King"
                        class="w-full h-full object-cover">
                </div>
            </div>

            <!-- Author Info -->
            <div class="flex-grow text-center md:text-left">
                <div class="mb-4">
                    <h1 class="text-4xl md:text-6xl font-black font-display uppercase tracking-tighter mb-2">Stephen <span
                            class="text-brand-blue">King</span></h1>
                    <div
                        class="flex flex-wrap justify-center md:justify-start gap-4 text-sm font-bold uppercase tracking-wide text-gray-700">
                        <span class="flex items-center gap-1">
                            <span class="w-2 h-2 bg-black rounded-full"></span>
                            Portland, Maine
                        </span>
                        <span class="flex items-center gap-1">
                            <span class="w-2 h-2 bg-black rounded-full"></span>
                            Sep 21, 1947 (77 years)
                        </span>
                    </div>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-3 gap-4 border-y-2 border-black py-4 mb-6 max-w-md mx-auto md:mx-0">
                    <div class="text-center md:text-left border-r-2 border-black last:border-0 pr-4">
                        <div class="text-2xl font-black">65</div>
                        <div class="text-xs font-bold uppercase text-gray-500">Books</div>
                    </div>
                    <div class="text-center md:text-left border-r-2 border-black last:border-0 px-4">
                        <div class="text-2xl font-black">1.2m</div>
                        <div class="text-xs font-bold uppercase text-gray-500">Followers</div>
                    </div>
                    <div class="text-center md:text-left pl-4">
                        <div class="text-2xl font-black flex items-center justify-center md:justify-start gap-1">
                            4.5 <span class="text-brand-yellow text-lg">★</span>
                        </div>
                        <div class="text-xs font-bold uppercase text-gray-500">Avg Rating</div>
                    </div>
                </div>

                <div class="flex gap-4 justify-center md:justify-start">
                    <button class="neo-btn-primary">
                        Follow Author
                    </button>
                    <button class="neo-btn-secondary">
                        Share Profile
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <!-- Biography Section -->
        <div class="lg:col-span-1">
            <div class="mb-4 border-b-2 border-black pb-2">
                <h2 class="text-2xl font-black uppercase flex items-center gap-2">
                    <span class="w-4 h-4 bg-brand-blue border-2 border-black block"></span>
                    Biography
                </h2>
            </div>
            <div class="neo-card p-6 text-sm leading-relaxed font-medium">
                <p class="mb-4">
                    Stephen Edwin King is an American author of horror, supernatural fiction, suspense, crime,
                    science-fiction, and fantasy novels. Described as the "King of Horror", a play on his surname and a
                    reference to his high standing in pop culture, his books have sold more than 350 million copies, and
                    many have been adapted into films, television series, miniseries, and comic books. King has published 65
                    novels, including seven under the pen name Richard Bachman, and five non-fiction books. He has also
                    written approximately 200 short stories, most of which have been published in book collections.
                </p>
                <p>
                    King has received Bram Stoker Awards, World Fantasy Awards, and British Fantasy Society Awards. In 2003,
                    the National Book Foundation awarded him the Medal for Distinguished Contribution to American Letters.
                </p>
                <a href="#" class="block mt-4 font-bold uppercase underline hover:text-brand-blue">Read full bio on
                    Wikipedia -></a>
            </div>
        </div>

        <!-- Books Section -->
        <div class="lg:col-span-2">
            <div class="flex items-center justify-between mb-4 border-b-2 border-black pb-2">
                <h2 class="text-2xl font-black uppercase flex items-center gap-2">
                    <span class="w-4 h-4 bg-brand-yellow border-2 border-black block"></span>
                    Bibliography
                </h2>
                <div class="flex gap-2 text-xs font-bold uppercase">
                    <button class="bg-black text-white px-3 py-1">All</button>
                    <button class="bg-white border-2 border-black px-3 py-1 hover:bg-gray-100">Novel</button>
                    <button class="bg-white border-2 border-black px-3 py-1 hover:bg-gray-100">Collection</button>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                <!-- Mock Book Card 1 -->
                <div class="neo-card p-0 group cursor-pointer hover:-translate-y-1 transition-transform">
                    <div class="aspect-[2/3] bg-gray-200 border-b-2 border-black relative overflow-hidden">
                        <div
                            class="absolute inset-0 flex items-center justify-center text-gray-400 font-black text-2xl uppercase rotate-45 opacity-20">
                            Cover</div>
                    </div>
                    <div class="p-3">
                        <div class="flex justify-between items-start mb-1">
                            <h3 class="font-bold uppercase text-sm leading-tight group-hover:text-brand-blue line-clamp-2">
                                It</h3>
                            <span class="text-xs font-black bg-brand-yellow px-1 border border-black">4.8</span>
                        </div>
                        <p class="text-xs text-gray-500 uppercase">1986</p>
                    </div>
                </div>

                <!-- Mock Book Card 2 -->
                <div class="neo-card p-0 group cursor-pointer hover:-translate-y-1 transition-transform">
                    <div class="aspect-[2/3] bg-gray-200 border-b-2 border-black relative overflow-hidden">
                        <div
                            class="absolute inset-0 flex items-center justify-center text-gray-400 font-black text-2xl uppercase rotate-45 opacity-20">
                            Cover</div>
                    </div>
                    <div class="p-3">
                        <div class="flex justify-between items-start mb-1">
                            <h3 class="font-bold uppercase text-sm leading-tight group-hover:text-brand-blue line-clamp-2">
                                The Shining</h3>
                            <span class="text-xs font-black bg-brand-yellow px-1 border border-black">4.9</span>
                        </div>
                        <p class="text-xs text-gray-500 uppercase">1977</p>
                    </div>
                </div>

                <!-- Mock Book Card 3 -->
                <div class="neo-card p-0 group cursor-pointer hover:-translate-y-1 transition-transform">
                    <div class="aspect-[2/3] bg-gray-200 border-b-2 border-black relative overflow-hidden">
                        <div
                            class="absolute inset-0 flex items-center justify-center text-gray-400 font-black text-2xl uppercase rotate-45 opacity-20">
                            Cover</div>
                    </div>
                    <div class="p-3">
                        <div class="flex justify-between items-start mb-1">
                            <h3 class="font-bold uppercase text-sm leading-tight group-hover:text-brand-blue line-clamp-2">
                                The Stand</h3>
                            <span class="text-xs font-black bg-brand-yellow px-1 border border-black">4.7</span>
                        </div>
                        <p class="text-xs text-gray-500 uppercase">1978</p>
                    </div>
                </div>

                <!-- Mock Book Card 4 -->
                <div class="neo-card p-0 group cursor-pointer hover:-translate-y-1 transition-transform">
                    <div class="aspect-[2/3] bg-gray-200 border-b-2 border-black relative overflow-hidden">
                        <div
                            class="absolute inset-0 flex items-center justify-center text-gray-400 font-black text-2xl uppercase rotate-45 opacity-20">
                            Cover</div>
                    </div>
                    <div class="p-3">
                        <div class="flex justify-between items-start mb-1">
                            <h3 class="font-bold uppercase text-sm leading-tight group-hover:text-brand-blue line-clamp-2">
                                Misery</h3>
                            <span class="text-xs font-black bg-brand-yellow px-1 border border-black">4.6</span>
                        </div>
                        <p class="text-xs text-gray-500 uppercase">1987</p>
                    </div>
                </div>
                <!-- Mock Book Card 5 -->
                <div class="neo-card p-0 group cursor-pointer hover:-translate-y-1 transition-transform">
                    <div class="aspect-[2/3] bg-gray-200 border-b-2 border-black relative overflow-hidden">
                        <div
                            class="absolute inset-0 flex items-center justify-center text-gray-400 font-black text-2xl uppercase rotate-45 opacity-20">
                            Cover</div>
                    </div>
                    <div class="p-3">
                        <div class="flex justify-between items-start mb-1">
                            <h3 class="font-bold uppercase text-sm leading-tight group-hover:text-brand-blue line-clamp-2">
                                Carrie</h3>
                            <span class="text-xs font-black bg-brand-yellow px-1 border border-black">4.2</span>
                        </div>
                        <p class="text-xs text-gray-500 uppercase">1974</p>
                    </div>
                </div>
                <!-- Mock Book Card 6 -->
                <div class="neo-card p-0 group cursor-pointer hover:-translate-y-1 transition-transform">
                    <div class="aspect-[2/3] bg-gray-200 border-b-2 border-black relative overflow-hidden">
                        <div
                            class="absolute inset-0 flex items-center justify-center text-gray-400 font-black text-2xl uppercase rotate-45 opacity-20">
                            Cover</div>
                    </div>
                    <div class="p-3">
                        <div class="flex justify-between items-start mb-1">
                            <h3 class="font-bold uppercase text-sm leading-tight group-hover:text-brand-blue line-clamp-2">
                                Salem's Lot</h3>
                            <span class="text-xs font-black bg-brand-yellow px-1 border border-black">4.4</span>
                        </div>
                        <p class="text-xs text-gray-500 uppercase">1975</p>
                    </div>
                </div>
            </div>

            <button class="w-full mt-6 neo-btn-secondary">Load More Books</button>
        </div>
    </div>
@endsection