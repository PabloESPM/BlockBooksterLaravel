<?php
/**
 * Página Livewire SFC — Detalle de Libro (show)
 *
 * Componente de página completa que muestra toda la información de un libro:
 * portada, metadatos, reseñas paginadas de forma reactiva y sidebar con
 * autores y libros relacionados del mismo género.
 *
 * Absorbe la lógica de BookController@show y BookController@loadReviews,
 * eliminando la necesidad del endpoint AJAX /books/{book}/load-reviews.
 *
 * Estado de lectura del usuario (tabla book_user):
 *   - pending  → «Quiero leer»
 *   - reading  → «Leyendo actualmente»
 *   - read     → «Leído»
 * El método actualizarEstadoLectura() persiste el cambio reactivamente.
 *
 * Eventos despachados hacia el layout:
 *   - open-add-to-list-modal  → livewire:components.books.add-to-list-modal
 *   - open-add-review-modal   → livewire:components.books.add-review-modal
 *   - open-share-modal        → x-modals.share-modal  (Alpine)
 *   - open-delete-modal       → x-modals.delete-modal (Alpine)
 *   - open-edit-review-modal  → x-modals.edit-review  (Alpine)
 */

use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Book;
use App\Models\Author;
use App\Models\BookUser;
use Illuminate\Support\Facades\Storage;

new #[Layout('layouts.app')] class extends Component {

    // ─── Modelo principal ─────────────────────────────────────────────────
    public Book $book;

    // ─── Paginación reactiva de reseñas ───────────────────────────────────
    // Número de reseñas visibles; crece 3 en cada llamada a cargarMasResenias()
    public int $reviewsPerPage = 3;

    /**
     * Inicializar el componente con el libro recibido por route model binding.
     * Se cargan las relaciones necesarias desde el principio.
     */
    // ─── Estado de lectura del usuario autenticado ────────────────────────
    // Valores posibles: '' (sin registrar) | 'pending' | 'reading' | 'read'
    public string $estadoLectura = '';

    /**
     * Inicializar el componente con el libro recibido por route model binding.
     * Se cargan las relaciones necesarias y se recupera el estado de lectura
     * del usuario autenticado (si existe) desde la tabla book_user.
     */
    public function mount(Book $book): void
    {
        $this->book = $book->load(['authors', 'genre', 'language']);

        // Recuperar el estado de lectura actual del usuario autenticado
        if (auth()->check()) {
            $pivote = BookUser::where('user_id', auth()->id())
                ->where('book_isbn', $this->book->isbn)
                ->first();
            $this->estadoLectura = $pivote?->status ?? '';
        }
    }

    /**
     * Persistir el estado de lectura del usuario en la tabla book_user.
     * Crea o actualiza el registro del pivote (upsert).
     *
     * @param string $estado  'pending' | 'reading' | 'read'
     */
    public function actualizarEstadoLectura(string $estado): void
    {
        if (auth()->guest()) {
            $this->redirectRoute('login', navigate: true);
            return;
        }

        // Validar que el estado sea uno de los valores permitidos
        if (!in_array($estado, ['pending', 'reading', 'read'])) {
            return;
        }

        // Crear o actualizar el registro en book_user
        BookUser::updateOrCreate(
            [
                'user_id'   => auth()->id(),
                'book_isbn' => $this->book->isbn,
            ],
            [
                'status'     => $estado,
                'started_at' => $estado === 'reading' ? now() : null,
                'finished_at'=> $estado === 'read'    ? now() : null,
            ]
        );

        $this->estadoLectura = $estado;

        // Notificar éxito mediante flash en sesión
        session()->flash('status', __('Estado de lectura actualizado correctamente.'));
    }

    /**
     * Título dinámico de la página para el <title> del HTML.
     */
    public function title(): string
    {
        return $this->book->title . ' — BlockBookster';
    }

    /**
     * Cargar 3 reseñas más de forma reactiva (sin petición AJAX externa).
     * Livewire re-renderiza solo el fragmento de reseñas.
     */
    public function cargarMasResenias(): void
    {
        $this->reviewsPerPage += 3;
    }

    /**
     * Reseñas del libro, paginadas reactivamente.
     * Ordenadas por número de likes descendente.
     */
    #[Computed]
    public function reviews()
    {
        return $this->book
            ->reviews()
            ->with('user')
            ->withCount('likes')
            ->orderByDesc('likes_count')
            ->paginate($this->reviewsPerPage);
    }

    /**
     * Valoración media del libro redondeada a 0,5 estrellas.
     */
    #[Computed]
    public function averageRating(): float
    {
        $total = $this->book->reviews()->count();
        if ($total === 0) {
            return 0.0;
        }
        $suma = $this->book->reviews()->withCount('likes')->get()->sum(fn ($r) => $r->rating);
        return round(($suma / $total) * 2) / 2;
    }

    /**
     * Número total de reseñas del libro.
     */
    #[Computed]
    public function totalResenias(): int
    {
        return $this->book->reviews()->count();
    }

    /**
     * URL resuelta de la portada del libro (storage, URL externa o placeholder).
     */
    #[Computed]
    public function coverUrl(): string
    {
        if ($this->book->cover_path) {
            return Storage::url($this->book->cover_path);
        }
        if (!empty($this->book->cover_image)) {
            return $this->book->cover_image;
        }
        if (!empty($this->book->cover) && str_starts_with($this->book->cover, 'http')) {
            return $this->book->cover;
        }
        return null; // null = mostrar placeholder visual en la vista
    }

    /**
     * Autores del mismo género (máximo 3), excluyendo los autores del propio libro.
     */
    #[Computed]
    public function autoresRelacionados()
    {
        if (!$this->book->genre_id) {
            return collect();
        }

        $autorIdsDelLibro = $this->book->authors->pluck('id');

        return Author::whereHas('books', fn ($q) => $q->where('genre_id', $this->book->genre_id))
            ->whereNotIn('id', $autorIdsDelLibro)
            ->inRandomOrder()
            ->take(3)
            ->get();
    }

    /**
     * Libros del mismo género que también les gustó a los lectores (máximo 4),
     * excluyendo el libro actual.
     */
    #[Computed]
    public function librosRelacionados()
    {
        if (!$this->book->genre_id) {
            return collect();
        }

        return Book::with('authors')
            ->where('genre_id', $this->book->genre_id)
            ->where('isbn', '!=', $this->book->isbn)
            ->inRandomOrder()
            ->take(4)
            ->get();
    }
}; ?>

<div>
    {{-- ─────────────────────────────────────────────────────────────────────── --}}
    {{-- SECCIÓN HERO: Portada + Información principal                           --}}
    {{-- ─────────────────────────────────────────────────────────────────────── --}}
    <section x-data class="grid grid-cols-1 md:grid-cols-12 gap-8 mb-16">

        {{-- Portada (columna izquierda) --}}
        <div class="md:col-span-4 lg:col-span-3">
            <div class="neo-card p-0 relative group">
                <div class="aspect-[2/3] bg-gray-200 border-b-2 border-black relative overflow-hidden">
                    @if($this->coverUrl)
                        <img src="{{ $this->coverUrl }}"
                             alt="{{ $book->title }}"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    @else
                        {{-- Placeholder visual neo-brutalista cuando no hay portada --}}
                        <div class="absolute inset-0 flex items-center justify-center bg-brand-yellow">
                            <span class="text-4xl font-black uppercase text-black opacity-20 -rotate-45">Portada</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Acciones en móvil (solo visible en pantallas pequeñas) --}}
            <div class="mt-4 md:hidden space-y-2">
                @auth
                    <button
                        @click="Livewire.dispatch('open-add-to-list-modal', { bookId: '{{ $book->isbn }}' })"
                        class="w-full neo-btn-primary mb-2">
                        + Quiero leer
                    </button>
                @endauth
                <button
                    @click="$dispatch('open-share-modal', { title: '{{ addslashes($book->title) }}', url: '{{ route('books.show', $book->isbn) }}' })"
                    class="block w-full text-center neo-btn-secondary text-sm">
                    Compartir
                </button>
            </div>
        </div>

        {{-- Información del libro (columna derecha) --}}
        <div class="md:col-span-8 lg:col-span-9 flex flex-col justify-between">
            <div>
                {{-- Título y autores --}}
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h1 class="text-3xl md:text-5xl font-black font-display uppercase tracking-tighter leading-none mb-2">
                            {{ $book->title }}
                        </h1>
                        <h2 class="text-xl font-bold uppercase text-gray-600">
                            por
                            @foreach($book->authors as $autor)
                                <a href="{{ route('authors.show', $autor->id) }}"
                                   wire:navigate
                                   class="text-brand-blue hover:underline">
                                    {{ $autor->name }} {{ $autor->surname }}
                                </a>{{ !$loop->last ? ',' : '' }}
                            @endforeach
                        </h2>
                    </div>

                    {{-- Bloque de valoración (escritorio) --}}
                    <div class="hidden md:block text-right flex-shrink-0 ml-4">
                        @php
                            $avgRating  = $this->averageRating;
                            $totalRes   = $this->totalResenias;
                        @endphp

                        {{-- Definición del gradiente para media estrella --}}
                        <svg class="w-0 h-0 absolute">
                            <defs>
                                <linearGradient id="half-star-gradient">
                                    <stop offset="50%" stop-color="currentColor" class="text-brand-yellow" />
                                    <stop offset="50%" stop-color="currentColor" class="text-gray-300" />
                                </linearGradient>
                            </defs>
                        </svg>

                        <div class="flex items-center gap-1 justify-end">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $avgRating)
                                    {{-- Estrella completa --}}
                                    <svg class="w-8 h-8 text-brand-yellow fill-current drop-shadow-[2px_2px_0px_rgba(0,0,0,1)]" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                    </svg>
                                @elseif($i - 0.5 == $avgRating)
                                    {{-- Media estrella --}}
                                    <svg class="w-8 h-8 drop-shadow-[2px_2px_0px_rgba(0,0,0,1)]" viewBox="0 0 24 24">
                                        <path fill="url(#half-star-gradient)" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                    </svg>
                                @else
                                    {{-- Estrella vacía --}}
                                    <svg class="w-8 h-8 text-gray-300 fill-current drop-shadow-[2px_2px_0px_rgba(0,0,0,1)]" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                    </svg>
                                @endif
                            @endfor
                        </div>
                        <div class="text-2xl font-black mt-1">
                            {{ number_format($avgRating, 1) }}
                            <span class="text-sm font-bold text-gray-500 uppercase">/ 5.0</span>
                        </div>
                        <div class="text-xs font-bold uppercase text-gray-500">
                            Basado en {{ $totalRes }} {{ Str::plural('valoración', $totalRes) }}
                        </div>
                    </div>
                </div>

                {{-- Metadatos del libro --}}
                <div class="flex flex-wrap gap-4 mb-8 text-sm font-bold uppercase border-y-2 border-black py-3">
                    @if($book->genre)
                        <span class="bg-black text-white px-2 py-0.5">{{ $book->genre->name }}</span>
                    @endif
                    @if($book->publication_year)
                        <span class="bg-gray-200 border border-black px-2 py-0.5">{{ $book->publication_year }}</span>
                    @endif
                    @if($book->number_of_pages)
                        <span class="bg-gray-200 border border-black px-2 py-0.5">{{ $book->number_of_pages }} páginas</span>
                    @endif
                    @if($book->language)
                        <span class="bg-gray-200 border border-black px-2 py-0.5">{{ $book->language->name }}</span>
                    @endif
                    <span class="text-gray-500 py-0.5">ISBN: {{ $book->isbn }}</span>
                </div>

                {{-- Sinopsis --}}
                @if($book->description)
                    <div class="mb-8 font-medium leading-relaxed text-gray-800">
                        <p>{{ $book->description }}</p>
                    </div>
                @endif
            </div>

            {{-- Acciones principales (escritorio) --}}
            <div class="hidden md:flex flex-wrap items-center gap-4">
                @auth
                    {{--
                        Selector de estado de lectura:
                        - Botón principal: acción rápida «Quiero leer» (pending) o muestra el estado actual.
                        - Desplegable (Alpine): permite cambiar entre pending / reading / read.
                        El estado se persiste en book_user vía wire:click sin recargar la página.
                    --}}
                    <div
                        x-data="{ open: false }"
                        class="flex items-center gap-2 border-r-2 border-black pr-4 mr-2"
                    >
                        {{-- Botón principal: muestra el estado actual o «Quiero leer» si no hay ninguno --}}
                        <button
                            wire:click="actualizarEstadoLectura('pending')"
                            wire:loading.attr="disabled"
                            class="neo-btn-primary flex items-center gap-2"
                        >
                            <span wire:loading.remove wire:target="actualizarEstadoLectura">
                                @if($estadoLectura === 'reading')
                                    📖 Leyendo actualmente
                                @elseif($estadoLectura === 'read')
                                    📚 Leído
                                @else
                                    📕 Leer
                                @endif
                            </span>
                            <div wire:loading wire:target="actualizarEstadoLectura"
                                 class="w-4 h-4 border-2 border-black border-t-transparent rounded-full animate-spin">
                            </div>
                        </button>

                        {{-- Flecha desplegable para cambiar el estado --}}
                        <div class="relative">
                            <button
                                @click="open = !open"
                                class="neo-btn-secondary px-3"
                                aria-label="Cambiar estado de lectura"
                            >▼</button>

                            <div
                                x-show="open"
                                @click.outside="open = false"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-95"
                                style="display: none;"
                                class="absolute top-full left-0 mt-2 w-52 bg-white border-2 border-black shadow-[4px_4px_0px_#000] z-20 flex flex-col"
                            >
                                {{-- Opción: Quiero leer --}}
                                <button
                                    wire:click="actualizarEstadoLectura('pending')"
                                    @click="open = false"
                                    class="text-left px-4 py-2 font-bold uppercase hover:bg-brand-yellow border-b border-black flex items-center gap-2 {{ $estadoLectura === 'pending' ? 'bg-brand-yellow' : '' }}"
                                >
                                    @if($estadoLectura === 'pending') ✓ @endif
                                    Quiero leer
                                </button>

                                {{-- Opción: Leyendo actualmente --}}
                                <button
                                    wire:click="actualizarEstadoLectura('reading')"
                                    @click="open = false"
                                    class="text-left px-4 py-2 font-bold uppercase hover:bg-brand-yellow border-b border-black flex items-center gap-2 {{ $estadoLectura === 'reading' ? 'bg-brand-yellow' : '' }}"
                                >
                                    @if($estadoLectura === 'reading') ✓ @endif
                                    Leyendo actualmente
                                </button>

                                {{-- Opción: Leído --}}
                                <button
                                    wire:click="actualizarEstadoLectura('read')"
                                    @click="open = false"
                                    class="text-left px-4 py-2 font-bold uppercase hover:bg-brand-yellow flex items-center gap-2 {{ $estadoLectura === 'read' ? 'bg-brand-yellow' : '' }}"
                                >
                                    @if($estadoLectura === 'read') ✓ @endif
                                    Leído
                                </button>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- Aviso para invitados --}}
                    <div class="flex items-center gap-4 border-r-2 border-black pr-4 mr-2">
                        <a href="{{ route('login') }}" wire:navigate
                           class="text-sm font-bold uppercase underline hover:text-brand-blue">
                            Inicia sesión para registrar tu lectura
                        </a>
                    </div>
                @endauth

                {{-- Compartir --}}
                <button
                    @click="$dispatch('open-share-modal', { title: '{{ addslashes($book->title) }}', url: '{{ route('books.show', $book->isbn) }}' })"
                    class="neo-btn-secondary text-sm flex items-center gap-2">
                    Compartir
                </button>

                {{-- Enlace externo (placeholder) --}}
                <a href="#" class="neo-btn-secondary text-sm flex items-center gap-2">
                    Comprar en Amazon
                </a>
            </div>
        </div>
    </section>

    {{-- ─────────────────────────────────────────────────────────────────────── --}}
    {{-- SEGUNDA FILA: Reseñas + Sidebar relacionados                            --}}
    {{-- ─────────────────────────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

        {{-- ── Sección Reseñas (2/3 del ancho) ────────────────────────────── --}}
        <div class="lg:col-span-2">
            <div class="flex items-center justify-between mb-6 border-b-2 border-black pb-2">
                <h2 class="text-2xl font-black uppercase flex items-center gap-2">
                    <span class="w-4 h-4 bg-brand-yellow border-2 border-black block"></span>
                    Reseñas de la comunidad
                </h2>
                @auth
                    <button
                        @click="Livewire.dispatch('open-add-review-modal', { bookId: '{{ $book->isbn }}' })"
                        class="text-sm font-bold uppercase bg-brand-blue text-white px-3 py-1 hover:bg-gray-800 transition-colors">
                        Escribir reseña
                    </button>
                @endauth
            </div>

            {{-- Indicador de carga reactiva --}}
            <div wire:loading wire:target="cargarMasResenias" class="flex items-center gap-3 py-4 mb-4">
                <div class="w-4 h-4 border-2 border-black border-t-transparent rounded-full animate-spin"></div>
                <span class="font-bold uppercase text-sm text-gray-500">Cargando reseñas...</span>
            </div>

            {{-- Listado de reseñas --}}
            <div class="space-y-6" wire:loading.remove wire:target="cargarMasResenias">
                @forelse($this->reviews as $resenia)
                    <x-review-card :review="$resenia" />
                @empty
                    <div class="text-center py-12 border-2 border-dashed border-gray-300 bg-gray-50">
                        <p class="text-xl font-bold uppercase text-gray-400">Aún no hay reseñas.</p>
                        @auth
                            <button
                                @click="Livewire.dispatch('open-add-review-modal', { bookId: '{{ $book->isbn }}' })"
                                class="neo-btn-primary mt-4 text-sm px-4">
                                Sé el primero en reseñar
                            </button>
                        @endauth
                    </div>
                @endforelse
            </div>

            {{-- Botón "Cargar más reseñas" (reactivo, sin AJAX externo) --}}
            @if($this->reviews->hasMorePages())
                <div class="mt-8 text-center">
                    <button
                        wire:click="cargarMasResenias"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-60 cursor-wait"
                        class="neo-btn-secondary w-full flex items-center justify-center gap-2">
                        <div wire:loading wire:target="cargarMasResenias"
                             class="w-4 h-4 border-2 border-black border-t-transparent rounded-full animate-spin">
                        </div>
                        <span wire:loading.remove wire:target="cargarMasResenias">
                            Cargar más reseñas
                        </span>
                        <span wire:loading wire:target="cargarMasResenias">
                            Cargando...
                        </span>
                    </button>
                </div>
            @endif
        </div>

        {{-- ── Sidebar: Relacionados (1/3 del ancho) ───────────────────────── --}}
        <div class="lg:col-span-1">

            {{-- Autores relacionados del mismo género --}}
            <div class="mb-6 border-b-2 border-black pb-2">
                <h2 class="text-xl font-black uppercase">Autores relacionados</h2>
            </div>

            @if($this->autoresRelacionados->isEmpty())
                <p class="text-sm font-bold text-gray-400 uppercase mb-8">Sin autores relacionados.</p>
            @else
                <div class="space-y-4 mb-8">
                    @foreach($this->autoresRelacionados as $autorRel)
                        <a href="{{ route('authors.show', $autorRel->id) }}"
                           wire:navigate
                           class="neo-card p-4 flex items-center gap-4 hover:bg-gray-50 transition-colors group">
                            {{-- Foto del autor o placeholder --}}
                            @if($autorRel->photo_path)
                                <img src="{{ Storage::url($autorRel->photo_path) }}"
                                     alt="{{ $autorRel->name }}"
                                     class="w-12 h-12 rounded-full border-2 border-black object-cover flex-shrink-0">
                            @else
                                <div class="w-12 h-12 bg-gray-200 rounded-full border-2 border-black flex items-center justify-center flex-shrink-0">
                                    <span class="text-xl font-black text-gray-500">
                                        {{ strtoupper(substr($autorRel->name, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                            <div class="overflow-hidden">
                                <h4 class="font-bold uppercase text-sm truncate group-hover:text-brand-blue transition-colors">
                                    {{ $autorRel->name }} {{ $autorRel->surname }}
                                </h4>
                                @if($book->genre)
                                    <p class="text-xs text-gray-500 uppercase truncate">{{ $book->genre->name }}</p>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

            {{-- Libros relacionados del mismo género --}}
            <div class="mb-6 border-b-2 border-black pb-2">
                <h2 class="text-xl font-black uppercase">A los lectores también les gustó</h2>
            </div>

            @if($this->librosRelacionados->isEmpty())
                <p class="text-sm font-bold text-gray-400 uppercase">Sin libros relacionados.</p>
            @else
                <div class="grid grid-cols-2 gap-4">
                    @foreach($this->librosRelacionados as $libroRel)
                        @php
                            $portadaRel = null;
                            if ($libroRel->cover_path) {
                                $portadaRel = Storage::url($libroRel->cover_path);
                            } elseif (!empty($libroRel->cover_image)) {
                                $portadaRel = $libroRel->cover_image;
                            } elseif (!empty($libroRel->cover) && str_starts_with($libroRel->cover, 'http')) {
                                $portadaRel = $libroRel->cover;
                            }
                        @endphp
                        <a href="{{ route('books.show', $libroRel->isbn) }}"
                           wire:navigate
                           class="neo-card p-0 border-2 border-black relative overflow-hidden group block">
                            @if($portadaRel)
                                <img src="{{ $portadaRel }}"
                                     alt="{{ $libroRel->title }}"
                                     class="w-full h-32 object-cover grayscale group-hover:grayscale-0 transition-all duration-300">
                            @else
                                <div class="w-full h-32 bg-gray-200 flex items-center justify-center">
                                    <span class="text-xs font-bold uppercase opacity-30 rotate-45">Portada</span>
                                </div>
                            @endif
                            <div class="p-2 bg-white border-t-2 border-black">
                                <p class="text-xs font-bold uppercase truncate">{{ $libroRel->title }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
