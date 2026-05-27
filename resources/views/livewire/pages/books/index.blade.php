<?php
/**
 * Página Livewire SFC — Explorar Libros (index)
 *
 * Componente de página completa que gestiona el listado de libros con
 * filtrado reactivo y paginación. Toda la lógica de búsqueda y filtrado
 * (anteriormente en BookController@index) se concentra aquí.
 *
 * Los filtros se sincronizan con la URL vía #[Url] para permitir
 * enlaces directos y bookmarks con filtros aplicados.
 */

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Book;
use App\Models\Genre;
use App\Models\Country;
use Illuminate\Support\Facades\Storage;

new #[Layout('layouts.app')] #[Title('Explorar Libros')] class extends Component {
    use WithPagination;

    // ─── Campos de Búsqueda Avanzada ──────────────────────────────────
    #[Url(as: 'title')]
    public string $title = '';

    #[Url(as: 'author')]
    public string $author = '';

    #[Url(as: 'isbn')]
    public string $isbn = '';

    // ─── Filtros del Sidebar ──────────────────────────────────────────
    #[Url(as: 'sort')]
    public string $sort = '';

    #[Url(as: 'genre')]
    public string $genre = '';

    #[Url(as: 'country')]
    public string $country = '';

    #[Url(as: 'language')]
    public string $language = '';

    #[Url(as: 'pages_from')]
    public string $pages_from = '';

    #[Url(as: 'pages_to')]
    public string $pages_to = '';

    #[Url(as: 'year_from')]
    public string $year_from = '';

    #[Url(as: 'year_to')]
    public string $year_to = '';

    #[Url(as: 'rating')]
    public string $rating = '';

    /**
     * Reiniciar la paginación cuando cualquier filtro cambia.
     */
    public function updated($property): void
    {
        if ($property !== 'paginators.page' && $property !== 'page') {
            $this->resetPage();
        }
    }

    /**
     * Restablecer todos los filtros a sus valores por defecto.
     */
    public function resetFilters(): void
    {
        $this->reset([
            'title', 'author', 'isbn', 'sort', 'genre', 'country',
            'language', 'pages_from', 'pages_to', 'year_from', 'year_to', 'rating',
        ]);
        $this->resetPage();
    }

    /**
     * Proveer datos a la vista en cada renderizado.
     */
    public function with(): array
    {
        $query = Book::with('authors');

        // ─── Búsqueda Avanzada (insensible a mayúsculas/minúsculas) ───
        if (!empty($this->title)) {
            $query->whereRaw('LOWER(title) LIKE ?', ['%' . strtolower($this->title) . '%']);
        }
        if (!empty($this->author)) {
            $query->whereHas('authors', function ($q) {
                $q->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($this->author) . '%']);
            });
        }
        if (!empty($this->isbn)) {
            $query->whereRaw('LOWER(isbn) LIKE ?', ['%' . strtolower($this->isbn) . '%']);
        }

        // ─── Filtros del Sidebar ──────────────────────────────────────
        if (!empty($this->genre)) {
            $query->where('genre_id', $this->genre);
        }
        if (!empty($this->language)) {
            $query->whereHas('language', function ($q) {
                $q->where('code', $this->language);
            });
        }
        if (!empty($this->country)) {
            $query->whereHas('authors', function ($q) {
                $q->where('country_id', $this->country);
            });
        }
        if (!empty($this->pages_from)) {
            $query->where('number_of_pages', '>=', $this->pages_from);
        }
        if (!empty($this->pages_to)) {
            $query->where('number_of_pages', '<=', $this->pages_to);
        }
        if (!empty($this->year_from)) {
            $query->where('publication_year', '>=', $this->year_from);
        }
        if (!empty($this->year_to)) {
            $query->where('publication_year', '<=', $this->year_to);
        }

        // Valoración media calculada para cada libro
        $query->withAvg('users as average_rating', 'book_user.rating');

        // Filtrado por valoración mínima (subconsulta para evitar problemas con having + paginación)
        if (!empty($this->rating)) {
            $query->where(function ($subquery) {
                $subquery->selectRaw('avg(rating)')
                    ->from('book_user')
                    ->whereColumn('book_isbn', 'books.isbn');
            }, '>=', $this->rating);
        }

        // ─── Ordenación ───────────────────────────────────────────────
        switch ($this->sort) {
            case 'newest':
                $query->orderBy('publication_year', 'desc');
                break;
            case 'oldest':
                $query->orderBy('publication_year', 'asc');
                break;
            case 'title_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'title_desc':
                $query->orderBy('title', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        // Paginar resultados (12 por página)
        $books = $query->paginate(12);

        // Datos para los selectores del sidebar
        $genres = Genre::all();
        $countries = Country::whereHas('authors', function ($q) {
            $q->has('books');
        })->orderBy('name')->get();

        return [
            'books' => $books,
            'genres' => $genres,
            'countries' => $countries,
        ];
    }

    /**
     * Resolver la URL de la portada de un libro.
     * Centraliza la lógica que antes estaba embebida en el book-card original.
     */
    public function resolveBookCover(Book $book): string
    {
        return $book->cover_image ?? 'https://via.placeholder.com/300x450';
    }

    /**
     * Calcular la valoración media real de un libro (redondeada a 0.5).
     */
    public function resolveBookRating(Book $book): float
    {
        if (isset($book->average_rating) && $book->average_rating > 0) {
            return round($book->average_rating * 2) / 2;
        }
        return 0;
    }

    /**
     * Obtener el nombre completo del autor principal de un libro.
     */
    public function resolveBookAuthor(Book $book): string
    {
        $firstAuthor = $book->authors->first();
        if ($firstAuthor) {
            return trim($firstAuthor->name . ' ' . ($firstAuthor->surname ?? ''));
        }
        return 'Autor desconocido';
    }
}; ?>

<div>
    {{-- Barra Superior: Búsqueda Avanzada (parcial Blade) --}}
    @include('livewire.components.books.advanced-search')

    <div class="flex flex-col lg:flex-row gap-12">

        {{-- Filtros Laterales — Escritorio (parcial Blade) --}}
        @include('livewire.components.books.sidebar-filters')

        {{-- Cuadrícula Principal --}}
        <div class="flex-1">
            <div class="flex justify-between items-end mb-8">
                <h1 class="text-4xl font-display font-black uppercase flex items-center">
                    <span class="bg-brand-yellow px-2 border-2 border-black mr-4 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] text-2xl">
                        {{ $books->total() }}
                    </span>
                    Libros
                </h1>
                {{-- Botón de Filtros Móvil (Visible solo en pantallas pequeñas) --}}
                <button
                    x-data="{ open: false }"
                    @click="open = !open"
                    class="lg:hidden font-bold uppercase border-2 border-black px-4 py-2 hover:bg-gray-100">
                    Filtros
                </button>
            </div>

            {{-- Indicador de carga global --}}
            <div wire:loading.delay class="flex items-center justify-center gap-3 py-6">
                <div class="w-5 h-5 border-2 border-black border-t-transparent rounded-full animate-spin"></div>
                <span class="font-bold uppercase text-sm text-gray-500">Cargando resultados...</span>
            </div>

            <div wire:loading.remove class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($books as $book)
                    <div class="h-full">
                        <livewire:components.books.book-card
                            :isbn="$book->isbn"
                            :title="$book->title"
                            :author="$this->resolveBookAuthor($book)"
                            :cover="$this->resolveBookCover($book)"
                            :rating="$this->resolveBookRating($book)"
                            :key="$book->isbn"
                        />
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-xl font-bold uppercase text-gray-500">No se han encontrado libros.</p>
                    </div>
                @endforelse
            </div>

            {{-- Paginación con el componente Livewire existente --}}
            <div wire:loading.remove>
                {{ $books->links('livewire.components.modals.pagination') }}
            </div>
        </div>
    </div>
</div>
