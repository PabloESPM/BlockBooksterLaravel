<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\Book;
use App\Models\Author;
use App\Models\Genre;

new #[Layout('layouts.admin')] #[Title('Formulario de Libro')] class extends Component {
    public ?Book $book = null;
    
    // Propiedades del formulario
    public $title = '';
    public $isbn = '';
    public $description = '';
    public $genre_id = '';
    public $author_id = ''; // Seleccion simple por simplicidad visual del form actual
    public $published_year = '';
    public $pages = '';
    public $language_id = '';
    public $amazon_url = '';
    public $bookshop_url = '';
    public $cover_url = '';

    public function mount($id = null)
    {
        if ($id) {
            $this->book = Book::find($id);
            if ($this->book) {
                $this->title = $this->book->title;
                $this->isbn = $this->book->isbn;
                $this->description = $this->book->description;
                $this->genre_id = $this->book->genre_id;
                $this->language_id = $this->book->language_id;
                $this->published_year = $this->book->published_year;
                $this->pages = $this->book->pages;
                $this->cover_url = $this->book->cover_url;
                
                // Si tiene autor asignado, preseleccionar el primero
                $author = $this->book->authors()->first();
                if ($author) {
                    $this->author_id = $author->id;
                }
            }
        }
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'isbn' => 'required|string|max:20',
        ]);

        if (!$this->book) {
            $this->book = new Book();
            // Assign ISBN since it's the primary key and non-incrementing
            $this->book->isbn = $this->isbn;
        }

        $this->book->title = $this->title;
        // if editing, and ISBN changed, it would be complex (primary key). We assume ISBN doesn't change or we create new.
        if (!$this->book->exists) {
            $this->book->isbn = $this->isbn;
        }
        
        $this->book->description = $this->description;
        $this->book->genre_id = $this->genre_id ?: null;
        $this->book->language_id = $this->language_id ?: null;
        $this->book->published_year = $this->published_year;
        $this->book->pages = $this->pages;
        $this->book->cover_url = $this->cover_url;
        
        $this->book->save();

        if ($this->author_id) {
            $this->book->authors()->sync([$this->author_id => ['role' => 'Author', 'author_order' => 1]]);
        }

        session()->flash('message', 'Libro guardado exitosamente.');
        return $this->redirectRoute('admin.books.index');
    }

    #[On('deleteBook')]
    public function deleteBook($id = null)
    {
        if ($this->book) {
            $this->book->delete();
            session()->flash('message', 'Libro eliminado exitosamente.');
            return $this->redirectRoute('admin.books.index');
        }
    }

    public function with()
    {
        return [
            'authors' => Author::orderBy('name')->get(),
            'genres' => Genre::orderBy('name')->get(),
            'languages' => \App\Models\Language::orderBy('name')->get(),
        ];
    }
};
?>

<div>
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.books.index') }}"
           class="w-10 h-10 border-2 border-black flex items-center justify-center hover:bg-black hover:text-white transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
            </svg>
        </a>
        <h1 class="text-3xl font-black uppercase font-display">{{ $book ? 'Editar Libro' : 'Nuevo Libro' }}</h1>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-800 font-bold text-sm shadow-[2px_2px_0px_#000]">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Formulario Principal -->
        <div class="lg:col-span-2 space-y-8">
            <x-card>
                <h3 class="font-black text-lg uppercase mb-6 border-b-2 border-black pb-2">Detalles del Libro</h3>
                <form wire:submit="save" class="space-y-6">
                    <div>
                        <label class="block text-xs font-bold uppercase mb-2">Título</label>
                        <input type="text" wire:model="title" class="neo-input w-full" placeholder="Project Hail Mary" required>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold uppercase mb-2">Autor</label>
                            <select wire:model="author_id" class="neo-input w-full">
                                <option value="">Seleccionar Autor...</option>
                                @foreach($authors as $author)
                                    <option value="{{ $author->id }}">{{ $author->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase mb-2">Género</label>
                            <select wire:model="genre_id" class="neo-input w-full">
                                <option value="">Seleccionar Género...</option>
                                @foreach($genres as $genre)
                                    <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-6">
                        <div>
                            <label class="block text-xs font-bold uppercase mb-2">ISBN</label>
                            <input type="text" wire:model="isbn" class="neo-input w-full" placeholder="978-0593135204" {{ $book ? 'readonly' : 'required' }}>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase mb-2">Año de Publicación</label>
                            <input type="number" wire:model="published_year" class="neo-input w-full" placeholder="2021">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase mb-2">Páginas</label>
                            <input type="number" wire:model="pages" class="neo-input w-full" placeholder="496">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase mb-2">Idioma</label>
                        <select wire:model="language_id" class="neo-input w-full">
                            <option value="">Seleccionar Idioma...</option>
                            @foreach($languages as $language)
                                <option value="{{ $language->id }}">{{ $language->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase mb-2">Descripción</label>
                        <textarea wire:model="description" rows="6" class="neo-input w-full" placeholder="Sinopsis del libro..."></textarea>
                    </div>
                </form>
            </x-card>

            <x-card>
                <h3 class="font-black text-lg uppercase mb-6 border-b-2 border-black pb-2">Enlaces de Afiliado</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase mb-2">URL de Amazon</label>
                        <input type="url" wire:model="amazon_url" class="neo-input w-full" placeholder="https://amazon.com/... (Próximamente)">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase mb-2">URL de Bookshop.org</label>
                        <input type="url" wire:model="bookshop_url" class="neo-input w-full" placeholder="https://bookshop.org/... (Próximamente)">
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Barra Lateral -->
        <div class="space-y-8">
            <!-- Imagen de Portada -->
            <x-card class="bg-gray-100">
                <h3 class="font-black text-sm uppercase mb-4">Imagen de Portada (URL)</h3>
                <div class="aspect-[2/3] bg-gray-300 border-2 border-black mb-4 flex items-center justify-center overflow-hidden">
                    @if($cover_url)
                        <img src="{{ $cover_url }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-gray-500 font-bold uppercase text-xs">Sin Portada</span>
                    @endif
                </div>
                <!-- Para simplificar, usamos input text para URL en lugar de file upload -->
                <input type="text" wire:model.live.debounce.500ms="cover_url" placeholder="https://ejemplo.com/cover.jpg" class="neo-input w-full text-xs">
            </x-card>

            <!-- Acciones -->
            <div class="space-y-4">
                <button wire:click="save" class="w-full neo-btn-primary py-4 text-lg">Guardar Cambios</button>
                
                @if($book)
                <button type="button" @click="$dispatch('open-delete-modal', { action: 'deleteBook', title: 'Eliminar Libro', message: '¿Estás seguro de que deseas eliminar este libro permanentemente?' })"
                    class="w-full bg-white border-2 border-black py-4 font-black uppercase hover:bg-red-50 hover:text-red-600 hover:border-red-600 transition-colors">
                    Eliminar Libro
                </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal de Eliminación -->
    @include('livewire.components.modals.delete-modal')
</div>
