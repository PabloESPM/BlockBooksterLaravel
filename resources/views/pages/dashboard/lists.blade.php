@extends('layouts.app')

@section('title', 'My Lists')

@section('content')
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar -->
        @include('pages.dashboard.partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1">
            <header class="mb-8 border-b-4 border-black pb-4 flex justify-between items-end">
                <div>
                    <h1 class="text-3xl font-black uppercase font-display">My Lists</h1>
                    <p class="text-gray-600 font-bold mt-1">Collections you've created</p>
                </div>
                <button class="neo-btn-primary text-sm flex items-center gap-2">
                    <span>+ Create New List</span>
                </button>
            </header>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Mock List 1 -->
                <x-card class="group hover:-translate-y-1 transition-transform cursor-pointer">
                    <div class="flex items-center justify-between mb-4 pb-2 border-b-2 border-black/10">
                        <h3 class="font-black uppercase text-lg group-hover:text-brand-blue">Summer Reading 2025</h3>
                        <span
                            class="bg-green-100 text-green-800 text-xs font-bold uppercase px-2 py-0.5 border border-black">Public</span>
                    </div>
                    <div class="flex gap-1 mb-4 h-16 bg-gray-100 overflow-hidden border border-black p-1">
                        <div class="bg-gray-300 w-10 h-full border-r border-black"></div>
                        <div class="bg-gray-400 w-10 h-full border-r border-black"></div>
                        <div class="bg-gray-500 w-10 h-full"></div>
                    </div>
                    <div class="flex justify-between items-center text-xs font-bold text-gray-500 uppercase">
                        <span>12 Books</span>
                        <span>Updated 2 days ago</span>
                    </div>
                    <div class="mt-4 flex gap-2">
                        <button class="neo-btn-secondary py-1 px-3 text-xs w-full">Edit</button>
                        <button
                            class="bg-red-100 border-2 border-black py-1 px-3 text-xs font-bold uppercase hover:bg-red-500 hover:text-white transition-colors">Delete</button>
                    </div>
                </x-card>

                <!-- Mock List 2 -->
                <x-card class="group hover:-translate-y-1 transition-transform cursor-pointer">
                    <div class="flex items-center justify-between mb-4 pb-2 border-b-2 border-black/10">
                        <h3 class="font-black uppercase text-lg group-hover:text-brand-blue">Dystopian Favorites</h3>
                        <span
                            class="bg-gray-100 text-gray-800 text-xs font-bold uppercase px-2 py-0.5 border border-black">Private</span>
                    </div>
                    <div class="flex gap-1 mb-4 h-16 bg-gray-100 overflow-hidden border border-black p-1">
                        <div class="bg-gray-300 w-10 h-full border-r border-black"></div>
                        <div class="bg-gray-400 w-10 h-full"></div>
                    </div>
                    <div class="flex justify-between items-center text-xs font-bold text-gray-500 uppercase">
                        <span>5 Books</span>
                        <span>Updated 1 month ago</span>
                    </div>
                    <div class="mt-4 flex gap-2">
                        <button class="neo-btn-secondary py-1 px-3 text-xs w-full">Edit</button>
                        <button
                            class="bg-red-100 border-2 border-black py-1 px-3 text-xs font-bold uppercase hover:bg-red-500 hover:text-white transition-colors">Delete</button>
                    </div>
                </x-card>
            </div>
        </div>
    </div>
@endsection