@extends('layouts.app')

@section('title', 'My Reviews')

@section('content')
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar -->
        @include('pages.dashboard.partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1">
            <header class="mb-8 border-b-4 border-black pb-4">
                <h1 class="text-3xl font-black uppercase font-display">My Reviews</h1>
                <p class="text-gray-600 font-bold mt-1">Manage your book ratings</p>
            </header>

            <div class="space-y-6">
                <!-- Mock Review 1 -->
                <x-card class="flex flex-col md:flex-row gap-6">
                    <div class="w-20 flex-shrink-0">
                        <img src="https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&q=80&w=200"
                            alt="Cover" class="w-full border-2 border-black shadow-[2px_2px_0px_#000]">
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-bold uppercase text-lg leading-tight">Project Hail Mary</h3>
                            <div class="flex text-brand-yellow text-xs gap-0.5">
                                <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                            </div>
                        </div>
                        <p class="text-sm italic text-gray-700 mb-4 bg-gray-50 p-3 border border-gray-200">"This book
                            completely revived my love for hard sci-fi. The pacing is relentless."</p>

                        <div class="flex justify-between items-center border-t-2 border-black/10 pt-3">
                            <span class="text-xs font-bold text-gray-500 uppercase">Written on Jan 24, 2026</span>
                            <div class="flex gap-2">
                                <button class="text-xs font-black uppercase hover:text-brand-blue underline">Edit</button>
                                <button class="text-xs font-black uppercase hover:text-red-600 underline">Delete</button>
                            </div>
                        </div>
                    </div>
                </x-card>

                <!-- Mock Review 2 -->
                <x-card class="flex flex-col md:flex-row gap-6">
                    <div class="w-20 flex-shrink-0">
                        <img src="https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&q=80&w=200"
                            alt="Cover" class="w-full border-2 border-black shadow-[2px_2px_0px_#000]">
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-bold uppercase text-lg leading-tight">Dark Matter</h3>
                            <div class="flex text-brand-yellow text-xs gap-0.5">
                                <span>★</span><span>★</span><span>★</span><span>★</span><span class="text-gray-300">★</span>
                            </div>
                        </div>
                        <p class="text-sm italic text-gray-700 mb-4 bg-gray-50 p-3 border border-gray-200">"A mind-bending
                            thriller that keeps you guessing until the very end."</p>

                        <div class="flex justify-between items-center border-t-2 border-black/10 pt-3">
                            <span class="text-xs font-bold text-gray-500 uppercase">Written on Dec 10, 2025</span>
                            <div class="flex gap-2">
                                <button class="text-xs font-black uppercase hover:text-brand-blue underline">Edit</button>
                                <button class="text-xs font-black uppercase hover:text-red-600 underline">Delete</button>
                            </div>
                        </div>
                    </div>
                </x-card>
            </div>
        </div>
    </div>
@endsection