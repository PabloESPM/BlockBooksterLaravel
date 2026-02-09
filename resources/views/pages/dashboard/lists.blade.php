@extends('layouts.app')

@section('title', 'My Lists')

@section('content')
    <div class="flex flex-col lg:flex-row gap-8" x-data="{ showModal: false, showDeleteModal: false, deleteAction: '' }">
        <!-- Sidebar -->
        @include('pages.dashboard.partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1">
            <header class="mb-8 border-b-4 border-black pb-4 flex justify-between items-end">
                <div>
                    <h1 class="text-3xl font-black uppercase font-display">My Lists</h1>
                    <p class="text-gray-600 font-bold mt-1">Collections you've created</p>
                </div>
                <button @click="showModal = true" class="neo-btn-primary text-sm flex items-center gap-2">
                    <span>+ Create New List</span>
                </button>
            </header>

            {{-- Success Message --}}
            @if(session('success'))
                <div class="mb-6 p-4 border-2 border-black bg-green-100 font-bold uppercase relative">
                    {{ session('success') }}
                    <button onclick="this.parentElement.remove()" class="absolute top-2 right-2 text-xl">&times;</button>
                </div>
            @endif

            {{-- Error Messages --}}
            @if ($errors->any())
                <div class="mb-6 p-4 border-2 border-black bg-red-100 font-bold relative">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button onclick="this.parentElement.remove()" class="absolute top-2 right-2 text-xl">&times;</button>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($lists as $list)
                    <x-card class="group hover:-translate-y-1 transition-transform cursor-pointer relative h-full flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-4 pb-2 border-b-2 border-black/10">
                                <h3 class="font-black uppercase text-lg group-hover:text-brand-blue truncate pr-2">{{ $list->name }}</h3>
                                <span class="bg-gray-100 text-gray-800 text-xs font-bold uppercase px-2 py-0.5 border border-black">
                                    {{ ucfirst($list->visibility) }}
                                </span>
                            </div>
                            
                            {{-- Preview of books --}}
                            <div class="flex gap-1 mb-4 h-16 bg-gray-100 overflow-hidden border border-black p-1">
                                @if($list->books->count() > 0)
                                    @foreach($list->books->take(3) as $book)
                                         <img src="{{ $book->cover_url ?? 'https://via.placeholder.com/40' }}" class="w-10 h-full object-cover border-r border-black last:border-0" alt="Cover">
                                    @endforeach
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs font-bold uppercase">
                                        Empty
                                    </div>
                                @endif
                            </div>
                            
                            @if($list->description)
                                <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $list->description }}</p>
                            @endif
                        </div>

                        <div>
                             <div class="flex justify-between items-center text-xs font-bold text-gray-500 uppercase mb-4">
                                <span>{{ $list->books->count() }} Books</span>
                                <span>Updated {{ $list->updated_at->diffForHumans() }}</span>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('lists.show', $list) }}" class="neo-btn-secondary py-1 px-3 text-xs w-full text-center">View</a>
                                <button
                                    @click="showDeleteModal = true; deleteAction = '{{ route('dashboard.lists.destroy', $list) }}'"
                                    class="bg-red-100 border-2 border-black py-1 px-3 text-xs font-bold uppercase hover:bg-red-500 hover:text-white transition-colors">
                                    Delete
                                </button>
                            </div>
                        </div>
                    </x-card>
                @empty
                    <div class="col-span-full text-center py-12 border-2 border-dashed border-black bg-gray-50">
                        <p class="font-bold text-gray-500 uppercase">No lists created yet.</p>
                        <button @click="showModal = true" class="mt-4 text-brand-blue underline font-bold">Create your first list</button>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Create Modal -->
        <div
            x-show="showModal"
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
                @click.away="showModal = false"
                class="bg-white border-2 border-black shadow-[8px_8px_0px_#000] w-full max-w-md p-6 relative"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="scale-95 opacity-0 translate-y-4"
                x-transition:enter-end="scale-100 opacity-100 translate-y-0"
            >
                <button @click="showModal = false" class="absolute top-4 right-4 text-2xl font-black hover:text-red-600">&times;</button>

                <h2 class="text-2xl font-black uppercase mb-6 font-display">Create New List</h2>

                <form action="{{ route('dashboard.lists.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="name" class="block font-bold uppercase text-sm mb-2">List Name</label>
                        <input type="text" name="name" id="name" required
                            class="w-full border-2 border-black p-2 focus:outline-none focus:shadow-[4px_4px_0px_#000] focus:ring-0 transition-shadow"
                            placeholder="e.g. Summer Reads 2026">
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block font-bold uppercase text-sm mb-2">Description</label>
                        <textarea name="description" id="description" rows="3"
                            class="w-full border-2 border-black p-2 focus:outline-none focus:shadow-[4px_4px_0px_#000] focus:ring-0 transition-shadow"
                            placeholder="Short description..."></textarea>
                    </div>

                    <div class="mb-6">
                        <label for="visibility" class="block font-bold uppercase text-sm mb-2">Visibility</label>
                        <div class="relative">
                            <select name="visibility" id="visibility"
                                class="w-full border-2 border-black p-2 appearance-none bg-white focus:outline-none focus:shadow-[4px_4px_0px_#000] focus:ring-0 transition-shadow">
                                <option value="public">Public (Visible to everyone)</option>
                                <option value="friends">Friends Only</option>
                                <option value="private">Private (Only me)</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-black border-l-2 border-black bg-gray-100">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4 justify-end">
                        <button type="button" @click="showModal = false"
                            class="px-4 py-2 bg-white border-2 border-black font-bold uppercase hover:bg-gray-100 transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-6 py-2 bg-[#FFA903] border-2 border-black font-bold uppercase shadow-[4px_4px_0px_#000] hover:translate-y-px hover:translate-x-px hover:shadow-[2px_2px_0px_#000] transition-all">
                            Create
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete Modal -->
         <div
            x-show="showDeleteModal"
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
                @click.away="showDeleteModal = false"
                class="bg-white border-2 border-black shadow-[8px_8px_0px_#000] w-full max-w-md p-6 relative"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="scale-95 opacity-0 translate-y-4"
                x-transition:enter-end="scale-100 opacity-100 translate-y-0"
            >
                <button @click="showDeleteModal = false" class="absolute top-4 right-4 text-2xl font-black hover:text-red-600">&times;</button>

                <h2 class="text-2xl font-black uppercase mb-4 font-display text-red-600">Delete List?</h2>
                
                <p class="font-bold text-gray-800 mb-6">Are you sure you want to delete this list? This action cannot be undone.</p>

                <form :action="deleteAction" method="POST">
                    @csrf
                    @method('DELETE')
                    
                    <div class="flex gap-4 justify-end">
                        <button type="button" @click="showDeleteModal = false"
                            class="px-4 py-2 bg-white border-2 border-black font-bold uppercase hover:bg-gray-100 transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-6 py-2 bg-red-500 text-white border-2 border-black font-bold uppercase shadow-[4px_4px_0px_#000] hover:translate-y-px hover:translate-x-px hover:shadow-[2px_2px_0px_#000] transition-all">
                            Delete Permanently
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection