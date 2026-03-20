<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\Author;

new
#[Layout('layouts.admin')]
#[Title('Gestionar Autores')]
class extends Component {

    use WithPagination;

    public $search = '';
    public $sortColumn = 'name';
    public $sortDirection = 'asc';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($column)
    {
        if ($this->sortColumn === $column) {
            if ($this->sortDirection === 'asc') {
                $this->sortDirection = 'desc';
            } elseif ($this->sortDirection === 'desc' && $column !== 'name') {
                $this->sortColumn = 'name';
                $this->sortDirection = 'asc';
            } elseif ($this->sortDirection === 'desc' && $column === 'name') {
                $this->sortDirection = 'asc';
            }
        } else {
            $this->sortColumn = $column;
            $this->sortDirection = 'asc';
        }
    }

    #[On('deleteAuthor')]
    public function deleteAuthor($id)
    {
        $author = Author::find($id);

        if ($author) {

            // 1. Eliminar todos sus libros físicos (portadas) y lógicos (registros)
            // Al borrar el registro de Book, la BD (por sus llaves foráneas 'cascadeOnDelete')
            // borrará automáticamente las reseñas, apariciones en listas, favoritos, etc.
            foreach ($author->books as $book) {
                if ($book->cover_path) {
                    $disco = \Illuminate\Support\Facades\Storage::disk('public');
                    $disco->delete($book->cover_path);

                    $carpetaLibro = dirname($book->cover_path);
                    if ($disco->exists($carpetaLibro) && count($disco->files($carpetaLibro)) === 0) {
                        $disco->deleteDirectory($carpetaLibro);
                    }
                }
                $book->delete();
            }

            // 2. Eliminar su foto y carpeta de ser necesario
            if ($author->photo_url) {
                $disco = \Illuminate\Support\Facades\Storage::disk('public');
                $disco->delete($author->photo_url);

                $carpetaAutor = dirname($author->photo_url);
                if ($disco->exists($carpetaAutor) && count($disco->files($carpetaAutor)) === 0) {
                    $disco->deleteDirectory($carpetaAutor);
                }
            }

            // 3. Finalmente eliminar el autor
            $author->delete();

            session()->flash(
                'message',
                'Autor eliminado correctamente.'
            );

            $this->resetPage();
        }
    }

    public function with()
    {
        $query = Author::with(['country'])->withCount('books')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereLikeAccentInsensitive('name', $this->search)
                      ->orWhereLikeAccentInsensitive('surname', $this->search)
                      ->orWhereHas('country', function ($countryQuery) {
                          $countryQuery->whereLikeAccentInsensitive('name', $this->search);
                      })
                      ->orWhereHas('books', function ($bookQuery) {
                          $bookQuery->whereLikeAccentInsensitive('title', $this->search)
                                    ->orWhereLikeAccentInsensitive('isbn', $this->search);
                      });
                      
                    if (is_numeric($this->search)) {
                        $q->orWhereYear('birth_date', $this->search);
                    }
                });
            });

        if ($this->sortColumn === 'country_name') {
            $query->leftJoin('countries', 'authors.country_id', '=', 'countries.id')
                  ->select('authors.*')
                  ->orderBy('countries.name', $this->sortDirection);
        } else {
            $query->orderBy($this->sortColumn, $this->sortDirection);
        }

        return [
            'authors' => $query->paginate(10)
        ];
    }
};
?>

<div>
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-4xl font-black uppercase font-display">Autores</h1>

        <a href="{{ route('admin.authors.create') }}" class="neo-btn-primary px-6 py-2 text-sm flex items-center gap-2">
            <span>+ Añadir Nuevo Autor</span>
        </a>
    </div>

    @if (session()->has('message'))
    <div
        class="mb-4 p-4 bg-green-100 border border-green-400 text-green-800 font-bold text-sm shadow-[2px_2px_0px_#000]">
        {{ session('message') }}
    </div>
    @endif

    <!-- Filtros Búsqueda -->
    <div class="bg-white border-2 border-black p-4 mb-8 flex gap-4">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por nombre, apellidos, nacionalidad, año..."
            class="neo-input flex-1 bg-white">
    </div>

    <!-- Tabla de Autores -->
    <div class="bg-white border-2 border-black overflow-hidden mb-8">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-black text-white text-xs font-bold uppercase tracking-wider">
                    <th class="p-4 border-b border-gray-800 text-center">Foto</th>
                    <th class="p-4 border-b border-gray-800 cursor-pointer hover:bg-gray-900 group select-none" wire:click="sortBy('name')">
                        <div class="flex items-center gap-1">Autor
                            @if($sortColumn === 'name')<span>{!! $sortDirection === 'asc' ? '↑' : '↓' !!}</span>@else<span class="opacity-0 group-hover:opacity-50">↕</span>@endif
                        </div>
                    </th>
                    <th class="p-4 border-b border-gray-800 cursor-pointer hover:bg-gray-900 group select-none" wire:click="sortBy('birth_date')">
                        <div class="flex items-center gap-1">Nacimiento
                            @if($sortColumn === 'birth_date')<span>{!! $sortDirection === 'asc' ? '↑' : '↓' !!}</span>@else<span class="opacity-0 group-hover:opacity-50">↕</span>@endif
                        </div>
                    </th>
                    <th class="p-4 border-b border-gray-800 cursor-pointer hover:bg-gray-900 group select-none" wire:click="sortBy('country_name')">
                        <div class="flex items-center gap-1">Nacionalidad
                            @if($sortColumn === 'country_name')<span>{!! $sortDirection === 'asc' ? '↑' : '↓' !!}</span>@else<span class="opacity-0 group-hover:opacity-50">↕</span>@endif
                        </div>
                    </th>
                    <th class="p-4 border-b border-gray-800 text-center cursor-pointer hover:bg-gray-900 group select-none" wire:click="sortBy('books_count')">
                        <div class="flex items-center justify-center gap-1">Libros
                            @if($sortColumn === 'books_count')<span>{!! $sortDirection === 'asc' ? '↑' : '↓' !!}</span>@else<span class="opacity-0 group-hover:opacity-50">↕</span>@endif
                        </div>
                    </th>
                    <th class="p-4 border-b border-gray-800 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-black/10">
                @forelse($authors as $author)
                <tr class="hover:bg-gray-50 transition-colors" wire:key="author-{{ $author->id }}">
                    <td class="p-4">
                        <div class="flex justify-center">
                            <div class="w-10 h-10 bg-gray-200 rounded-full border-2 border-black overflow-hidden">
                                @if($author->photo_url)
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($author->photo_url) }}"
                                    class="w-full h-full object-cover" alt="{{ $author->name }}">
                                @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($author->name . ' ' . $author->surname) }}&background=random"
                                    class="w-full h-full object-cover">
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="p-4">
                        <div class="font-bold text-sm">{{ $author->name }} {{ $author->surname }}</div>
                    </td>
                    <td class="p-4">
                        <div class="text-sm font-bold text-gray-500">
                            {{ $author->birth_date ? \Carbon\Carbon::parse($author->birth_date)->format('Y') : 'N/A' }}
                        </div>
                    </td>
                    <td class="p-4">
                        <div class="text-sm font-medium">
                            {{ optional($author->country)->name ?? 'No especificada' }}
                        </div>
                    </td>
                    <td class="p-4 text-center">
                        <span
                            class="inline-block bg-brand-yellow/20 border border-black/20 px-2 py-0.5 text-xs font-black uppercase">
                            {{ $author->books_count }}
                        </span>
                    </td>
                    <td class="p-4 text-right">
                        <div class="flex justify-end gap-3">
                            <a href="{{ route('admin.authors.edit', ['id' => $author->id]) }}"
                                class="text-xs font-black uppercase text-brand-blue hover:underline">
                                Editar
                            </a>
                            <button
                                @click="$dispatch('open-delete-modal', { action: 'deleteAuthor', params: {{ $author->id }}, title: 'Eliminar Autor', message: '¿Estás seguro que deseas eliminar permanentemente a este autor y todos sus libros asociados? Esta acción no se puede deshacer.' })"
                                class="text-xs font-black uppercase text-red-600 hover:underline border-l border-gray-300 pl-3">
                                Eliminar
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-8 text-center text-gray-500 font-bold uppercase">
                        No se encontraron autores
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $authors->links('livewire.components.modals.pagination') }}
    </div>

    <!-- Modal de Eliminación -->
    @include('livewire.components.modals.delete-modal')
</div>