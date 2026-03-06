@extends('layouts.app')

@section('title', $author->name . ' - Perfil del Autor')

@section('content')
    <!-- Encabezado del Autor -->
    <div class="neo-card p-6 md:p-10 mb-12 relative overflow-hidden">
        <!-- Elemento decorativo de fondo -->
        <div
            class="absolute top-0 right-0 w-64 h-64 bg-brand-yellow rounded-full translate-x-1/2 -translate-y-1/2 border-2 border-black opacity-20">
        </div>

        <div class="flex flex-col md:flex-row items-center md:items-start gap-8 relative z-10">
            <!-- Foto del Autor -->
            <div class="flex-shrink-0">
                <div
                    class="w-48 h-48 bg-gray-300 rounded-full border-4 border-black shadow-[8px_8px_0px_#000] overflow-hidden">
                    <img src="{{ $author->photo ?? 'https://ui-avatars.com/api/?name=' . urlencode($author->name) . '&background=random&size=256' }}"
                         alt="{{ $author->name }}" class="w-full h-full object-cover">
                </div>
            </div>

            <!-- Información del Autor -->
            <div class="flex-grow text-center md:text-left">
                <div class="mb-4">
                    <h1 class="text-4xl md:text-6xl font-black font-display uppercase tracking-tighter mb-2">
                        {{ $author->name . ' ' . $author->surname }}
                    </h1>
                    <div
                        class="flex flex-wrap justify-center md:justify-start gap-4 text-sm font-bold uppercase tracking-wide text-gray-700">
                        @if($author->country)
                            <span class="flex items-center gap-1">
                                <span class="w-2 h-2 bg-black rounded-full"></span>
                                {{ $author->country->name }}
                            </span>
                        @endif
                        @if($author->date_of_birth)
                            <span class="flex items-center gap-1">
                                <span class="w-2 h-2 bg-black rounded-full"></span>
                                {{ \Carbon\Carbon::parse($author->date_of_birth)->format('d M, Y') }}
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Estadísticas -->
                <div class="grid grid-cols-3 gap-4 border-y-2 border-black py-4 mb-6 max-w-md mx-auto md:mx-0">
                    <div class="text-center md:text-left border-r-2 border-black last:border-0 pr-4">
                        <div class="text-2xl font-black">{{ $author->books->count() }}</div>
                        <div class="text-xs font-bold uppercase text-gray-500">Libros</div>
                    </div>
                    <div class="text-center md:text-left border-r-2 border-black last:border-0 px-4">
                        <div class="text-2xl font-black">1.2m</div>
                        <div class="text-xs font-bold uppercase text-gray-500">Seguidores</div>
                    </div>
                    <div class="text-center md:text-left pl-4">
                        <div class="text-2xl font-black flex items-center justify-center md:justify-start gap-1">
                            4.5 <span class="text-brand-yellow text-lg">★</span>
                        </div>
                        <div class="text-xs font-bold uppercase text-gray-500">Valoración Media</div>
                    </div>
                </div>

                @auth
                    <div x-data class="flex gap-4 justify-center md:justify-start">
                        <x-modals.follow-modal :followableId="$author->id" followableType="author" :isFollowing="false"
                                               :followUrl="route('authors.follow', $author)" />
                        <button
                            @click="$dispatch('open-share-modal', { title: 'Compartir Perfil del Autor', url: '{{ route('authors.show', $author->id) }}' })"
                            class="neo-btn-secondary">
                            Compartir Perfil
                        </button>
                    </div>
                @endauth
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <!-- Sección: Biografía -->
        <div class="lg:col-span-1">
            <div class="mb-4 border-b-2 border-black pb-2">
                <h2 class="text-2xl font-black uppercase flex items-center gap-2">
                    <span class="w-4 h-4 bg-brand-blue border-2 border-black block"></span>
                    Biografía
                </h2>
            </div>
            <div class="neo-card p-6 text-sm leading-relaxed font-medium">
                <p class="mb-4">
                    {{ $author->biography ?? 'No hay biografía disponible para este autor.' }}
                </p>
                <a href="#" class="block mt-4 font-bold uppercase underline hover:text-brand-blue">Leer biografía completa en Wikipedia -></a>
            </div>
        </div>

        <!-- Sección: Libros -->
        <div class="lg:col-span-2">
            <div class="flex items-center justify-between mb-4 border-b-2 border-black pb-2">
                <h2 class="text-2xl font-black uppercase flex items-center gap-2">
                    <span class="w-4 h-4 bg-brand-yellow border-2 border-black block"></span>
                    Bibliografía
                </h2>
                <!-- Filtro tipo de libro comentado
                    <div class="flex gap-2 text-xs font-bold uppercase">
                        <button class="bg-black text-white px-3 py-1">Todos</button>
                        <button class="bg-white border-2 border-black px-3 py-1 hover:bg-gray-100">Novela</button>
                    </div>
                -->
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-6" id="load-more-grid">
                @foreach($books as $book)
                    <x-book-card :title="$book->title" :author="$author->name . ' ' . $author->surname" :cover="$book->cover"
                                 :id="$book->isbn" />
                @endforeach
            </div>

            @if($books->hasMorePages())
                <x-modals.load-more :url="route('authors.books', $author)" label="Cargar más libros" />
            @endif
        </div>
    </div>
@endsection

