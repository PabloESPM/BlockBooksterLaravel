<?php
/**
 * Página Livewire SFC — Mis Listas
 *
 * Absorbe la lógica de FavListController@dashboardIndex.
 */

use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Layout('layouts.app')] #[Title('Mis Listas')] class extends Component {

    public function mount(): void
    {
        if (auth()->guest()) {
            $this->redirectRoute('login', navigate: true);
        }
    }

    #[Computed]
    public function listas()
    {
        return auth()->user()->lists()
            ->with(['likes', 'books'])
            ->withCount(['likes', 'books'])
            ->latest()
            ->get();
    }
}; ?>

<div>
    <div class="flex flex-col lg:flex-row gap-8" x-data>
        @include('livewire.pages.dashboard.partials.sidebar')

        <div class="flex-1">
            <header class="mb-8 border-b-4 border-black pb-4 flex justify-between items-end">
                <div>
                    <h1 class="text-3xl font-black uppercase font-display">Mis Listas</h1>
                    <p class="text-gray-600 font-bold mt-1">Colecciones que has creado</p>
                </div>
                <button @click="Livewire.dispatch('open-add-to-list-modal')"
                    class="neo-btn-primary text-sm flex items-center gap-2">
                    <span>+ Crear nueva lista</span>
                </button>
            </header>

            @if(session('success'))
                <div class="mb-6 p-4 border-2 border-black bg-green-100 font-bold uppercase relative">
                    {{ session('success') }}
                    <button onclick="this.parentElement.remove()" class="absolute top-2 right-2 text-xl">&times;</button>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-4 border-2 border-black bg-red-100 font-bold relative">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button onclick="this.parentElement.remove()" class="absolute top-2 right-2 text-xl">&times;</button>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($this->listas as $list)
                    <x-list-card :list="$list" :dashboard="true" />
                @empty
                    <div class="col-span-full text-center py-12 border-2 border-dashed border-black bg-gray-50">
                        <p class="font-bold text-gray-500 uppercase">Aún no has creado ninguna lista.</p>
                        <button @click="Livewire.dispatch('open-add-to-list-modal')"
                            class="mt-4 text-brand-blue underline font-bold">Crea tu primera lista</button>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
