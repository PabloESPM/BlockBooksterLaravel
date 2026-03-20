<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Author;
use App\Models\Country;

new #[Layout('layouts.admin')] #[Title('Editar Autor')] class extends Component {
    use WithFileUploads;

    public ?Author $author = null;

    // Campos del formulario
    public $name = '';
    public $surname = '';
    public $birth_date = '';
    public $country_id = '';
    public $biography = '';
    public $photo_url = '';

    // Propiedad para la subida temporal desde el modal
    public $photo_upload = null;

    public function mount($id = null)
    {
        if ($id) {
            $this->author = Author::findOrFail($id);
            $this->name = $this->author->name;
            $this->surname = $this->author->surname;
            $this->birth_date = $this->author->birth_date;
            $this->country_id = $this->author->country_id;
            $this->biography = $this->author->biography;
            $this->photo_url = $this->author->photo_url;
        } else {
            $this->author = new Author();
        }
    }

    public function with()
    {
        return [
            'countries' => Country::orderBy('name')->get()
        ];
    }

    // Guarda los datos de texto del autor
    public function save()
    {
        $this->validate([
            'name'       => 'required|string|max:255',
            'surname'    => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'country_id' => 'nullable|exists:countries,id',
            'biography'  => 'nullable|string',
        ]);

        $this->author->name       = $this->name;
        $this->author->surname    = $this->surname;
        $this->author->birth_date = $this->birth_date ?: null;
        $this->author->country_id = $this->country_id ?: null;
        $this->author->biography  = $this->biography;
        $this->author->photo_url  = $this->photo_url; // Por si se actualizó desde el modal

        $this->author->save();

        session()->flash('message', 'Autor ' . ($this->author->wasRecentlyCreated ? 'creado' : 'actualizado') . ' exitosamente.');
        return $this->redirectRoute('admin.authors.index');
    }

    // Guarda la foto del autor en storage/app/public/authorimg/{slug-nombre}/
    public function savePhoto(): void
    {
        $this->validate([
            'photo_upload' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
            'name'         => 'required|min:1',
        ], [
            'photo_upload.required' => 'Debes seleccionar una imagen.',
            'photo_upload.image'    => 'El archivo debe ser una imagen.',
            'photo_upload.mimes'    => 'Se aceptan: JPG, PNG y WebP.',
            'photo_upload.max'      => 'La imagen no debe superar los 4 MB.',
            'name.required'         => 'Escribe primero el Nombre del autor.',
        ]);

        $disco = Storage::disk('public');

        // 1. Limpiar el nombre para usarlo como nombre de carpeta
        // Usamos nombre + apellido (opcional)
        $nombreCompleto = $this->name . ' ' . $this->surname;
        $nombreLimpio = mb_strtolower(trim($nombreCompleto));
        $nombreLimpio = preg_replace('/[^\w\s\-]/u', '', $nombreLimpio);
        $nombreLimpio = preg_replace('/[\s\-]+/', '_', $nombreLimpio);
        $nombreLimpio = trim($nombreLimpio, '_');

        // 2. Crear carpeta base si no existe
        if (!$disco->exists('authorimg')) {
            $disco->makeDirectory('authorimg');
        }

        // 3. Ruta completa de la carpeta del autor: authorimg/{nombre_limpio}
        $carpetaAutor = 'authorimg/' . $nombreLimpio;

        // 4. Eliminar el archivo anterior del disco
        if ($this->author && $this->author->photo_url) {
            $disco->delete($this->author->photo_url);

            // Borrar carpeta preexistente si queda vacía
            $carpetaAnterior = dirname($this->author->photo_url);
            if ($disco->exists($carpetaAnterior) && count($disco->files($carpetaAnterior)) === 0) {
                $disco->deleteDirectory($carpetaAnterior);
            }
        }

        // 5. Crear carpeta y asegurar limpieza
        if (!$disco->exists($carpetaAutor)) {
            $disco->makeDirectory($carpetaAutor);
        } else {
            foreach ($disco->files($carpetaAutor) as $archivoExistente) {
                $disco->delete($archivoExistente);
            }
        }

        // 6. Extensión y nombre final
        $extension = $this->photo_upload->getClientOriginalExtension();
        $nombreArchivo = $nombreLimpio . '.' . $extension;
        $rutaFinal = $carpetaAutor . '/' . $nombreArchivo;

        // 7. Guardar imagen
        $this->photo_upload->storeAs($carpetaAutor, $nombreArchivo, 'public');

        // 8. Actualizar propiedad
        $this->photo_url = $rutaFinal;
        $this->photo_upload = null;

        // 9. Guardar en BD si el autor ya existe
        if ($this->author && $this->author->exists) {
            $this->author->photo_url = $rutaFinal;
            $this->author->save();
        }

        // 10. Cerrar el modal
        $this->dispatch('photo-saved');
    }

    #[On('deleteAuthor')]
    public function deleteAuthor($id = null)
    {
        if ($this->author) {
            $disco = Storage::disk('public');

            // 1. Eliminar todos los libros de este autor y sus portadas del sistema de almacenamiento
            foreach ($this->author->books as $book) {
                if ($book->cover_path) {
                    $disco->delete($book->cover_path);

                    $carpetaLibro = dirname($book->cover_path);
                    if ($disco->exists($carpetaLibro) && count($disco->files($carpetaLibro)) === 0) {
                        $disco->deleteDirectory($carpetaLibro);
                    }
                }
                $book->delete(); // Desencadena cascada en DB: reseñas, book_user, author_book, listas, etc.
            }

            // 2. Eliminar foto del autor del disco, si existe
            if ($this->author->photo_url) {
                $disco->delete($this->author->photo_url);

                $carpetaAutor = dirname($this->author->photo_url);
                if ($disco->exists($carpetaAutor) && count($disco->files($carpetaAutor)) === 0) {
                    $disco->deleteDirectory($carpetaAutor);
                }
            }

            // 3. Eliminar autor
            $this->author->delete();
            
            session()->flash('message', 'Autor y todos sus libros asociados eliminados exitosamente.');
            return $this->redirectRoute('admin.authors.index');
        }
    }
};
?>

<div class="max-w-4xl mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.authors.index') }}"
            class="w-10 h-10 border-2 border-black flex items-center justify-center hover:bg-black hover:text-white transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
            </svg>
        </a>
        <h1 class="text-3xl font-black uppercase font-display">{{ $author && $author->exists ? 'Editar Autor' : 'Nuevo Autor' }}</h1>
    </div>

    <!-- Layout principal dividido en dos columnas: Datos (Izquierda) e Imagen (Derecha) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Formulario (Izquierda, 2/3) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white border-2 border-black p-6 shadow-[4px_4px_0px_#000]">
                <h2 class="text-xl font-black uppercase border-b-2 border-black pb-4 mb-6">Información General</h2>

                <form wire:submit="save" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nombre -->
                        <div>
                            <label class="block text-sm font-bold uppercase mb-2">Nombre <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="name" class="neo-input w-full" placeholder="Ej: Stephen">
                            @error('name') <span class="text-xs font-bold text-red-600 uppercase mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Apellido -->
                        <div>
                            <label class="block text-sm font-bold uppercase mb-2">Apellido(s)</label>
                            <input type="text" wire:model="surname" class="neo-input w-full" placeholder="Ej: King">
                            @error('surname') <span class="text-xs font-bold text-red-600 uppercase mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Fecha de Nacimiento -->
                        <div>
                            <label class="block text-sm font-bold uppercase mb-2">Fecha Nacimiento</label>
                            <input type="date" wire:model="birth_date" class="neo-input w-full">
                            @error('birth_date') <span class="text-xs font-bold text-red-600 uppercase mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- País -->
                        <div>
                            <label class="block text-sm font-bold uppercase mb-2">Nacionalidad</label>
                            <select wire:model="country_id" class="neo-input w-full bg-white">
                                <option value="">Seleccionar País...</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                            @error('country_id') <span class="text-xs font-bold text-red-600 uppercase mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Biografía -->
                    <div>
                        <label class="block text-sm font-bold uppercase mb-2">Biografía</label>
                        <textarea wire:model="biography" rows="6" class="neo-input w-full" placeholder="Corta biografía del autor..."></textarea>
                        @error('biography') <span class="text-xs font-bold text-red-600 uppercase mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Acciones -->
                    <div class="pt-4 border-t-2 border-black mt-6 flex flex-col-reverse md:flex-row gap-4 justify-end">
                        @if($author && $author->exists)
                            <button type="button"
                                @click="$dispatch('open-delete-modal', { action: 'deleteAuthor', params: null, title: 'Eliminar Autor', message: '¿Estás seguro que deseas eliminar permanentemente a este autor y todas sus asociaciones? Esta acción no se puede deshacer.' })"
                                class="w-full md:w-auto bg-white border-2 border-black px-8 py-3 font-black uppercase hover:bg-red-50 hover:text-red-600 hover:border-red-600 transition-colors">
                                Eliminar Autor
                            </button>
                        @endif

                        <button type="submit" class="neo-btn-primary px-8 py-3 text-lg w-full md:w-auto">
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Columna lateral (Derecha, 1/3): Foto del autor -->
        <div class="space-y-6">
            <div class="bg-brand-yellow/20 border-2 border-black p-6 relative group text-center shadow-[4px_4px_0px_#000]">
                <h3 class="text-lg font-black uppercase mb-4 border-b-2 border-black pb-2">Foto</h3>
                
                <div class="w-48 h-48 mx-auto bg-white border-4 border-black mb-4 flex items-center justify-center overflow-hidden rounded-full shadow-[4px_4px_0px_#000]">
                    @if($photo_url)
                        <img src="{{ Storage::url($photo_url) }}" class="w-full h-full object-cover" alt="Foto del autor">
                    @else
                        @if($name)
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($name . ' ' . $surname) }}&background=random&size=256" class="w-full h-full object-cover">
                        @else
                            <span class="text-xs font-bold text-gray-400 uppercase">Sin Imagen</span>
                        @endif
                    @endif
                </div>

                {{-- Overlay hover para subir foto --}}
                <div class="absolute inset-0 bg-black/80 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer border-2 border-black"
                     @click="$dispatch('open-photo-upload')">
                    <span class="text-white font-black uppercase tracking-wider text-sm border-2 border-white px-4 py-2 hover:bg-white hover:text-black transition-colors">
                        Cambiar Foto
                    </span>
                </div>
                
                @error('photo_upload')
                    <p class="text-xs font-bold text-red-600 mt-2 text-center">{{ $message }}</p>
                @enderror
            </div>
            
            <p class="text-xs text-black/60 font-bold px-4 text-center">
                Haz clic en la imagen superior para subir o cambiar la fotografía de este autor.
                Se recomienda formato cuadrado.
            </p>
        </div>
    </div>

    <!-- Modales -->
    @include('livewire.components.modals.author-photo-upload-modal')
    @include('livewire.components.modals.delete-modal')

</div>
