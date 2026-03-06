@extends('layouts.app')

@section('title', 'Mis Reseñas')

@section('content')
    <div x-data class="flex flex-col lg:flex-row gap-8">
        <!-- Barra Lateral -->
        @include('pages.dashboard.partials.sidebar')

        <!-- Contenido Principal -->
        <div class="flex-1">
            <header class="mb-8 border-b-4 border-black pb-4">
                <h1 class="text-3xl font-black uppercase font-display">Mis Reseñas</h1>
                <p class="text-gray-600 font-bold mt-1">Gestiona tus valoraciones de libros</p>
            </header>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($reviews as $review)
                    <x-review-card :review="$review" :showBook="true" :showActions="true" />
                @empty
                    <div
                        class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-12 border-2 border-dashed border-gray-300 bg-gray-50">
                        <p class="text-xl font-bold uppercase text-gray-400 mb-2">Aún no hay reseñas</p>
                        <a href="{{ route('books.index') }}" class="neo-btn-primary inline-block text-sm">Explorar Libros</a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
