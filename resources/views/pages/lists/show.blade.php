@extends('layouts.app')

@section('title', $list->name . ' - Reading List')

@section('content')
    <div x-data="{ showEditModal: false }">
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
                    <button @click="showEditModal = true" class="neo-btn-secondary text-sm px-4 py-2">
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

        <!-- Edit Modal -->
        <div
            x-show="showEditModal"
            style="display: none;"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        >
            <div
                @click.away="showEditModal = false"
                class="bg-white border-2 border-black shadow-[8px_8px_0px_#000] w-full max-w-md p-6 relative"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="scale-95 opacity-0 translate-y-4"
                x-transition:enter-end="scale-100 opacity-100 translate-y-0"
            >
                <button @click="showEditModal = false" class="absolute top-4 right-4 text-2xl font-black hover:text-red-600">&times;</button>

                <h2 class="text-2xl font-black uppercase mb-6 font-display">Edit List</h2>

                <form action="{{ route('dashboard.lists.update', $list) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label for="edit_name" class="block font-bold uppercase text-sm mb-2">List Name</label>
                        <input type="text" name="name" id="edit_name" required value="{{ $list->name }}"
                            class="w-full border-2 border-black p-2 focus:outline-none focus:shadow-[4px_4px_0px_#000] focus:ring-0 transition-shadow">
                    </div>

                    <div class="mb-4">
                        <label for="edit_description" class="block font-bold uppercase text-sm mb-2">Description</label>
                        <textarea name="description" id="edit_description" rows="3"
                            class="w-full border-2 border-black p-2 focus:outline-none focus:shadow-[4px_4px_0px_#000] focus:ring-0 transition-shadow">{{ $list->description }}</textarea>
                    </div>

                    <div class="mb-6">
                        <label for="edit_visibility" class="block font-bold uppercase text-sm mb-2">Visibility</label>
                        <div class="relative">
                            <select name="visibility" id="edit_visibility"
                                class="w-full border-2 border-black p-2 appearance-none bg-white focus:outline-none focus:shadow-[4px_4px_0px_#000] focus:ring-0 transition-shadow">
                                <option value="public" {{ $list->visibility === 'public' ? 'selected' : '' }}>Public (Visible to everyone)</option>
                                <option value="friends" {{ $list->visibility === 'friends' ? 'selected' : '' }}>Friends Only</option>
                                <option value="private" {{ $list->visibility === 'private' ? 'selected' : '' }}>Private (Only me)</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-black border-l-2 border-black bg-gray-100">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4 justify-end">
                        <button type="button" @click="showEditModal = false"
                            class="px-4 py-2 bg-white border-2 border-black font-bold uppercase hover:bg-gray-100 transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-6 py-2 bg-[#FFA903] border-2 border-black font-bold uppercase shadow-[4px_4px_0px_#000] hover:translate-y-px hover:translate-x-px hover:shadow-[2px_2px_0px_#000] transition-all">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
