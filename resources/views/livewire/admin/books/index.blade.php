<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Book;
use App\Models\Genre;

new #[Layout('layouts.admin')] #[Title('Gestión de Libros')] class extends Component {
    use WithPagination;

    public $search = '';
    public $genre_id = '';
    public $sortColumn = 'created_at';
    public $sortDirection = 'desc';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($column)
    {
        if ($this->sortColumn === $column) {
            if ($this->sortDirection === 'asc') {
                $this->sortDirection = 'desc';
            } elseif ($this->sortDirection === 'desc' && $column !== 'created_at') {
                $this->sortColumn = 'created_at';
                $this->sortDirection = 'desc';
            } elseif ($this->sortDirection === 'desc' && $column === 'created_at') {
                $this->sortDirection = 'asc';
            }
        } else {
            $this->sortColumn = $column;
            $this->sortDirection = 'asc';
            if ($column === 'created_at') {
                $this->sortDirection = 'desc';
            }
        }
    }

    public function updatingGenreId()
    {
        $this->resetPage();
    }

    #[On('deleteBook')]
    public function deleteBook($id) // El objeto del evento usa la key 'id'
    {
        $book = Book::find($id);
        if ($book) {
            // Eliminar la portada y su carpeta del disco si existe
            if ($book->cover_path) {
                $disco = \Illuminate\Support\Facades\Storage::disk('public');
                $disco->delete($book->cover_path);

                // Si la carpeta queda vacía, eliminarla también
                $carpeta = dirname($book->cover_path);
                if ($disco->exists($carpeta) && count($disco->files($carpeta)) === 0) {
                    $disco->deleteDirectory($carpeta);
                }
            }

            $book->delete();
            session()->flash("message", "Libro eliminado correctamente.");
        }
    }

    public function with()
    {
        $query = Book::with(['authors', 'genre'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereLikeAccentInsensitive('title', $this->search)
                      ->orWhereLikeAccentInsensitive('isbn', $this->search)
                      ->orWhereLikeAccentInsensitive('publisher', $this->search)
                      ->orWhereHas('authors', function ($authorQuery) {
                          $authorQuery->whereLikeAccentInsensitive('name', $this->search)
                                      ->orWhereLikeAccentInsensitive('surname', $this->search);
                      });
                      
                    if (is_numeric($this->search)) {
                        $q->orWhere('publication_year', $this->search);
                    }
                });
            })
            ->when($this->genre_id, function ($query) {
                $query->where('genre_id', $this->genre_id);
            });

        if ($this->sortColumn === 'author_name') {
            $query->orderBy(
                \App\Models\Author::select('name')
                    ->join('author_book', 'authors.id', '=', 'author_book.author_id')
                    ->whereColumn('author_book.book_isbn', 'books.isbn')
                    ->limit(1),
                $this->sortDirection
            );
        } elseif ($this->sortColumn === 'genre_name') {
            $query->leftJoin('genres', 'books.genre_id', '=', 'genres.id')
                  ->select('books.*')
                  ->orderBy('genres.name', $this->sortDirection);
        } else {
            $query->orderBy($this->sortColumn, $this->sortDirection);
        }

        return [
            'books' => $query->paginate(10),
            'genres' => Genre::orderBy('name')->get()
        ];
    }
};
?>

<div>
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-4xl font-black uppercase font-display">Libros</h1>
        <a href="{{ route('admin.books.create') }}" class="neo-btn-primary px-6 py-2 text-sm flex items-center gap-2">
            <span>+ Añadir Nuevo Libro</span>
        </a>
    </div>

    @if (session()->has('message'))
    <div
        class="mb-4 p-4 bg-green-100 border border-green-400 text-green-800 font-bold text-sm shadow-[2px_2px_0px_#000]">
        {{ session('message') }}
    </div>
    @endif

    <!-- Filtros -->
    <div class="bg-white border-2 border-black p-4 mb-8 flex gap-4">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por título, autor, editorial, año..."
            class="neo-input flex-1 bg-white">
        <select wire:model.live="genre_id" class="neo-input w-48 bg-white">
            <option value="">Todos los Géneros</option>
            @foreach($genres as $genre)
            <option value="{{ $genre->id }}">{{ $genre->name }}</option>
            @endforeach
        </select>
        <!-- Retiramos el botón Filtrar porque livewire model.live lo hace dinámicamente -->
    </div>

    <!-- Tabla -->
    <div class="bg-white border-2 border-black overflow-hidden mb-8">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-black text-white text-xs font-bold uppercase tracking-wider">
                    <th class="p-4 border-b border-gray-800">Portada</th>
                    <th class="p-4 border-b border-gray-800 cursor-pointer hover:bg-gray-900 group select-none" wire:click="sortBy('title')">
                        <div class="flex items-center gap-1">Título / ISBN
                            @if($sortColumn === 'title')<span>{!! $sortDirection === 'asc' ? '↑' : '↓' !!}</span>@else<span class="opacity-0 group-hover:opacity-50">↕</span>@endif
                        </div>
                    </th>
                    <th class="p-4 border-b border-gray-800 cursor-pointer hover:bg-gray-900 group select-none" wire:click="sortBy('author_name')">
                        <div class="flex items-center gap-1">Autor(es)
                            @if($sortColumn === 'author_name')<span>{!! $sortDirection === 'asc' ? '↑' : '↓' !!}</span>@else<span class="opacity-0 group-hover:opacity-50">↕</span>@endif
                        </div>
                    </th>
                    <th class="p-4 border-b border-gray-800 cursor-pointer hover:bg-gray-900 group select-none" wire:click="sortBy('genre_name')">
                        <div class="flex items-center gap-1">Género
                            @if($sortColumn === 'genre_name')<span>{!! $sortDirection === 'asc' ? '↑' : '↓' !!}</span>@else<span class="opacity-0 group-hover:opacity-50">↕</span>@endif
                        </div>
                    </th>
                    <th class="p-4 border-b border-gray-800 text-center cursor-pointer hover:bg-gray-900 group select-none" wire:click="sortBy('publication_year')">
                        <div class="flex items-center justify-center gap-1">Año
                            @if($sortColumn === 'publication_year')<span>{!! $sortDirection === 'asc' ? '↑' : '↓' !!}</span>@else<span class="opacity-0 group-hover:opacity-50">↕</span>@endif
                        </div>
                    </th>
                    <th class="p-4 border-b border-gray-800 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-black/10">
                @forelse($books as $book)
                <tr class="hover:bg-gray-50 transition-colors" wire:key="book-{{ $book->isbn }}">
                    <td class="p-4">
                        <div class="w-10 h-14 bg-gray-200 border border-black overflow-hidden flex-shrink-0">
                            @if($book->cover_path)
                            <img src="{{ Storage::url($book->cover_path) }}" class="w-full h-full object-cover"
                                alt="{{ $book->title }}">
                            @else
                            <div class="w-full h-full flex items-center justify-center text-xs text-gray-400">Sin</div>
                            @endif
                        </div>
                    </td>
                    <td class="p-4">
                        <div class="font-bold leading-tight">{{ $book->title }}</div>
                        <div class="text-xs text-gray-500 font-mono">{{ $book->isbn }}</div>
                    </td>
                    <td class="p-4 font-medium text-sm">
                        {{ $book->authors->map(fn($author) => trim($author->name . ' ' . $author->surname))->join(', ')
                        ?: 'Anónimo' }}
                    </td>
                    <td class="p-4">
                        @if($book->genre)
                        <span class="text-xs font-bold uppercase bg-brand-yellow/20 px-2 py-1 border border-black/20">
                            {{ $book->genre->name }}
                        </span>
                        @else
                        <span class="text-xs font-bold uppercase text-gray-400">N/A</span>
                        @endif
                    </td>
                    <td class="p-4 text-center font-bold text-sm text-gray-600">
                        {{ $book->publication_year ?? 'N/A' }}
                    </td>
                    <td class="p-4 text-right">
                        <div class="flex justify-end gap-3">
                            <a href="{{ route('admin.books.edit', ['id' => $book->isbn]) }}"
                                class="text-xs font-black uppercase text-brand-blue hover:underline">Editar</a>
                            <button
                                @click="$dispatch('open-delete-modal', { action: 'deleteBook', params: '{{ $book->isbn }}', title: 'Eliminar Libro', message: '¿Estás seguro que deseas eliminar permanentemente este libro y todas sus reseñas asociadas? Esta acción no se puede deshacer.' })"
                                class="text-xs font-black uppercase text-red-600 hover:underline border-l border-gray-300 pl-3">
                                Eliminar
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-8 text-center text-gray-500 font-bold uppercase">
                        No se encontraron libros
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div class="mt-4">
        {{ $books->links('livewire.components.modals.pagination') }}
    </div>

    <!-- Modal Neo-Brutalista de Eliminación -->
    @include('livewire.components.modals.delete-modal')
</div>