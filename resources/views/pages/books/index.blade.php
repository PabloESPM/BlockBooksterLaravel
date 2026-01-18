@extends('layouts.app')

@section('title', 'Browse Books')

@section('content')

    <div class="flex flex-col lg:flex-row gap-12">

        <!-- Sidebar Filters (Desktop) -->
        <aside class="w-full lg:w-72 flex-shrink-0 hidden lg:block">
            <x-card class="sticky top-24 space-y-8 bg-white">
                <!-- Filter Group -->
                <div>
                    <h3 class="font-black text-lg mb-4 uppercase bg-black text-white inline-block px-2">Genres</h3>
                    <div class="space-y-3 font-bold">
                        <label class="flex items-center space-x-3 cursor-pointer group">
                            <input type="checkbox"
                                class="w-5 h-5 border-2 border-black rounded-none focus:ring-0 checked:bg-brand-yellow checked:text-black">
                            <span class="group-hover:translate-x-1 transition-transform">Science Fiction</span>
                        </label>
                        <label class="flex items-center space-x-3 cursor-pointer group">
                            <input type="checkbox"
                                class="w-5 h-5 border-2 border-black rounded-none focus:ring-0 checked:bg-brand-yellow checked:text-black">
                            <span class="group-hover:translate-x-1 transition-transform">Mystery</span>
                        </label>
                        <label class="flex items-center space-x-3 cursor-pointer group">
                            <input type="checkbox"
                                class="w-5 h-5 border-2 border-black rounded-none focus:ring-0 checked:bg-brand-yellow checked:text-black">
                            <span class="group-hover:translate-x-1 transition-transform">Romance</span>
                        </label>
                    </div>
                </div>

                <x-secondary-button class="w-full text-center">Reset</x-secondary-button>
            </x-card>
        </aside>

        <!-- Main Grid -->
        <div class="flex-1">
            <h1 class="text-4xl font-display font-black uppercase mb-8 flex items-center">
                <span
                    class="bg-brand-yellow px-2 border-2 border-black mr-4 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">Docs</span>
                All Books
            </h1>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4 gap-6">
                @for($i = 0; $i < 12; $i++)
                    <div class="h-full">
                        <x-book-card :title="'Book Title ' . $i" author="Author Name"
                            cover="https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&q=80&w=600"
                            :rating="rand(3, 5)" />
                    </div>
                @endfor
            </div>

            <!-- Pagination -->
            <div class="mt-16 flex justify-center gap-2">
                <button
                    class="w-10 h-10 border-2 border-black bg-white font-bold hover:bg-black hover:text-white transition-colors">
                    << /button>
                        <button
                            class="w-10 h-10 border-2 border-black bg-brand-blue text-white font-bold shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">1</button>
                        <button
                            class="w-10 h-10 border-2 border-black bg-white font-bold hover:bg-black hover:text-white transition-colors">2</button>
                        <button
                            class="w-10 h-10 border-2 border-black bg-white font-bold hover:bg-black hover:text-white transition-colors">></button>
            </div>
        </div>
    </div>
@endsection