@extends('layouts.app')

@section('title', 'Browse Books')

@section('content')
    <!-- Top Bar: Advanced Search -->
    <x-advanced-search />

    <div class="flex flex-col lg:flex-row gap-12">

        <!-- Sidebar Filters (Desktop) -->
        <x-sidebar-filters :genres="$genres" :countries="$countries" />

        <!-- Main Grid -->
        <div class="flex-1">
            <div class="flex justify-between items-end mb-8">
                <h1 class="text-4xl font-display font-black uppercase flex items-center">
                    <span
                        class="bg-brand-yellow px-2 border-2 border-black mr-4 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] text-2xl">{{ $books->total() }}</span>
                    Books
                </h1>
                <!-- Mobile Filter Toggle (Visible only on small screens) -->
                <button class="lg:hidden font-bold uppercase border-2 border-black px-4 py-2 hover:bg-gray-100">
                    Filters (+3)
                </button>
            </div>


            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($books as $book)
                    <div class="h-full">
                        <x-book-card id="{{$book->isbn}}" :title="$book->title"
                            :author="$book->authors->first()->name ?? 'Unknown Author'"
                            :cover="$book->cover ?? 'https://via.placeholder.com/600x900'"
                            :rating="4.5" />
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-xl font-bold uppercase text-gray-500">No books found.</p>
                    </div>
                @endforelse
            </div>

            <!-- Paginación -->
            <x-modals.pagination :paginator="$books" />

        </div>
    </div>
@endsection
