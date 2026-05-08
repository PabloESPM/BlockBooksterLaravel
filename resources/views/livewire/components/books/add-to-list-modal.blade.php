<?php
/**
 * Componente Livewire SFC — Modal para Agregar a Lista / Crear Lista
 *
 * Modal global que permite al usuario autenticado:
 * 1. Agregar un libro a una lista existente
 * 2. Crear una nueva lista (con o sin libro adjunto)
 *
 * Se abre mediante el evento Alpine 'open-add-to-list-modal'.
 * La lógica replica FavListController@attachBook y @storeAndAttach.
 */

use Livewire\Attributes\On;
use Livewire\Attributes\Computed;
use Livewire\Component;
use App\Models\FavList;

new class extends Component {
    // ─── Estado del modal ─────────────────────────────────────────────
    public bool $show = false;
    public string $bookIsbn = '';

    // ─── Campos para crear nueva lista ────────────────────────────────
    public string $name = '';
    public string $description = '';
    public string $visibility = 'public';

    /**
     * Abrir el modal al recibir el evento de Alpine.
     */
    #[On('open-add-to-list-modal')]
    public function openModal(string $bookId = ''): void
    {
        if (auth()->guest()) {
            $this->redirectRoute('login', navigate: true);
            return;
        }

        $this->bookIsbn = $bookId;
        $this->reset(['name', 'description', 'visibility']);
        $this->visibility = 'public';
        $this->resetValidation();
        $this->show = true;
    }

    /**
     * Cerrar el modal y limpiar el estado.
     */
    public function closeModal(): void
    {
        $this->show = false;
        $this->reset(['bookIsbn', 'name', 'description', 'visibility']);
        $this->resetValidation();
    }

    /**
     * Propiedad computada: listas del usuario autenticado.
     */
    #[Computed]
    public function userLists()
    {
        if (auth()->guest()) {
            return collect();
        }
        return auth()->user()->lists()->latest()->get();
    }

    /**
     * Agregar un libro a una lista existente.
     * Replica la lógica de FavListController@attachBook.
     */
    public function addToList(int $listId): void
    {
        $list = FavList::findOrFail($listId);

        // Verificar que el usuario sea el propietario
        if ($list->user_id !== auth()->id()) {
            abort(403);
        }

        if (empty($this->bookIsbn)) {
            return;
        }

        // Verificar que el libro no esté ya en la lista
        if (!$list->books()->where('book_isbn', $this->bookIsbn)->exists()) {
            $list->books()->attach($this->bookIsbn, ['added_at' => now()]);
            session()->flash('status', '¡Libro añadido a la lista correctamente!');
        } else {
            session()->flash('status', 'El libro ya está en esta lista.');
        }

        $this->closeModal();
        $this->redirectIntended(default: request()->header('referer', '/'), navigate: true);
    }

    /**
     * Crear una nueva lista (y opcionalmente adjuntar el libro).
     * Replica FavListController@store y @storeAndAttach.
     */
    public function createList(): void
    {
        $validated = $this->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'visibility'  => ['required', 'in:public,private,friends'],
        ]);

        $list = auth()->user()->lists()->create($validated);

        // Si hay un libro, adjuntarlo a la lista recién creada
        if (!empty($this->bookIsbn)) {
            $this->validate([
                'bookIsbn' => ['required', 'string', 'exists:books,isbn'],
            ]);
            $list->books()->attach($this->bookIsbn, ['added_at' => now()]);
            session()->flash('status', '¡Lista creada y libro añadido correctamente!');
        } else {
            session()->flash('status', '¡Lista creada correctamente!');
        }

        $this->closeModal();
        $this->redirectIntended(default: request()->header('referer', '/'), navigate: true);
    }
}; ?>

{{-- El <div> raíz SIEMPRE debe existir para que Livewire pueda anclar el componente.
     La directiva @auth se mueve al interior para que los invitados vean un div vacío. --}}
<div>
    @auth
    {{-- Fondo oscuro del modal --}}
    <div
        x-data
        x-show="$wire.show"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @keydown.escape.window="$wire.show && $wire.closeModal()"
        style="display: none;"
        class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 text-left">

        {{-- Contenedor del modal --}}
        <div
            @click.away="$wire.closeModal()"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="scale-95 opacity-0 translate-y-4"
            x-transition:enter-end="scale-100 opacity-100 translate-y-0"
            class="bg-white border-2 border-black shadow-[8px_8px_0px_#000] w-full max-w-sm p-6 relative max-h-[90vh] overflow-y-auto">

            {{-- Botón cerrar --}}
            <button wire:click="closeModal"
                class="absolute top-4 right-4 text-2xl font-black hover:text-red-600 z-50">&times;</button>

            {{-- Título dinámico --}}
            <h2 class="text-xl font-black uppercase mb-4 font-display">
                @if($bookIsbn)
                    Agregar a la Lista
                @else
                    Crear Nueva Lista
                @endif
            </h2>

            {{-- Listas Existentes (solo si se está añadiendo un libro) --}}
            @if($bookIsbn && $this->userLists->count() > 0)
                <div class="mb-6">
                    <h3 class="font-bold text-sm uppercase mb-2 text-gray-500">Tus Listas</h3>
                    <div class="space-y-2 max-h-40 overflow-y-auto border border-black p-2 bg-gray-50">
                        @foreach($this->userLists as $list)
                            <button wire:click="addToList({{ $list->id }})"
                                class="w-full text-left flex justify-between items-center group hover:bg-white p-1 transition-colors">
                                <span class="font-bold truncate text-sm">{{ $list->name }}</span>
                                <span class="text-xs bg-black text-white px-2 py-0.5 opacity-0 group-hover:opacity-100 transition-opacity">
                                    AGREGAR
                                </span>
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Crear Nueva Lista --}}
            <div @class(['border-t-2 border-black pt-4' => !empty($bookIsbn)])>
                @if($bookIsbn)
                    <h3 class="font-bold text-sm uppercase mb-3 text-brand-blue">O Crear Nueva Lista</h3>
                @endif

                <form wire:submit="createList">
                    {{-- Nombre de la lista --}}
                    <div class="mb-3">
                        @if(!$bookIsbn)
                            <label for="list_name" class="block font-bold uppercase text-xs mb-1">Nombre de la lista</label>
                        @endif
                        <input type="text" wire:model="name" id="list_name" required
                            class="w-full border-2 border-black p-2 text-sm focus:outline-none focus:shadow-[2px_2px_0px_#000] transition-shadow placeholder-gray-500"
                            placeholder="Nombre de la Lista">
                        @error('name') <span class="text-red-600 text-xs font-bold">{{ $message }}</span> @enderror
                    </div>

                    {{-- Descripción --}}
                    <div class="mb-3">
                        @if(!$bookIsbn)
                            <label for="list_description" class="block font-bold uppercase text-xs mb-1">Descripción</label>
                        @endif
                        <textarea wire:model="description" id="list_description" rows="2"
                            class="w-full border-2 border-black p-2 text-sm focus:outline-none focus:shadow-[2px_2px_0px_#000] transition-shadow placeholder-gray-500"
                            placeholder="Descripción breve..."></textarea>
                    </div>

                    {{-- Visibilidad --}}
                    <div class="mb-4">
                        @if(!$bookIsbn)
                            <label for="list_visibility" class="block font-bold uppercase text-xs mb-1">Visibilidad</label>
                        @endif
                        <select wire:model="visibility" id="list_visibility"
                            class="w-full border-2 border-black p-2 text-sm bg-white focus:outline-none focus:shadow-[2px_2px_0px_#000] transition-shadow">
                            <option value="public">Pública</option>
                            <option value="friends">Solo Amigos</option>
                            <option value="private">Privada</option>
                        </select>
                    </div>

                    {{-- Botones de acción --}}
                    <div class="flex gap-2">
                        @if(!$bookIsbn)
                            <button type="button" wire:click="closeModal"
                                class="w-1/3 py-2 bg-white border-2 border-black font-bold uppercase text-sm hover:bg-gray-100 transition-colors">
                                Cancelar
                            </button>
                        @endif
                        <button type="submit"
                            class="py-2 bg-brand-yellow border-2 border-black font-bold uppercase text-sm shadow-[2px_2px_0px_#000] hover:translate-y-px hover:translate-x-px hover:shadow-none transition-all flex items-center justify-center gap-2 {{ $bookIsbn ? 'w-full' : 'w-2/3' }}">
                            <div wire:loading wire:target="createList" class="w-4 h-4 border-2 border-black border-t-transparent rounded-full animate-spin"></div>
                            {{ $bookIsbn ? 'Crear y Agregar' : 'Crear Lista' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endauth
</div>
