<?php

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\FavList;

new #[Layout('layouts.app')] class extends Component {
    public FavList $list;
    public $showEditModal = false;
    public $name;
    public $description;
    public $visibility;

    public function mount(FavList $list)
    {
        $this->list = $list;
        $this->list->load(['user', 'books.authors']);
        $this->name = $list->name;
        $this->description = $list->description;
        $this->visibility = $list->visibility;
    }

    public function updateList()
    {
        if ($this->list->user_id !== auth()->id()) {
            abort(403);
        }

        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'visibility' => 'required|in:public,friends,private',
        ]);

        $this->list->update([
            'name' => $this->name,
            'description' => $this->description,
            'visibility' => $this->visibility,
        ]);

        $this->showEditModal = false;
        session()->flash('success', '¡Lista actualizada correctamente!');
    }

    public function rendering($view)
    {
        $view->title($this->list->name . ' - Lista de Lectura');
    }
}; ?>

<div>
    <!-- Mensaje de éxito -->
    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-green-100 border-2 border-black text-green-800 font-bold shadow-[4px_4px_0px_#000]">
            {{ session('success') }}
        </div>
    @endif

    <!-- Cabecera de la Lista -->
    <div class="mb-12">
        <div class="flex flex-col md:flex-row gap-8 items-start">
            <div class="flex-grow">
                <div class="flex items-center gap-2 mb-2">
                    <span class="bg-brand-blue text-white text-xs font-bold uppercase px-2 py-1 border border-black shadow-[2px_2px_0px_#000]">Lista</span>
                    @if($list->visibility === 'private')
                        <span class="bg-gray-200 text-gray-600 text-xs font-bold uppercase px-2 py-1 border border-black">Privada</span>
                    @endif
                </div>
                <h1 class="text-4xl md:text-6xl font-black font-display uppercase tracking-tighter mb-4">{{ $list->name }}</h1>
                <p class="text-lg font-medium text-gray-700 max-w-2xl mb-6 border-l-4 border-brand-yellow pl-4">
                    {{ $list->description ?? 'Sin descripción disponible.' }}
                </p>
                <!-- Datos usuario creador -->

                <div class="flex items-center gap-4">
                    <a href="{{ route('users.show', $list->user->id) }}" wire:navigate class="flex items-center gap-2 hover:opacity-80 transition-opacity">
                        <div class="w-8 h-8 rounded-full bg-gray-300 border border-black overflow-hidden">
                            <img src="{{ $list->user->avatar_url }}" alt="Avatar de {{ $list->user->name ?? 'Usuario' }}" class="w-full h-full object-cover">
                        </div>
                        <span class="text-sm font-bold uppercase">por <span class="underline hover:text-brand-blue">{{ $list->user->name ?? 'Desconocido' }}</span></span>
                    </a>
                    <span class="text-gray-400">|</span>
                    <span class="text-sm font-bold text-gray-600 uppercase">{{ $list->books->count() }} Libros</span>
                    <span class="text-gray-400">|</span>
                    <span class="text-sm font-bold text-gray-600 uppercase">Creada en {{ $list->created_at->format('M Y') }}</span>
                </div>
            </div>

            <div class="flex-shrink-0 flex gap-2">
                @auth
                    @if(Auth::id() !== $list->user_id)
                        <livewire:components.follow-button :model="$list" type="list" />
                    @endif
                @endauth
                <button @click="$dispatch('open-share-modal', { title: 'Compartir Lista', url: '{{ route('lists.show', $list->id) }}' })" class="neo-btn-primary text-sm px-4 py-2">
                    Compartir
                </button>
                @if(Auth::id() === $list->user_id)
                    <button wire:click="$set('showEditModal', true)" class="neo-btn-secondary text-sm px-4 py-2">
                        Editar Lista
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Cuadrícula de Libros -->
    <section>
        <div class="flex items-center justify-between mb-6 border-b-2 border-black pb-2">
            <h2 class="text-2xl font-black uppercase flex items-center gap-2">
                <span class="w-4 h-4 bg-brand-yellow border-2 border-black block"></span>
                Libros en esta lista
            </h2>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
            @forelse($list->books as $book)
                <div class="h-full" wire:key="book-{{ $book->isbn }}">
                    <x-book-card
                        :id="$book->isbn"
                        :title="$book->title"
                        :author="$book->authors->first()->name ?? 'Autor Desconocido'"
                        :cover="$book->cover_image"
                        :rating="0"
                    />
                </div>
            @empty
                <div class="col-span-full py-12 text-center border-2 border-dashed border-gray-400 bg-gray-50">
                    <p class="text-xl font-bold text-gray-500 uppercase">Aún no hay libros en esta lista.</p>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Modal de Edición (Livewire) -->
    @if($showEditModal)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
        >
            <div
                @click.away="$set('showEditModal', false)"
                class="bg-white border-2 border-black shadow-[8px_8px_0px_#000] w-full max-w-md p-6 relative"
            >
                <button wire:click="$set('showEditModal', false)" class="absolute top-4 right-4 text-2xl font-black hover:text-red-600">&times;</button>

                <h2 class="text-2xl font-black uppercase mb-6 font-display">Editar Lista</h2>

                <form wire:submit.prevent="updateList">
                    <div class="mb-4">
                        <label for="edit_name" class="block font-bold uppercase text-sm mb-2">Nombre de la Lista</label>
                        <input type="text" wire:model="name" id="edit_name" required
                               class="w-full border-2 border-black p-2 focus:outline-none focus:shadow-[4px_4px_0px_#000] transition-shadow">
                        @error('name') <span class="text-red-600 text-xs font-bold">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="edit_description" class="block font-bold uppercase text-sm mb-2">Descripción</label>
                        <textarea wire:model="description" id="edit_description" rows="3"
                                  class="w-full border-2 border-black p-2 focus:outline-none focus:shadow-[4px_4px_0px_#000] transition-shadow"></textarea>
                        @error('description') <span class="text-red-600 text-xs font-bold">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6">
                        <label for="edit_visibility" class="block font-bold uppercase text-sm mb-2">Visibilidad</label>
                        <select wire:model="visibility" id="edit_visibility"
                                class="w-full border-2 border-black p-2 appearance-none bg-white focus:outline-none focus:shadow-[4px_4px_0px_#000] transition-shadow">
                            <option value="public">Pública (Visible para todos)</option>
                            <option value="friends">Solo Amigos</option>
                            <option value="private">Privada (Solo yo)</option>
                        </select>
                        @error('visibility') <span class="text-red-600 text-xs font-bold">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex gap-4">
                        <button type="button" wire:click="$set('showEditModal', false)" class="flex-1 neo-btn-secondary py-3 uppercase font-black">Cancelar</button>
                        <button type="submit" class="flex-1 neo-btn-primary py-3 uppercase font-black">
                            <span wire:loading class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin inline-block mr-2"></span>
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
