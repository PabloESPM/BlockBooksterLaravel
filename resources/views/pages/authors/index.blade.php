@extends('layouts.app')

@section('title', 'Autores')

@section('content')
    <div class="mb-12 border-b-4 border-black pb-4">
        <h1 class="text-4xl md:text-6xl font-black font-display uppercase tracking-tighter">
            Conoce a los <span class="text-brand-yellow text-shadow-neo">Autores</span>
        </h1>
        <p class="text-lg font-bold mt-2 text-gray-600 uppercase tracking-widest">
            Las mentes detrás de las historias
        </p>
    </div>

    <!-- Sección: Autores Populares -->
    <section class="mb-16">
        <div class="flex items-center justify-between mb-6 border-b-2 border-black pb-2">
            <h2 class="text-2xl font-black uppercase flex items-center gap-2">
                <span class="w-4 h-4 bg-brand-blue border-2 border-black block"></span>
                Populares Ahora
            </h2>
            <a href="#" class="text-sm font-bold uppercase hover:underline hover:text-brand-blue">Ver todos -></a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
            @foreach ($popularAuthors as $author)
                <x-author-card :author="$author" />
            @endforeach
        </div>
    </section>

    <!-- Sección: Clásicos -->
    <section class="mb-16">
        <div class="flex items-center justify-between mb-6 border-b-2 border-black pb-2">
            <h2 class="text-2xl font-black uppercase flex items-center gap-2">
                <span class="w-4 h-4 bg-black border-2 border-black block"></span>
                Los Clásicos
            </h2>
            <a href="#" class="text-sm font-bold uppercase hover:underline hover:text-brand-blue">Ver todos -></a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach ($classicAuthors as $author)
                <div class="neo-card p-6 flex items-center gap-6">
                    <div class="w-20 h-20 bg-gray-200 border-2 border-black flex-shrink-0 overflow-hidden">
                        <img src="{{ $author->photo ?? 'https://ui-avatars.com/api/?name=' . urlencode($author->name) . '&background=random' }}"
                             alt="{{ $author->name }}" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <a href="{{ route('authors.show', $author->id) }}">
                            <h3 class="text-xl font-black uppercase hover:underline">{{ $author->name }}</h3>
                        </a>
                        <p class="text-sm font-bold text-gray-600 mb-2">Autor Clásico</p>
                        <div class="flex items-center gap-1">
                            <span class="text-xs font-black bg-brand-yellow px-2 border border-black">4.8</span>
                            <span class="text-xs font-bold uppercase text-gray-500">Calificación Media</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Sección: Más Valorados -->
    <section class="mb-16">
        <div class="flex items-center justify-between mb-6 border-b-2 border-black pb-2">
            <h2 class="text-2xl font-black uppercase flex items-center gap-2">
                <span class="w-4 h-4 bg-brand-yellow border-2 border-black block"></span>
                Más Valorados
            </h2>
            <a href="#" class="text-sm font-bold uppercase hover:underline hover:text-brand-blue">Ver todos -></a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            @foreach ($mostRatedAuthors as $author)
                <div class="neo-card p-3 text-center">
                    <a href="{{ route('authors.show', $author->id) }}">
                        <h3 class="font-bold uppercase text-sm truncate hover:underline text-brand-blue">{{ $author->name }}
                        </h3>
                    </a>
                    <div class="text-3xl font-black text-brand-blue my-2">{{ $author->books_count * 120 }}</div>
                    <div class="text-xs font-bold uppercase text-gray-500">Valoraciones Estimadas</div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Sección: Nuevos Autores -->
    <section>
        <div class="flex items-center justify-between mb-6 border-b-2 border-black pb-2">
            <h2 class="text-2xl font-black uppercase flex items-center gap-2">
                <span class="w-4 h-4 bg-green-500 border-2 border-black block"></span>
                Estrellas Emergentes
            </h2>
            <a href="#" class="text-sm font-bold uppercase hover:underline hover:text-brand-blue">Ver todos -></a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($newAuthors as $author)
                <div class="neo-card p-4 hover:-translate-y-1 transition-transform">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-black rounded-full text-white flex items-center justify-center font-bold">Nuevo
                        </div>
                        <div>
                            <a href="{{ route('authors.show', $author->id) }}">
                                <h3 class="font-bold uppercase text-sm hover:underline">{{ $author->name }}</h3>
                            </a>
                            <p class="text-xs text-gray-500">Se unió {{ $author->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-700 italic border-l-2 border-gray-300 pl-3">"Descubre sus últimas obras..."</p>
                </div>
            @endforeach
        </div>
    </section>
@endsection
