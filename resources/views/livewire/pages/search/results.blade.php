<?php
/**
 * Página Livewire SFC — Resultados de Búsqueda
 *
 * Sustituye a SearchController@search + pages/search/results.blade.php.
 * Recibe el parámetro ?q= desde la URL (sincronizado con #[Url]) y ejecuta
 * búsquedas insensibles a mayúsculas/minúsculas.
 *
 * Busca coincidencias parciales en:
 *   - Libros (título, ISBN)
 *   - Autores (nombre, apellido)
 *   - Usuarios (nombre, solo perfiles públicos)
 *   - Listas (nombre, solo listas con visibility='public')
 *   - Géneros (nombre)
 *
 * Las secciones sin resultados no se renderizan.
 */

use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use App\Models\Book;
use App\Models\Author;
use App\Models\User;
use App\Models\FavList;
use App\Models\Genre;

new #[Layout('layouts.app')] class extends Component {

    // ─── Término sincronizado con ?q= en la URL ──────────────────────────
    #[Url(as: 'q')]
    public string $busqueda = '';

    /**
     * Título dinámico de la pestaña del navegador.
     */
    public function title(): string
    {
        return !empty(trim($this->busqueda))
            ? 'Resultados para "' . $this->busqueda . '" — BlockBookster'
            : 'Búsqueda — BlockBookster';
    }

    /**
     * Helper: término con wildcards para LIKE.
     */
    private function termino(): string
    {
        return '%' . mb_strtolower(trim($this->busqueda)) . '%';
    }

    // ─── Propiedades computadas ───────────────────────────────────────────

    /** Libros que coinciden con el término (máx. 10). */
    #[Computed]
    public function libros()
    {
        if (empty(trim($this->busqueda))) return collect();
        $t = $this->termino();
        return Book::with('authors')
            ->whereRaw('LOWER(title) LIKE ?', [$t])
            ->orWhereRaw('LOWER(isbn) LIKE ?', [$t])
            ->limit(10)
            ->get();
    }

    /** Autores que coinciden con el término (máx. 10). */
    #[Computed]
    public function autores()
    {
        if (empty(trim($this->busqueda))) return collect();
        $t = $this->termino();
        return Author::where(function ($q) use ($t) {
                $q->whereRaw('LOWER(name) LIKE ?', [$t])
                  ->orWhereRaw('LOWER(surname) LIKE ?', [$t]);
            })
            ->withCount('books')
            ->limit(10)
            ->get();
    }

    /** Usuarios con perfil público que coinciden (máx. 10). */
    #[Computed]
    public function usuarios()
    {
        if (empty(trim($this->busqueda))) return collect();
        $t = $this->termino();
        return User::whereRaw('LOWER(name) LIKE ?', [$t])
            ->where('profile_visibility', 'public')
            ->withCount('followers')
            ->limit(10)
            ->get();
    }

    /** Listas públicas que coinciden (máx. 10). */
    #[Computed]
    public function listas()
    {
        if (empty(trim($this->busqueda))) return collect();
        $t = $this->termino();
        // Columna correcta: 'visibility', no 'is_public'
        // Se carga 'books' para que x-list-card pueda mostrar la tira de portadas
        return FavList::whereRaw('LOWER(name) LIKE ?', [$t])
            ->where('visibility', 'public')
            ->with(['user', 'books'])
            ->withCount('books')
            ->limit(10)
            ->get();
    }

    /** Géneros que coinciden (máx. 10). */
    #[Computed]
    public function generos()
    {
        if (empty(trim($this->busqueda))) return collect();
        $t = $this->termino();
        return Genre::whereRaw('LOWER(name) LIKE ?', [$t])
            ->limit(10)
            ->get();
    }

    /** Total de resultados en todas las categorías. */
    #[Computed]
    public function totalResultados(): int
    {
        return $this->libros->count()
            + $this->autores->count()
            + $this->usuarios->count()
            + $this->listas->count()
            + $this->generos->count();
    }
}; ?>

<div>
    {{-- ─── Encabezado ──────────────────────────────────────────────────── --}}
    <div class="mb-8 border-b-4 border-black pb-4">
        <h1 class="text-4xl md:text-5xl font-black uppercase font-display">
            @if(!empty(trim($busqueda)))
                Resultados de búsqueda para "<span class="text-brand-blue">{{ $busqueda }}</span>"
            @else
                Búsqueda
            @endif
        </h1>
        @if(!empty(trim($busqueda)))
            <p class="text-lg font-bold text-gray-600 mt-2 uppercase">
                {{ $this->totalResultados }} {{ $this->totalResultados === 1 ? 'resultado encontrado' : 'resultados encontrados' }}
            </p>
        @endif
    </div>

    {{-- ─── Barra de búsqueda reutilizable ──────────────────────────────── --}}
    <div class="mb-12">
        <livewire:components.search.barra-busqueda :grande="true" />
    </div>

    {{-- ─── Estado: sin búsqueda ────────────────────────────────────────── --}}
    @if(empty(trim($busqueda)))
        <x-card class="text-center py-16">
            <svg class="w-24 h-24 mx-auto mb-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <h2 class="text-3xl font-black uppercase mb-3">Escribe algo para buscar</h2>
            <p class="text-gray-600 font-bold text-lg">Libros, autores, usuarios, listas o géneros</p>
        </x-card>

    {{-- ─── Estado: sin resultados ──────────────────────────────────────── --}}
    @elseif($this->totalResultados === 0)
        <x-card class="text-center py-16">
            <svg class="w-24 h-24 mx-auto mb-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <h2 class="text-3xl font-black uppercase mb-3">No se encontraron resultados</h2>
            <p class="text-gray-600 font-bold text-lg">Prueba con otras palabras clave o verifica la ortografía</p>
        </x-card>

    {{-- ─── Resultados por sección ──────────────────────────────────────── --}}
    @else
        {{-- Sección Libros --}}
        @if($this->libros->isNotEmpty())
            <section class="mb-12">
                <h2 class="text-2xl font-black uppercase mb-6 flex items-center gap-2 border-b-2 border-black pb-2">
                    <span class="w-3 h-3 bg-brand-blue border border-black"></span>
                    Libros ({{ $this->libros->count() }})
                </h2>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                    @foreach($this->libros as $book)
                        <div wire:key="res-libro-{{ $book->isbn }}">
                            <x-book-card
                                :id="$book->isbn"
                                :title="$book->title"
                                :author="$book->authors->pluck('name')->join(', ') ?: 'Desconocido'"
                                :cover="$book->cover_image ?? 'https://via.placeholder.com/300x450'"
                                :rating="0"
                            />
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Sección Autores — mismo componente y diseño que authors/index y home/index --}}
        @if($this->autores->isNotEmpty())
            <section class="mb-12">
                <h2 class="text-2xl font-black uppercase mb-6 flex items-center gap-2 border-b-2 border-black pb-2">
                    <span class="w-3 h-3 bg-brand-yellow border border-black"></span>
                    Autores ({{ $this->autores->count() }})
                </h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
                    @foreach($this->autores as $author)
                        <div wire:key="res-autor-{{ $author->id }}">
                            <x-author-card :author="$author" />
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Sección Usuarios --}}
        @if($this->usuarios->isNotEmpty())
            <section class="mb-12">
                <h2 class="text-2xl font-black uppercase mb-6 flex items-center gap-2 border-b-2 border-black pb-2">
                    <span class="w-3 h-3 bg-brand-pink border border-black"></span>
                    Usuarios ({{ $this->usuarios->count() }})
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($this->usuarios as $user)
                        <div wire:key="res-user-{{ $user->id }}">
                            <x-card class="flex items-center gap-4 hover:-translate-y-1 transition-transform">
                                <a href="{{ route('users.show', $user->id) }}" wire:navigate class="flex items-center gap-4 flex-1">
                                    <div class="w-16 h-16 bg-gray-200 rounded-full border-2 border-black overflow-hidden flex-shrink-0">
                                        <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random' }}"
                                             alt="{{ $user->name }}" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-bold uppercase text-sm hover:text-brand-blue truncate">{{ $user->name }}</h3>
                                        <p class="text-xs text-gray-500 font-bold">{{ $user->followers_count }} {{ Str::plural('Seguidor', $user->followers_count) }}</p>
                                    </div>
                                </a>
                            </x-card>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Sección Listas — mismo componente y diseño que home/index y lists/index --}}
        @if($this->listas->isNotEmpty())
            <section class="mb-12">
                <h2 class="text-2xl font-black uppercase mb-6 flex items-center gap-2 border-b-2 border-black pb-2">
                    <span class="w-3 h-3 bg-green-500 border border-black"></span>
                    Listas ({{ $this->listas->count() }})
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($this->listas as $list)
                        <div wire:key="res-lista-{{ $list->id }}">
                            <x-list-card :list="$list" />
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Sección Géneros --}}
        @if($this->generos->isNotEmpty())
            <section class="mb-12">
                <h2 class="text-2xl font-black uppercase mb-6 flex items-center gap-2 border-b-2 border-black pb-2">
                    <span class="w-3 h-3 bg-purple-500 border border-black"></span>
                    Géneros ({{ $this->generos->count() }})
                </h2>
                <div class="flex flex-wrap gap-3">
                    @foreach($this->generos as $genre)
                        <a href="{{ route('books.index', ['genre' => $genre->id]) }}" wire:navigate wire:key="res-gen-{{ $genre->id }}"
                           class="neo-btn-secondary py-2 px-4 text-sm hover:bg-purple-500 hover:text-white transition-colors">
                            {{ $genre->name }}
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    @endif
</div>
