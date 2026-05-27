<?php
/**
 * Página Livewire SFC — Mis Listas
 *
 * Muestra dos secciones:
 *  1. Listas creadas por el usuario (con opción de eliminar y editar)
 *  2. Listas que el usuario sigue/ha dado like
 *
 * Cada sección muestra un máximo de 6 listas inicialmente,
 * con carga dinámica incremental (+6) mediante el patrón loadMore.
 */

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Layout('layouts.app')] #[Title('Mis Listas')] class extends Component {

    /** Límite inicial y actual de listas creadas visibles */
    public int $createdLimit = 6;

    /** Límite inicial y actual de listas seguidas visibles */
    public int $followedLimit = 6;

    public function mount(): void
    {
        if (auth()->guest()) {
            $this->redirectRoute('login', navigate: true);
        }
    }

    /**
     * Incrementa el límite de la sección indicada (+6 por carga).
     */
    public function loadMore(string $section): void
    {
        if ($section === 'created') {
            $this->createdLimit += 6;
        } elseif ($section === 'followed') {
            $this->followedLimit += 6;
        }
    }

    /**
     * Pasa las variables calculadas a la vista.
     */
    public function with(): array
    {
        $user = auth()->user();

        // --- Listas CREADAS por el usuario ---
        $createdLists = $user->lists()
            ->with(['user', 'books', 'likes'])
            ->withCount(['likes', 'books'])
            ->latest()
            ->take($this->createdLimit)
            ->get();

        $totalCreated   = $user->lists()->count();
        $hasMoreCreated = $totalCreated > $this->createdLimit;

        // --- Listas SEGUIDAS (liked) por el usuario ---
        // Nota: el usuario puede ver listas seguidas independientemente de su visibilidad,
        // ya que las siguió conscientemente. Si el creador las elimina, la FK en cascada
        // limpia list_likes automáticamente. No filtramos por visibilidad aquí.
        $followedLists = $user->likedLists()
            ->with(['user', 'books', 'likes'])
            ->withCount(['books', 'likes'])
            ->latest('list_likes.created_at')
            ->take($this->followedLimit)
            ->get();

        $totalFollowed   = $user->likedLists()->count();
        $hasMoreFollowed = $totalFollowed > $this->followedLimit;

        return [
            'createdLists'    => $createdLists,
            'hasMoreCreated'  => $hasMoreCreated,
            'totalCreated'    => $totalCreated,
            'followedLists'   => $followedLists,
            'hasMoreFollowed' => $hasMoreFollowed,
            'totalFollowed'   => $totalFollowed,
        ];
    }
}; ?>

<div>
    <div class="flex flex-col lg:flex-row gap-8" x-data>
        @include('livewire.pages.dashboard.partials.sidebar')

        <div class="flex-1">
            {{-- Cabecera principal --}}
            <header class="mb-8 border-b-4 border-black pb-4 flex justify-between items-end">
                <div>
                    <h1 class="text-3xl font-black uppercase font-display">Mis Listas</h1>
                    <p class="text-gray-600 font-bold mt-1">Colecciones que has creado y listas que sigues</p>
                </div>
                <button @click="Livewire.dispatch('open-add-to-list-modal')"
                    class="neo-btn-primary text-sm flex items-center gap-2">
                    <span>+ Crear nueva lista</span>
                </button>
            </header>

            {{-- Mensajes de sesión --}}
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

            {{-- ═══════════════════════════════════════════════ --}}
            {{-- SECCIÓN 1: LISTAS CREADAS                       --}}
            {{-- ═══════════════════════════════════════════════ --}}
            <section class="mb-12">
                <h2 class="text-xl font-black uppercase mb-4 flex items-center gap-2 border-b-2 border-black pb-2">
                    <span class="w-3 h-3 bg-brand-blue border-2 border-black block"></span>
                    Listas Creadas
                    <span class="text-sm font-bold text-gray-500 normal-case ml-1">({{ $totalCreated }})</span>
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($createdLists as $list)
                        <div wire:key="created-{{ $list->id }}">
                            <x-list-card :list="$list" :dashboard="true" />
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12 border-2 border-dashed border-black bg-gray-50">
                            <p class="font-bold text-gray-500 uppercase">Aún no has creado ninguna lista.</p>
                            <button @click="Livewire.dispatch('open-add-to-list-modal')"
                                class="mt-4 text-brand-blue underline font-bold">Crea tu primera lista</button>
                        </div>
                    @endforelse
                </div>

                {{-- Botón cargar más listas creadas --}}
                @if($hasMoreCreated)
                    <div class="mt-6 flex justify-center">
                        <button wire:click="loadMore('created')" wire:loading.attr="disabled"
                            class="neo-btn-secondary px-8 py-3 uppercase font-black">
                            <span wire:loading wire:target="loadMore('created')"
                                class="inline-block w-4 h-4 border-2 border-black border-t-transparent rounded-full animate-spin mr-2"></span>
                            Cargar más listas creadas
                        </button>
                    </div>
                @endif
            </section>

            {{-- ═══════════════════════════════════════════════ --}}
            {{-- SECCIÓN 2: LISTAS SEGUIDAS                      --}}
            {{-- ═══════════════════════════════════════════════ --}}
            <section>
                <h2 class="text-xl font-black uppercase mb-4 flex items-center gap-2 border-b-2 border-black pb-2">
                    <span class="w-3 h-3 bg-brand-yellow border-2 border-black block"></span>
                    Listas Seguidas
                    <span class="text-sm font-bold text-gray-500 normal-case ml-1">({{ $totalFollowed }})</span>
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($followedLists as $list)
                        <div wire:key="followed-{{ $list->id }}">
                            {{-- Sin prop dashboard: no son listas propias, no se muestran botones de eliminar --}}
                            <x-list-card :list="$list" />
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12 border-2 border-dashed border-black bg-gray-50">
                            <p class="font-bold text-gray-500 uppercase">Aún no sigues ninguna lista.</p>
                            <a href="{{ route('lists.index') }}" wire:navigate
                                class="mt-4 inline-block text-brand-blue underline font-bold">
                                Explorar listas públicas
                            </a>
                        </div>
                    @endforelse
                </div>

                {{-- Botón cargar más listas seguidas --}}
                @if($hasMoreFollowed)
                    <div class="mt-6 flex justify-center">
                        <button wire:click="loadMore('followed')" wire:loading.attr="disabled"
                            class="neo-btn-secondary px-8 py-3 uppercase font-black">
                            <span wire:loading wire:target="loadMore('followed')"
                                class="inline-block w-4 h-4 border-2 border-black border-t-transparent rounded-full animate-spin mr-2"></span>
                            Cargar más listas seguidas
                        </button>
                    </div>
                @endif
            </section>

        </div>
    </div>
</div>
