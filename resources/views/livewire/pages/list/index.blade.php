<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\FavList;

new #[Layout('layouts.app')] #[Title('Explorar Listas')] class extends Component {
    use WithPagination;

    public function with()
    {
        $lists = FavList::where('visibility', 'public')
            ->with([
                'user',
                'likes',
                'books' => function ($query) {
                    $query->take(5); // Vista previa de 5 libros para la tarjeta
                }
            ])
            ->withCount(['books', 'likes'])
            ->latest()
            ->paginate(12);

        return [
            'lists' => $lists,
        ];
    }
}; ?>

<div>
    <div class="mb-12 border-b-4 border-black pb-4">
        <h1 class="text-4xl md:text-6xl font-black font-display uppercase tracking-tighter">Explora <span
                class="text-brand-yellow text-shadow-neo">Listas</span></h1>
        <p class="text-lg font-bold mt-2 text-gray-600 uppercase tracking-widest">Colecciones seleccionadas por la comunidad
        </p>
    </div>

    <!-- Sección: Listas Públicas -->
    <section class="mb-16">
        <div class="flex items-center justify-between mb-6 border-b-2 border-black pb-2">
            <h2 class="text-2xl font-black uppercase flex items-center gap-2">
                <span class="w-4 h-4 bg-brand-blue border-2 border-black block"></span>
                Listas Públicas
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse ($lists as $list)
                <x-list-card :list="$list" />
            @empty
                <div class="col-span-full text-center py-10">
                    <h3 class="text-2xl font-black uppercase text-gray-400">No se han encontrado listas públicas.</h3>
                </div>
            @endforelse
        </div>
        
        <!-- Paginación usando el componente existente si es posible, o el de Livewire -->
        <div class="mt-8">
            {{ $lists->links('livewire.components.modals.pagination') }}
        </div>
    </section>

    <!-- Sección: Mejor Valoradas -->
    <section class="mb-16">
        <div class="flex items-center justify-between mb-6 border-b-2 border-black pb-2">
            <h2 class="text-2xl font-black uppercase flex items-center gap-2">
                <span class="w-4 h-4 bg-brand-yellow border-2 border-black block"></span>
                Mejor Valoradas
            </h2>
            <a href="#" class="text-sm font-bold uppercase hover:underline hover:text-brand-blue">Ver todas -></a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            @for ($i = 0; $i < 4; $i++)
                <div class="neo-card p-4 hover:bg-yellow-50 transition-colors cursor-pointer group">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold uppercase bg-black text-white px-2 py-0.5">Top 1%</span>
                        <div class="flex gap-1">
                            @for($j=0; $j<5; $j++)
                                <div class="w-3 h-3 bg-brand-yellow rounded-full border border-black"></div>
                            @endfor
                        </div>
                    </div>
                    <h3 class="text-lg font-bold uppercase leading-tight group-hover:underline">Lo mejor de 2025</h3>
                    <p class="text-xs text-gray-600 mt-1 uppercase">por Curador{{ $i }}</p>
                </div>
            @endfor
        </div>
    </section>

    <!-- Sección: Tendencias -->
    <section>
        <div class="flex items-center justify-between mb-6 border-b-2 border-black pb-2">
            <h2 class="text-2xl font-black uppercase flex items-center gap-2">
                <span class="w-4 h-4 bg-black block"></span>
                Tendencias
            </h2>
            <a href="#" class="text-sm font-bold uppercase hover:underline hover:text-brand-blue">Ver todas -></a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div
                class="bg-brand-blue text-white border-2 border-black shadow-[6px_6px_0px_#000] p-8 flex items-center justify-between">
                <div>
                    <h3 class="text-3xl font-black uppercase mb-2">TikTok BookTok</h3>
                    <p class="font-bold uppercase opacity-80 mb-6">Éxitos virales del momento</p>
                    <button
                        class="bg-white text-black border-2 border-black font-bold uppercase px-6 py-2 shadow-[4px_4px_0px_#000] hover:translate-y-[-2px] hover:shadow-[6px_6px_0px_#000] transition-all">Ver
                        Lista</button>
                </div>
                <div class="text-6xl font-black opacity-20 rotate-12">#1</div>
            </div>
            <div
                class="bg-brand-yellow text-black border-2 border-black shadow-[6px_6px_0px_#000] p-8 flex items-center justify-between">
                <div>
                    <h3 class="text-3xl font-black uppercase mb-2">Lecturas de Verano</h3>
                    <p class="font-bold uppercase opacity-80 mb-6">Ideales para la playa</p>
                    <button
                        class="bg-white text-black border-2 border-black font-bold uppercase px-6 py-2 shadow-[4px_4px_0px_#000] hover:translate-y-[-2px] hover:shadow-[6px_6px_0px_#000] transition-all">Ver
                        Lista</button>
                </div>
                <div class="text-6xl font-black opacity-20 rotate-12">#2</div>
            </div>
        </div>
    </section>
</div>
