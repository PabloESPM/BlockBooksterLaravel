<?php
/**
 * Componente Livewire SFC — Barra de Búsqueda con Desplegable
 *
 * Sub-componente reutilizable que ofrece búsqueda en tiempo real con
 * sugerencias desplegables. Busca en libros, autores, usuarios y géneros
 * de forma insensible a mayúsculas/minúsculas mediante LOWER() + LIKE.
 *
 * Modos de uso:
 *   - Modo grande ($grande=true): campo grande con botón visible (para home)
 *   - Modo compacto ($grande=false): campo pequeño (para navbar)
 *
 * Al pulsar Enter o el botón "Buscar", redirige a la página de resultados
 * completos /search?q=término.
 */

use Livewire\Attributes\Computed;
use Livewire\Component;
use App\Models\Book;
use App\Models\Author;
use App\Models\User;
use App\Models\Genre;

new class extends Component {

    // ─── Propiedades públicas ─────────────────────────────────────────────
    /** Término de búsqueda actual (bind en tiempo real). */
    public string $termino = '';

    /** Controla si el desplegable de sugerencias está visible. */
    public bool $mostrarSugerencias = false;

    /** Modo grande (home) vs. compacto (navbar). */
    public bool $grande = false;

    // ─── Hooks ────────────────────────────────────────────────────────────

    /**
     * Cuando el término cambia, mostrar el desplegable si hay ≥2 caracteres.
     */
    public function updatedTermino(): void
    {
        $this->mostrarSugerencias = mb_strlen(trim($this->termino)) >= 2;
    }

    // ─── Propiedades computadas (sugerencias) ─────────────────────────────

    /**
     * Sugerencias de libros (máx. 4) — por título o ISBN.
     */
    #[Computed]
    public function sugerenciasLibros()
    {
        if (mb_strlen(trim($this->termino)) < 2)
            return collect();
        $t = '%' . mb_strtolower(trim($this->termino)) . '%';
        return Book::with('authors')
            ->whereRaw('LOWER(title) LIKE ?', [$t])
            ->orWhereRaw('LOWER(isbn) LIKE ?', [$t])
            ->limit(4)
            ->get();
    }

    /**
     * Sugerencias de autores (máx. 3) — por nombre o apellido.
     */
    #[Computed]
    public function sugerenciasAutores()
    {
        if (mb_strlen(trim($this->termino)) < 2)
            return collect();
        $t = '%' . mb_strtolower(trim($this->termino)) . '%';
        return Author::where(function ($q) use ($t) {
            $q->whereRaw('LOWER(name) LIKE ?', [$t])
                ->orWhereRaw('LOWER(surname) LIKE ?', [$t]);
        })
            ->limit(3)
            ->get();
    }

    /**
     * Sugerencias de usuarios públicos (máx. 3).
     */
    #[Computed]
    public function sugerenciasUsuarios()
    {
        if (mb_strlen(trim($this->termino)) < 2)
            return collect();
        $t = '%' . mb_strtolower(trim($this->termino)) . '%';
        return User::whereRaw('LOWER(name) LIKE ?', [$t])
            ->where('profile_visibility', 'public')
            ->limit(3)
            ->get();
    }

    /**
     * Sugerencias de géneros (máx. 3).
     */
    #[Computed]
    public function sugerenciasGeneros()
    {
        if (mb_strlen(trim($this->termino)) < 2)
            return collect();
        $t = '%' . mb_strtolower(trim($this->termino)) . '%';
        return Genre::whereRaw('LOWER(name) LIKE ?', [$t])
            ->limit(3)
            ->get();
    }

    /**
     * ¿Hay alguna sugerencia que mostrar?
     */
    #[Computed]
    public function haySugerencias(): bool
    {
        return $this->sugerenciasLibros->isNotEmpty()
            || $this->sugerenciasAutores->isNotEmpty()
            || $this->sugerenciasUsuarios->isNotEmpty()
            || $this->sugerenciasGeneros->isNotEmpty();
    }

    // ─── Acciones ─────────────────────────────────────────────────────────

    /**
     * Navegar a la página de resultados completos.
     */
    public function buscar(): void
    {
        if (!empty(trim($this->termino))) {
            $this->mostrarSugerencias = false;
            $this->redirectRoute('search', ['q' => trim($this->termino)], navigate: true);
        }
    }

    /**
     * Cerrar el desplegable (al hacer clic fuera).
     */
    public function cerrarSugerencias(): void
    {
        $this->mostrarSugerencias = false;
    }
}; ?>

{{-- Barra de búsqueda con desplegable de sugerencias en tiempo real --}}
<div class="relative" x-data>

    {{-- Campo de búsqueda --}}
    <div class="{{ $grande ? 'max-w-3xl mx-auto flex gap-3' : 'flex gap-2' }}">
        <div class="flex-1 relative">
            <input type="text" wire:model.live.debounce.300ms="termino" wire:keydown.enter="buscar"
                id="barra-busqueda-{{ $grande ? 'home' : 'nav' }}"
                class="{{ $grande ? 'neo-input text-lg py-4 pl-12 w-full' : 'neo-input py-2 pl-10 w-full text-sm' }}"
                placeholder="Buscar libros, autores, usuarios, géneros, ISBN..." autocomplete="off">
            {{-- Icono lupa --}}
            <svg class="{{ $grande ? 'w-6 h-6' : 'w-4 h-4' }} absolute left-4 top-1/2 -translate-y-1/2 text-gray-500"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            {{-- Spinner mientras busca --}}
            <div wire:loading wire:target="termino" class="absolute right-4 top-1/2 -translate-y-1/2">
                <div class="w-4 h-4 border-2 border-black border-t-transparent rounded-full animate-spin"></div>
            </div>
        </div>
        <button wire:click="buscar"
            class="{{ $grande ? 'neo-btn-primary px-8 text-lg' : 'neo-btn-primary px-4 text-sm' }}">
            Buscar
        </button>
    </div>

    {{-- Desplegable de sugerencias --}}
    @if($mostrarSugerencias && $this->haySugerencias)
        <div class="{{ $grande ? 'max-w-3xl mx-auto' : '' }} absolute top-full left-0 right-0 mt-1 bg-white border-2 border-black shadow-[4px_4px_0px_#000] z-50 max-h-[400px] overflow-y-auto"
            wire:click.outside="cerrarSugerencias">
            {{-- Sugerencias: Libros --}}
            @if($this->sugerenciasLibros->isNotEmpty())
                <div class="border-b-2 border-black">
                    <div class="px-4 py-2 bg-brand-blue text-white text-xs font-black uppercase">Libros</div>
                    @foreach($this->sugerenciasLibros as $libro)
                        <a href="{{ route('books.show', $libro->isbn) }}" wire:navigate wire:key="sug-libro-{{ $libro->isbn }}"
                            class="flex items-center justify-between gap-3 px-4 py-3 hover:bg-brand-yellow/30 transition-colors border-b border-gray-100 last:border-0">
                            <span class="font-bold text-sm truncate">{{ $libro->title }}</span>
                            @if($libro->authors->first())
                                <span class="text-xs text-gray-500 flex-shrink-0">
                                    {{ $libro->authors->first()->name }}
                                </span>
                            @endif
                        </a>
                    @endforeach
                </div>
            @endif

            {{-- Sugerencias: Autores --}}
            @if($this->sugerenciasAutores->isNotEmpty())
                <div class="border-b-2 border-black">
                    <div class="px-4 py-2 bg-brand-yellow text-black text-xs font-black uppercase">Autores</div>
                    @foreach($this->sugerenciasAutores as $autor)
                        <a href="{{ route('authors.show', $autor->id) }}" wire:navigate wire:key="sug-autor-{{ $autor->id }}"
                            class="flex items-center gap-3 px-4 py-3 hover:bg-brand-yellow/30 transition-colors border-b border-gray-100 last:border-0">
                            <span class="font-bold text-sm">{{ $autor->name }} {{ $autor->surname }}</span>
                        </a>
                    @endforeach
                </div>
            @endif

            {{-- Sugerencias: Usuarios --}}
            @if($this->sugerenciasUsuarios->isNotEmpty())
                <div class="border-b-2 border-black">
                    <div class="px-4 py-2 bg-black text-white text-xs font-black uppercase">Usuarios</div>
                    @foreach($this->sugerenciasUsuarios as $usuario)
                        <a href="{{ route('users.show', $usuario->id) }}" wire:navigate wire:key="sug-user-{{ $usuario->id }}"
                            class="flex items-center gap-3 px-4 py-3 hover:bg-brand-yellow/30 transition-colors border-b border-gray-100 last:border-0">
                            <span class="font-bold text-sm">{{ $usuario->name }}</span>
                        </a>
                    @endforeach
                </div>
            @endif

            {{-- Sugerencias: Géneros --}}
            @if($this->sugerenciasGeneros->isNotEmpty())
                <div>
                    <div class="px-4 py-2 bg-purple-500 text-white text-xs font-black uppercase">Géneros</div>
                    @foreach($this->sugerenciasGeneros as $genero)
                        <a href="{{ route('books.index', ['genre' => $genero->id]) }}" wire:navigate
                            wire:key="sug-gen-{{ $genero->id }}"
                            class="flex items-center gap-3 px-4 py-3 hover:bg-brand-yellow/30 transition-colors border-b border-gray-100 last:border-0">
                            <span class="font-bold text-sm">{{ $genero->name }}</span>
                        </a>
                    @endforeach
                </div>
            @endif

            {{-- Enlace a resultados completos --}}
            <div class="border-t-2 border-black px-4 py-2">
                <button wire:click="buscar"
                    class="w-full text-left text-xs font-bold text-brand-blue hover:underline uppercase">
                    Ver todos los resultados para "{{ $termino }}" →
                </button>
            </div>
        </div>
    @endif
</div>