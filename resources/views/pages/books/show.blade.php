@extends('layouts.app')

@section('title', $book->title . ' - Detalles del Libro')

@section('content')
    <!-- Sección Hero -->
    <div x-data class="grid grid-cols-1 md:grid-cols-12 gap-8 mb-16">
        <!-- Portada (Izquierda) -->
        <div class="md:col-span-4 lg:col-span-3">
            <div class="neo-card p-0 relative group">
                <div class="aspect-[2/3] bg-gray-200 border-b-2 border-black relative overflow-hidden">
                    @if($book->cover)
                        <img src="{{ $book->cover }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                    @else
                        <!-- Portada de ejemplo -->
                        <div class="absolute inset-0 flex items-center justify-center bg-brand-yellow">
                            <span class="text-4xl font-black uppercase text-black opacity-20 -rotate-45">Portada</span>
                        </div>
                    @endif
                </div>
            </div>
            <!-- Acciones móvil (solo visible en pantallas pequeñas) -->
            <div class="mt-4 md:hidden space-y-2">
                @auth
                    <button class="w-full neo-btn-primary mb-2">Quiero leer</button>
                @endauth
                <a href="#" class="block w-full text-center neo-btn-secondary text-sm">Comprar en Amazon</a>
            </div>
        </div>

        <!-- Información (Derecha) -->
        <div class="md:col-span-8 lg:col-span-9 flex flex-col justify-between">
            <div>
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h1
                            class="text-3xl md:text-5xl font-black font-display uppercase tracking-tighter leading-none mb-2">
                            {{ $book->title }}
                        </h1>
                        <h2 class="text-xl font-bold uppercase text-gray-600">por
                            @foreach($book->authors as $author)
                                <a href="{{ route('authors.show', $author->id) }}"
                                    class="text-brand-blue hover:underline">{{ $author->name }}</a>{{ !$loop->last ? ',' : '' }}
                            @endforeach
                        </h2>
                    </div>
                    <!-- Valoración -->
                    <div class="hidden md:block text-right">

                        @php
                            $totalReviewsForRating = $book->reviews->count();
                            $averageRating = 0;
                            if ($totalReviewsForRating > 0) {
                                $sum = $book->reviews->sum('rating');
                                $average = $sum / $totalReviewsForRating;
                                $averageRating = round($average * 2) / 2;
                            }
                        @endphp

                        <div class="flex items-center gap-1 justify-end">
                            <svg class="w-0 h-0 absolute">
                                <defs>
                                    <linearGradient id="half-star-gradient">
                                        <stop offset="50%" stop-color="currentColor" class="text-brand-yellow" />
                                        <stop offset="50%" stop-color="currentColor" class="text-gray-300" />
                                    </linearGradient>
                                </defs>
                            </svg>
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $averageRating)
                                    <!-- Estrella completa -->
                                    <svg class="w-8 h-8 text-brand-yellow fill-current drop-shadow-[2px_2px_0px_rgba(0,0,0,1)]"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                    </svg>
                                @elseif($i - 0.5 == $averageRating)
                                    <!-- Media estrella -->
                                    <svg class="w-8 h-8 drop-shadow-[2px_2px_0px_rgba(0,0,0,1)]" viewBox="0 0 24 24">
                                        <path fill="url(#half-star-gradient)"
                                            d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                    </svg>
                                @else
                                    <!-- Estrella vacía -->
                                    <svg class="w-8 h-8 text-gray-300 fill-current drop-shadow-[2px_2px_0px_rgba(0,0,0,1)]"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                    </svg>
                                @endif
                            @endfor
                        </div>
                        <div class="text-2xl font-black mt-1">{{ number_format($averageRating, 1) }} <span
                                class="text-sm font-bold text-gray-500 uppercase">/ 5.0</span></div>
                        <div class="text-xs font-bold uppercase text-gray-500">Basado en {{ $totalReviewsForRating }}
                            valoraciones
                        </div>
                    </div>
                </div>

                <!-- Metadatos -->
                <div class="flex flex-wrap gap-4 mb-8 text-sm font-bold uppercase border-y-2 border-black py-3">
                    @if($book->genre)
                        <span class="bg-black text-white px-2 py-0.5">{{ $book->genre->name }}</span>
                    @endif
                    <span class="bg-gray-200 border border-black px-2 py-0.5">{{ $book->publication_year }}</span>
                    <span class="bg-gray-200 border border-black px-2 py-0.5">{{ $book->number_of_pages ?? 'Desconocido' }}
                        Páginas</span>
                    @if($book->language)
                        <span class="bg-gray-200 border border-black px-2 py-0.5">{{ $book->language->name }}</span>
                    @endif
                    <span class="text-gray-500 py-0.5">ISBN: {{ $book->isbn }}</span>
                </div>

                <!-- Sinopsis -->
                <div class="mb-8 font-medium leading-relaxed text-gray-800">
                    <p class="mb-4">{{ $book->description }}</p>
                </div>
            </div>

            <!-- Acciones escritorio -->
            <div class="hidden md:flex flex-wrap items-center gap-4">
                @auth
                    <!-- Acciones usuario autenticado -->
                    <div class="flex items-center gap-2 border-r-2 border-black pr-4 mr-2">
                        <button class="neo-btn-primary flex items-center gap-2">
                            <span>Quiero leer</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </button>
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="neo-btn-secondary px-3">▼</button>
                            <div x-show="open" @click.outside="open = false"
                                class="absolute top-full left-0 mt-2 w-48 bg-white border-2 border-black shadow-[4px_4px_0px_#000] z-20 flex flex-col">
                                <button
                                    class="text-left px-4 py-2 font-bold uppercase hover:bg-brand-yellow border-b border-black">Leyendo
                                    actualmente</button>
                                <button class="text-left px-4 py-2 font-bold uppercase hover:bg-brand-yellow">Leído</button>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Aviso invitado -->
                    <div class="flex items-center gap-4 border-r-2 border-black pr-4 mr-2">
                        <a href="{{ route('login') }}"
                            class="text-sm font-bold uppercase underline hover:text-brand-blue">Inicia sesión
                            para registrar tu lectura</a>
                    </div>
                @endauth

                <!-- Compartir -->
                <button
                    @click="$dispatch('open-share-modal', { title: 'Compartir libro', url: '{{ route('books.show', $book->isbn) }}' })"
                    class="neo-btn-secondary text-sm flex items-center gap-2">
                    Compartir
                </button>

                <!-- Enlaces afiliados -->
                <a href="#" class="neo-btn-secondary text-sm flex items-center gap-2">
                    Comprar en Amazon
                </a>
            </div>
        </div>
    </div>

    <!-- Segunda fila: Reseñas y Relacionados -->
    <div x-data class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <!-- Sección Reseñas (2/3 ancho) -->
        <div class="lg:col-span-2">
            <div class="flex items-center justify-between mb-6 border-b-2 border-black pb-2">
                <h2 class="text-2xl font-black uppercase flex items-center gap-2">
                    <span class="w-4 h-4 bg-brand-yellow border-2 border-black block"></span>
                    Reseñas de la comunidad
                </h2>
                @auth
                    <button @click.prevent="$dispatch('open-add-review-modal', { bookId: '{{ $book->isbn }}' })"
                        class="text-sm font-bold uppercase bg-brand-blue text-white px-3 py-1 hover:bg-gray-800 transition-colors">Escribir
                        reseña</button>
                @endauth
            </div>

            <div class="space-y-6" id="reviews-grid">
                @forelse($reviews as $review)
                    <x-review-card :review="$review" />
                @empty
                    <div class="text-center py-12 border-2 border-dashed border-gray-300 bg-gray-50">
                        <p class="text-xl font-bold uppercase text-gray-400">Aún no hay reseñas.</p>
                        @auth
                            <button @click.prevent="$dispatch('open-add-review-modal', { bookId: '{{ $book->isbn }}' })"
                                class="neo-btn-primary mt-4 text-sm px-4">Sé el primero en reseñar</button>
                        @endauth
                    </div>
                @endforelse
            </div>

            <!-- Lamada al boton cargar mas -->
            @if($reviews->hasMorePages())
                <x-modals.load-more :url="route('books.load-reviews', $book->isbn)" target="reviews-grid"
                    :initialHasMore="$reviews->hasMorePages()" />
            @endif
        </div>

        <!-- Barra lateral: Relacionados (1/3 ancho) -->
        <div class="lg:col-span-1">
            <div class="mb-6 border-b-2 border-black pb-2">
                <h2 class="text-xl font-black uppercase">Autores relacionados</h2>
            </div>
            <div class="space-y-4">
                @for($i = 0; $i < 3; $i++)
                    <div class="neo-card p-4 flex items-center gap-4 cursor-pointer hover:bg-gray-50 transition-colors">
                        <div class="w-12 h-12 bg-gray-200 rounded-full border-2 border-black"></div>
                        <div>
                            <h4 class="font-bold uppercase text-sm">Nombre del autor</h4>
                            <p class="text-xs text-gray-500 uppercase">Ciencia ficción • Thriller</p>
                        </div>
                    </div>
                @endfor
            </div>

            <div class="my-8 border-b-2 border-black pb-2">
                <h2 class="text-xl font-black uppercase">A los lectores también les gustó</h2>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="neo-card p-0 h-32 bg-gray-200 border-2 border-black relative">
                    <div
                        class="absolute inset-0 flex items-center justify-center text-xs font-bold uppercase opacity-30 rotate-45">
                        Portada del libro</div>
                </div>
                <div class="neo-card p-0 h-32 bg-gray-200 border-2 border-black relative">
                    <div
                        class="absolute inset-0 flex items-center justify-center text-xs font-bold uppercase opacity-30 rotate-45">
                        Portada del libro</div>
                </div>
            </div>
        </div>
    </div>
@endsection
