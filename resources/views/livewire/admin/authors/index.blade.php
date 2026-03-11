<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use App\Models\Author;

new
#[Layout('layouts.admin')]
#[Title('Gestionar Autores')]
class extends Component {

    use WithPagination;

    public function deleteAuthor($id)
    {
        $author = Author::find($id);

        if ($author) {
            $author->delete();

            session()->flash(
                'message',
                'Autor eliminado correctamente.'
            );

            $this->resetPage();
        }
    }

    public function authors()
    {
        return Author::withCount('books')
            ->orderBy('name')
            ->paginate(12);
    }
};
?>

<div>
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-4xl font-black uppercase font-display">Autores</h1>

        <button class="neo-btn-primary px-6 py-2 text-sm flex items-center gap-2">
            <span>+ Añadir Nuevo Autor (Próximamente)</span>
        </button>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-800 font-bold text-sm shadow-[2px_2px_0px_#000]">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

        @forelse($this->authors() as $author)

            <x-card class="group" wire:key="author-{{ $author->id }}">

                <div class="flex items-center gap-4 mb-4">

                    <div class="w-16 h-16 bg-gray-200 rounded-full border-2 border-black flex-shrink-0 overflow-hidden">
                        @if($author->photo_url)
                            <img src="{{ $author->photo_url }}" class="w-full h-full object-cover">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($author->name) }}&background=random"
                                 class="w-full h-full object-cover">
                        @endif
                    </div>

                    <div>
                        <h3 class="font-bold uppercase leading-tight group-hover:text-brand-blue transition-colors">
                            {{ $author->name }}
                        </h3>

                        <p class="text-xs text-gray-500 font-bold uppercase">
                            {{ $author->books_count }} Libros
                        </p>
                    </div>

                </div>

                <div class="flex gap-2">

                    <button class="flex-1 neo-btn-secondary py-1 text-xs text-gray-400 cursor-not-allowed">
                        Editar
                    </button>

                    <button
                        wire:click="deleteAuthor({{ $author->id }})"
                        wire:confirm="¿Estás seguro de que deseas eliminar este autor?"
                        class="bg-red-50 border-2 border-black px-3 py-1 text-xs font-bold uppercase text-red-600 hover:bg-red-600 hover:text-white transition-colors">

                        Eliminar

                    </button>

                </div>

            </x-card>

        @empty

            <div class="col-span-full p-8 text-center text-gray-500 font-bold uppercase bg-gray-50 border-2 border-dashed border-gray-300">
                No hay autores registrados.
            </div>

        @endforelse

    </div>

    <div class="mt-6">
        {{ $this->authors()->links() }}
    </div>

</div>
