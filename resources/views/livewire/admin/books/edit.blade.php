<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;   // Trait oficial de Livewire para subida de archivos
use App\Models\Book;
use App\Models\Author;
use App\Models\Genre;

new #[Layout('layouts.admin')] #[Title('Formulario de Libro')] class extends Component {
    use WithFileUploads; // Habilita la carga de archivos reactiva en Livewire 4.1

    public ?Book $book = null;

    // Propiedades del formulario — nombres exactos de las columnas de la BD
    public $title = '';
    public $isbn = '';
    public $description = '';
    public $genre_id = '';
    public $publisher = '';
    public $publication_year = '';   // columna real: publication_year
    public $number_of_pages = '';    // columna real: number_of_pages
    public $language_id = '';
    public $cover_path = '';          // columna real: cover_path

    // Propiedad temporal de carga de imagen (Livewire WithFileUploads)
    // No se guarda en BD — solo se usa durante el proceso de subida
    public $cover_upload = null;

    // Campo de autor con búsqueda libre
    public string $author_search = '';   // texto que escribe el admin
    public ?int $author_id = null; // ID del autor seleccionado (si existe)
    public array $author_suggestions = []; // lista de sugerencias dinámicas

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
                $this->publisher = $this->book->publisher;
                $this->publication_year = $this->book->publication_year;
                $this->number_of_pages = $this->book->number_of_pages;
                $this->cover_path = $this->book->cover_path;

                // Pre-cargar el primer autor si existe
                $author = $this->book->authors()->first();
                if ($author) {
                    $this->author_id = $author->id;
                    // Mostramos nombre completo en el campo de búsqueda
                    $this->author_search = trim($author->name . ' ' . $author->surname);
                }
            }
        }
    }

    // Busca autores en la BD mientras el admin escribe (búsqueda reactiva)
    public function updatedAuthorSearch(string $value): void
    {
        $this->author_id = null; // Se deselecciona el autor previo al escribir

        if (strlen($value) < 2) {
            $this->author_suggestions = [];
            return;
        }

        // Buscamos por nombre o apellido, devolvemos máx 8 sugerencias
        $this->author_suggestions = Author::where('name', 'ilike', "%{$value}%")
            ->orWhere('surname', 'ilike', "%{$value}%")
            ->limit(8)
            ->get(['id', 'name', 'surname'])
            ->map(fn($a) => [
                'id' => $a->id,
                'label' => trim("{$a->name} {$a->surname}"),
            ])
            ->toArray();
    }

    // El admin elige una sugerencia del desplegable de autocompletar
    public function selectAuthor(int $id, string $label): void
    {
        $this->author_id = $id;
        $this->author_search = $label;
        $this->author_suggestions = [];
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'isbn' => 'required|string|max:20',
        ]);

        if (!$this->book) {
            $this->book = new Book();
            $this->book->isbn = $this->isbn;
        }

        $this->book->title = $this->title;
        $this->book->description = $this->description;
        $this->book->genre_id = $this->genre_id ?: null;
        $this->book->language_id = $this->language_id ?: null;
        $this->book->publisher = $this->publisher;
        $this->book->publication_year = $this->publication_year ?: null;
        $this->book->number_of_pages = $this->number_of_pages ?: null;
        $this->book->cover_path = $this->cover_path;

        $this->book->save();

        // Gestión del autor: si hay texto pero no ID seleccionado, creamos autor nuevo
        if (trim($this->author_search) !== '') {
            if (!$this->author_id) {
                // Crear autor nuevo con el nombre introducido (sin apellido separado)
                $parts = explode(' ', trim($this->author_search), 2);
                $newAuthor = Author::create([
                    'name' => $parts[0],
                    'surname' => $parts[1] ?? null,
                ]);
                $this->author_id = $newAuthor->id;
            }

            // Sincronizamos la relación — rol en minúsculas según el ENUM de la BD
            $this->book->authors()->sync([
                $this->author_id => ['role' => 'author', 'author_order' => 1]
            ]);
        }

        session()->flash('message', 'Libro guardado exitosamente.');
        return $this->redirectRoute('admin.books.index');
    }

    // Guarda la portada del libro en storage/app/public/covers/{titulo_limpio}/
    // NOTA: "upload" es nombre RESERVADO en Livewire 4.1, de ahí "saveCover".
    public function saveCover(): void
    {
        $this->validate([
            'cover_upload' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
            'title'        => 'required|min:1',
        ], [
            'cover_upload.required' => 'Debes seleccionar una imagen.',
            'cover_upload.image'    => 'El archivo debe ser una imagen.',
            'cover_upload.mimes'    => 'Se aceptan: JPG, PNG y WebP.',
            'cover_upload.max'      => 'La imagen no debe superar los 4 MB.',
            'title.required'        => 'Escribe primero el Título del libro.',
        ]);

        $disco = \Illuminate\Support\Facades\Storage::disk('public');

        // 1. Limpiar el título para usarlo como nombre de carpeta
        //    - Eliminar caracteres no permitidos en nombres de carpeta
        //    - Convertir a minúsculas
        //    - Reemplazar espacios por guiones bajos
        $nombreLimpio = mb_strtolower($this->title);                 // minúsculas
        $nombreLimpio = preg_replace('/[^\w\s\-]/u', '', $nombreLimpio); // quitar caracteres no válidos
        $nombreLimpio = preg_replace('/[\s\-]+/', '_', $nombreLimpio);   // espacios/guiones → guiones bajos
        $nombreLimpio = trim($nombreLimpio, '_');                     // limpiar extremos

        // 2. Si no existe la carpeta base "covers", crearla
        if (!$disco->exists('covers')) {
            $disco->makeDirectory('covers');
        }

        // 3. Ruta completa de la carpeta del libro: covers/{titulo_limpio}
        $carpetaLibro = 'covers/' . $nombreLimpio;  // ej: covers/el_principito

        // 4. Si hay una portada previa en BD, eliminar el archivo anterior del disco
        if ($this->book && $this->book->cover_path) {
            $disco->delete($this->book->cover_path);

            // Si la carpeta antigua queda vacía, eliminarla también
            $carpetaAnterior = dirname($this->book->cover_path);
            if ($disco->exists($carpetaAnterior) && count($disco->files($carpetaAnterior)) === 0) {
                $disco->deleteDirectory($carpetaAnterior);
            }
        }

        // 5. Verificar si la carpeta del libro ya existe
        if (!$disco->exists($carpetaLibro)) {
            // Si no existe, crearla con el nombre limpio del libro
            $disco->makeDirectory($carpetaLibro);
        } else {
            // Si ya existe, eliminar cualquier imagen anterior dentro de ella
            $archivosExistentes = $disco->files($carpetaLibro);
            foreach ($archivosExistentes as $archivoExistente) {
                $disco->delete($archivoExistente);
            }
        }

        // 6. Obtener la extensión del archivo subido
        $extension = $this->cover_upload->getClientOriginalExtension();

        // 7. Mover el archivo a la carpeta del libro con el nombre limpio
        //    Nombre final: {titulo_limpio}.{extension}  →  ej: el_principito.jpg
        $nombreArchivo = $nombreLimpio . '.' . $extension;
        $rutaFinal = $carpetaLibro . '/' . $nombreArchivo;

        // Guardar con nombre personalizado (storeAs en vez de store)
        $this->cover_upload->storeAs($carpetaLibro, $nombreArchivo, 'public');

        // 8. Actualizar la propiedad del formulario con la ruta relativa
        //    En BD se guarda: covers/el_principito/el_principito.jpg
        $this->cover_path = $rutaFinal;
        $this->cover_upload = null;

        // 9. Si el libro ya existe en BD, guardar la ruta inmediatamente
        if ($this->book && $this->book->exists) {
            $this->book->cover_path = $rutaFinal;
            $this->book->save();
        }

        // 10. Emitir evento que cierra el modal (escuchado por Alpine)
        $this->dispatch('cover-saved');
    }

    #[On('deleteBook')]
    public function deleteBook($id = null)
    {
        if ($this->book) {
            // Eliminar la portada y su carpeta del disco si existe
            if ($this->book->cover_path) {
                $disco = \Illuminate\Support\Facades\Storage::disk('public');
                $disco->delete($this->book->cover_path);

                // Si la carpeta queda vacía, eliminarla también
                $carpeta = dirname($this->book->cover_path);
                if ($disco->exists($carpeta) && count($disco->files($carpeta)) === 0) {
                    $disco->deleteDirectory($carpeta);
                }
            }

            $this->book->delete();
            session()->flash('message', 'Libro eliminado exitosamente.');
            return $this->redirectRoute('admin.books.index');
        }
    }

    public function with()
    {
        return [
            // Ya no se carga toda la lista de autores; se usa búsqueda reactiva
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
        <div
            class="mb-4 p-4 bg-green-100 border border-green-400 text-green-800 font-bold text-sm shadow-[2px_2px_0px_#000]">
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
                        <input type="text" wire:model="title" class="neo-input w-full" placeholder="Project Hail Mary"
                            required>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        {{-- Campo de autor con búsqueda libre y autocompletar --}}
                        <div class="relative">
                            <label class="block text-xs font-bold uppercase mb-2">
                                Autor
                                @if($author_id)
                                    <span class="ml-2 text-green-700 font-normal normal-case">
                                        ✓ Autor existente seleccionado
                                    </span>
                                @elseif(trim($author_search) !== '')
                                    <span class="ml-2 text-brand-blue font-normal normal-case">
                                        → Se creará como nuevo autor al guardar
                                    </span>
                                @endif
                            </label>
                            {{-- Input de texto libre con búsqueda reactiva en Livewire --}}
                            <input type="text" wire:model.live.debounce.300ms="author_search" class="neo-input w-full"
                                placeholder="Escribe el nombre del autor..." autocomplete="off">
                            {{-- Lista de sugerencias dinámicas --}}
                            @if(!empty($author_suggestions))
                                <ul
                                    class="absolute z-50 w-full bg-white border-2 border-black shadow-[4px_4px_0px_#000] mt-1 max-h-48 overflow-y-auto">
                                    @foreach($author_suggestions as $suggestion)
                                        <li>
                                            <button type="button"
                                                wire:click="selectAuthor({{ $suggestion['id'] }}, '{{ addslashes($suggestion['label']) }}')"
                                                class="w-full text-left px-4 py-2 text-sm font-bold hover:bg-black hover:text-white transition-colors border-b border-gray-100 last:border-0">
                                                {{ $suggestion['label'] }}
                                            </button>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
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

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold uppercase mb-2">ISBN</label>
                            <input type="text" wire:model="isbn" class="neo-input w-full" placeholder="978-0593135204"
                                {{ $book ? 'readonly' : 'required' }}>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase mb-2">Editorial (Publisher)</label>
                            <input type="text" wire:model="publisher" class="neo-input w-full"
                                placeholder="Penguin Random House">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold uppercase mb-2">Año de Publicación</label>
                            <input type="number" wire:model="publication_year" class="neo-input w-full"
                                placeholder="2021">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase mb-2">Número de Páginas</label>
                            <input type="number" wire:model="number_of_pages" class="neo-input w-full"
                                placeholder="496">
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
                        <textarea wire:model="description" rows="6" class="neo-input w-full"
                            placeholder="Sinopsis del libro..."></textarea>
                    </div>
                </form>
            </x-card>

            <x-card>
                <h3 class="font-black text-lg uppercase mb-6 border-b-2 border-black pb-2">Enlaces de Afiliado</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase mb-2">URL de Amazon</label>
                        <input type="url" wire:model="amazon_url" class="neo-input w-full"
                            placeholder="https://amazon.com/... (Próximamente)">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase mb-2">URL de Bookshop.org</label>
                        <input type="url" wire:model="bookshop_url" class="neo-input w-full"
                            placeholder="https://bookshop.org/... (Próximamente)">
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Barra Lateral -->
        <div class="space-y-8">
            {{-- Imagen de Portada --}}
            <x-card class="bg-gray-100">
                <h3 class="font-black text-sm uppercase mb-4">Imagen de Portada</h3>

                {{-- Al hacer clic en la portada se abre el modal de carga --}}
                <button type="button" @click="$dispatch('open-cover-upload')"
                    class="block w-full aspect-[2/3] bg-gray-300 border-2 border-black mb-4 overflow-hidden relative group cursor-pointer">

                    @if($cover_path)
                        <img src="{{ Storage::url($cover_path) }}" class="w-full h-full object-cover" alt="Portada actual">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center">
                            <svg class="w-10 h-10 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="text-gray-500 font-bold uppercase text-xs">Sin Portada</span>
                        </div>
                    @endif

                    {{-- Overlay de hover: invita a hacer clic --}}
                    <div
                        class="absolute inset-0 bg-black/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <span class="text-white font-black uppercase text-xs">Cambiar Portada</span>
                    </div>
                </button>

                <p class="text-xs text-gray-500 text-center">
                    Haz clic en la portada para cargar una imagen
                </p>
            </x-card>

            <!-- Acciones -->
            <div class="space-y-4">
                <button wire:click="save" class="w-full neo-btn-primary py-4 text-lg">Guardar Cambios</button>

                @if($book)
                    <button type="button"
                        @click="$dispatch('open-delete-modal', { action: 'deleteBook', title: 'Eliminar Libro', message: '¿Estás seguro de que deseas eliminar este libro permanentemente?' })"
                        class="w-full bg-white border-2 border-black py-4 font-black uppercase hover:bg-red-50 hover:text-red-600 hover:border-red-600 transition-colors">
                        Eliminar Libro
                    </button>
                @endif
            </div>
        </div>
    </div>

    {{-- Modal de Eliminación --}}
    @include('livewire.components.modals.delete-modal')

    {{-- Modal de Carga de Portada --}}
    @include('livewire.components.modals.cover-upload-modal')
</div>