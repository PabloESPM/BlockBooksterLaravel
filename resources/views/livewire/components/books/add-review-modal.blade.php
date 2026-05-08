<?php
/**
 * Componente Livewire SFC — Modal para Escribir Reseña
 *
 * Modal global que permite al usuario autenticado escribir una reseña
 * para un libro. Se abre mediante el evento Alpine 'open-add-review-modal'.
 * La lógica de guardado replica ReviewController@store.
 */

use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\Review;

new class extends Component {
    // ─── Estado del modal ─────────────────────────────────────────────
    public bool $show = false;
    public string $bookIsbn = '';

    // ─── Campos del formulario ────────────────────────────────────────
    public string $title = '';
    public int $rating = 0;
    public string $body = '';

    /**
     * Abrir el modal al recibir el evento de Alpine.
     * Se usa #[On] para escuchar el evento Livewire despachado desde Alpine.
     */
    #[On('open-add-review-modal')]
    public function openModal(string $bookId = ''): void
    {
        if (auth()->guest()) {
            $this->redirectRoute('login', navigate: true);
            return;
        }

        $this->bookIsbn = $bookId;
        $this->reset(['title', 'rating', 'body']);
        $this->resetValidation();
        $this->show = true;
    }

    /**
     * Cerrar el modal y limpiar el estado.
     */
    public function closeModal(): void
    {
        $this->show = false;
        $this->reset(['bookIsbn', 'title', 'rating', 'body']);
        $this->resetValidation();
    }

    /**
     * Guardar la reseña.
     * Replica la lógica de ReviewController@store.
     */
    public function save(): void
    {
        $validated = $this->validate([
            'bookIsbn' => ['required', 'string', 'exists:books,isbn'],
            'title'    => ['nullable', 'string', 'max:255'],
            'rating'   => ['required', 'integer', 'min:1', 'max:5'],
            'body'     => ['required', 'string', 'max:1000'],
        ]);

        $user = auth()->user();

        // Crear o actualizar la reseña del usuario para este libro
        $user->reviews()->updateOrCreate(
            ['book_isbn' => $validated['bookIsbn']],
            [
                'title' => $validated['title'] ?: null,
                'body'  => $validated['body'],
            ]
        );

        // Actualizar o crear la valoración en la tabla pivote book_user
        $user->books()->updateOrCreate(
            ['book_isbn' => $validated['bookIsbn']],
            ['rating' => $validated['rating']]
        );

        $this->closeModal();

        // Notificar éxito y refrescar la página
        session()->flash('status', '¡Reseña publicada correctamente!');
        $this->redirectIntended(default: request()->header('referer', '/'), navigate: true);
    }
}; ?>

{{-- El <div> raíz SIEMPRE debe existir para Livewire. @auth va dentro. --}}
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
            class="bg-white border-2 border-black shadow-[8px_8px_0px_#000] w-full max-w-lg p-6 relative">

            {{-- Botón cerrar --}}
            <button wire:click="closeModal"
                class="absolute top-4 right-4 text-2xl font-black hover:text-red-600 z-50">&times;</button>

            <h2 class="text-xl font-black uppercase mb-6 font-display">Escribe una Reseña</h2>

            <form wire:submit="save">
                {{-- Título de la reseña --}}
                <div class="mb-4">
                    <label for="review_title" class="block font-bold uppercase text-sm mb-2">Título de la Reseña</label>
                    <input type="text" wire:model="title" id="review_title"
                        class="w-full border-2 border-black p-3 focus:outline-none focus:shadow-[4px_4px_0px_#000] focus:ring-0 transition-shadow"
                        placeholder="Resumen breve de tu reseña...">
                    @error('title') <span class="text-red-600 text-xs font-bold mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Selector de estrellas (Alpine para interactividad visual) --}}
                <div class="mb-6" x-data="{ hoverRating: null }">
                    <label class="block font-bold uppercase text-sm mb-2">Valoración</label>
                    <div class="flex items-center gap-1">
                        <template x-for="i in 5">
                            <button type="button"
                                @click="$wire.set('rating', i)"
                                @mouseenter="hoverRating = i"
                                @mouseleave="hoverRating = null"
                                class="text-2xl focus:outline-none transition-transform hover:scale-110"
                                :class="{
                                    'text-brand-yellow': (hoverRating || $wire.rating) >= i,
                                    'text-gray-300': (hoverRating || $wire.rating) < i
                                }">
                                ★
                            </button>
                        </template>
                        <span class="ml-2 font-bold text-lg"
                            x-text="($wire.rating > 0 ? $wire.rating : 0) + ' / 5'"></span>
                    </div>
                    @error('rating') <span class="text-red-600 text-xs font-bold mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Cuerpo de la reseña --}}
                <div class="mb-6">
                    <label for="review_body" class="block font-bold uppercase text-sm mb-2">Tu Reseña</label>
                    <textarea wire:model="body" id="review_body" rows="5"
                        class="w-full border-2 border-black p-3 focus:outline-none focus:shadow-[4px_4px_0px_#000] focus:ring-0 transition-shadow resize-none"
                        placeholder="Escribe tu reseña aquí..."></textarea>
                    @error('body') <span class="text-red-600 text-xs font-bold mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Botones de acción --}}
                <div class="flex gap-4 justify-end">
                    <button type="button" wire:click="closeModal"
                        class="px-4 py-2 bg-white border-2 border-black font-bold uppercase hover:bg-gray-100 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-6 py-2 bg-[#FFA903] border-2 border-black font-bold uppercase shadow-[4px_4px_0px_#000] hover:translate-y-px hover:translate-x-px hover:shadow-[2px_2px_0px_#000] transition-all flex items-center gap-2">
                        <div wire:loading wire:target="save" class="w-4 h-4 border-2 border-black border-t-transparent rounded-full animate-spin"></div>
                        Publicar Reseña
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endauth
</div>
