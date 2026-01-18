@extends('layouts.app')

@section('title', 'Project Hail Mary')

@section('content')

    <article>
        <!-- Header Block -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 mb-12">

            <!-- Cover (Left) -->
            <div class="md:col-span-4 lg:col-span-3">
                <div class="border-4 border-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] bg-white p-2">
                    <img src="https://images.unsplash.com/photo-1614544048536-0d28caf77f41?auto=format&fit=crop&q=80&w=600"
                        alt="Cover"
                        class="w-full h-auto border-2 border-black grayscale hover:grayscale-0 transition-all duration-500">
                </div>
            </div>

            <!-- Info (Right) -->
            <div class="md:col-span-8 lg:col-span-9 flex flex-col justify-end pb-4">
                <div class="mb-6">
                    <h1 class="text-4xl md:text-6xl font-display font-black leading-none mb-4 uppercase">
                        Project Hail Mary
                    </h1>
                    <p class="text-2xl font-bold font-mono text-gray-600 mb-6">
                        Andy Weir <span class="bg-black text-white px-2 py-1 text-sm ml-4">2021</span>
                    </p>

                    <div class="flex gap-4 mb-8">
                        <x-primary-button class="flex items-center gap-2">
                            <span>★ Rate Book</span>
                        </x-primary-button>
                        <x-secondary-button class="flex items-center gap-2">
                            <span>+ Add to List</span>
                        </x-secondary-button>
                    </div>
                </div>

                <!-- Stats Bar -->
                <div class="border-2 border-black bg-white p-4 flex gap-8 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                    <div>
                        <span class="block text-xs font-bold uppercase text-gray-500">Avg Rating</span>
                        <span class="block text-xl font-black text-brand-blue">4.85</span>
                    </div>
                    <div>
                        <span class="block text-xs font-bold uppercase text-gray-500">Pages</span>
                        <span class="block text-xl font-black">496</span>
                    </div>
                    <div>
                        <span class="block text-xs font-bold uppercase text-gray-500">Genre</span>
                        <span class="block text-xl font-black">Sci-Fi</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <div class="lg:col-span-2">
                <h3 class="font-display font-black text-2xl uppercase border-b-4 border-brand-yellow inline-block mb-6">
                    Synopsis</h3>
                <div class="prose prose-lg font-medium text-black max-w-none">
                    <p>Ryland Grace is the sole survivor on a desperate, last-chance mission—and if he fails, humanity and
                        the earth itself will perish. Except that right now, he doesn't know that. He can't even remember
                        his own name, let alone the nature of his assignment or how to complete it.</p>
                    <p>All he knows is that he's been asleep for a very, very long time. And he's just been awakened to find
                        himself millions of miles from home, with nothing but two corpses for company...</p>
                </div>
            </div>

            <div>
                <x-card class="bg-gray-100">
                    <h3 class="font-black uppercase mb-4">Friends Who Read This</h3>
                    <ul class="space-y-3">
                        <li class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-black"></div>
                            <span class="font-bold underline">Friend One</span>
                            <span class="ml-auto text-sm font-mono">5/5 ★</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-black"></div>
                            <span class="font-bold underline">Friend Two</span>
                            <span class="ml-auto text-sm font-mono">4/5 ★</span>
                        </li>
                    </ul>
                </x-card>
            </div>
        </div>
    </article>

@endsection