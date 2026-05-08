<?php
/**
 * Página Livewire SFC — Mis Reseñas
 *
 * Absorbe la lógica de ReviewController@dashboardIndex.
 */

use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Layout('layouts.app')] #[Title('Mis Reseñas')] class extends Component {

    public function mount(): void
    {
        if (auth()->guest()) {
            $this->redirectRoute('login', navigate: true);
        }
    }

    #[Computed]
    public function resenias()
    {
        return auth()->user()->reviews()
            ->with(['book.authors', 'likes'])
            ->withCount('likes')
            ->latest()
            ->get();
    }
}; ?>

<div>
    <div x-data class="flex flex-col lg:flex-row gap-8">
        @include('livewire.pages.dashboard.partials.sidebar')

        <div class="flex-1">
            <header class="mb-8 border-b-4 border-black pb-4">
                <h1 class="text-3xl font-black uppercase font-display">Mis Reseñas</h1>
                <p class="text-gray-600 font-bold mt-1">Gestiona tus valoraciones de libros</p>
            </header>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($this->resenias as $review)
                    <x-review-card :review="$review" :showBook="true" :showActions="true" />
                @empty
                    <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-12 border-2 border-dashed border-gray-300 bg-gray-50">
                        <p class="text-xl font-bold uppercase text-gray-400 mb-2">Aún no hay reseñas</p>
                        <a href="{{ route('books.index') }}" wire:navigate class="neo-btn-primary inline-block text-sm">Explorar Libros</a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
