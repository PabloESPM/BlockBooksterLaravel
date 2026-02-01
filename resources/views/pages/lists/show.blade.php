@extends('layouts.app')

@section('title', $list->name . ' - Reading List')

@section('content')
    <!-- List Header -->
    <div class="mb-12">
        <div class="flex flex-col md:flex-row gap-8 items-start">
            <div class="flex-grow">
                <div class="flex items-center gap-2 mb-2">
                    <span class="bg-brand-blue text-white text-xs font-bold uppercase px-2 py-1 border border-black shadow-[2px_2px_0px_#000]">List</span>
                    @if($list->visibility === 'private')
                        <span class="bg-gray-200 text-gray-600 text-xs font-bold uppercase px-2 py-1 border border-black">Private</span>
                    @endif
                </div>
                <h1 class="text-4xl md:text-6xl font-black font-display uppercase tracking-tighter mb-4">{{ $list->name }}</h1>
                <p class="text-lg font-medium text-gray-700 max-w-2xl mb-6 border-l-4 border-brand-yellow pl-4">
                    {{ $list->description ?? 'No description provided.' }}
                </p>
                
                <div class="flex items-center gap-4">
                    <a href="{{ route('users.show', $list->user->id) }}" class="flex items-center gap-2 hover:opacity-80 transition-opacity">
                        <div class="w-8 h-8 rounded-full bg-gray-300 border border-black overflow-hidden">
                             <img src="https://ui-avatars.com/api/?name={{ urlencode($list->user->name ?? 'User') }}&background=random" class="w-full h-full object-cover">
                        </div>
                        <span class="text-sm font-bold uppercase">by <span class="underline hover:text-brand-blue">{{ $list->user->name ?? 'Unknown' }}</span></span>
                    </a>
                    <span class="text-gray-400">|</span>
                    <span class="text-sm font-bold text-gray-600 uppercase">{{ $list->books->count() }} Books</span>
                    <span class="text-gray-400">|</span>
                    <span class="text-sm font-bold text-gray-600 uppercase">Created {{ $list->created_at->format('M Y') }}</span>
                </div>
            </div>
            
            <div class="flex-shrink-0 flex gap-2">
                 <button class="neo-btn-primary text-sm px-4 py-2">
                    Share
                </button>
                 @if(Auth::id() === $list->user_id)
                <button class="neo-btn-secondary text-sm px-4 py-2">
                    Edit List
                </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Books Grid -->
    <section>
        <div class="flex items-center justify-between mb-6 border-b-2 border-black pb-2">
            <h2 class="text-2xl font-black uppercase flex items-center gap-2">
                <span class="w-4 h-4 bg-brand-yellow border-2 border-black block"></span>
                Books in this list
            </h2>
            <div class="flex gap-2">
                <!-- Sort options could go here -->
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
            @forelse($list->books as $book)
                <div class="h-full">
                    <x-book-card 
                        :id="$book->isbn" 
                        :title="$book->title" 
                        :author="$book->authors->first()->name ?? 'Unknown Author'"
                        :cover="$book->cover"
                        :rating="0" 
                    />
                </div>
            @empty
                <div class="col-span-full py-12 text-center border-2 border-dashed border-gray-400 bg-gray-50">
                    <p class="text-xl font-bold text-gray-500 uppercase">No books in this list yet.</p>
                </div>
            @endforelse
        </div>
    </section>
@endsection
