<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\FavList;

new #[Layout('layouts.admin')] #[Title('Listas Reportadas')] class extends Component {
    use WithPagination;

    public function deleteList($id)
    {
        $list = FavList::find($id);
        if ($list) {
            $list->delete();
            session()->flash('message', 'Lista de usuario eliminada correctamente.');
        }
    }

    public function ignoreReport($id)
    {
        session()->flash('message', 'Reporte ignorado.');
    }

    public function with()
    {
        return [
            // Listas recientes representamos como reportadas para el ejemplo
            'lists' => FavList::with('user')->orderBy('created_at', 'desc')->paginate(10)
        ];
    }
};
?>

<div>
    <h1 class="text-4xl font-black uppercase font-display mb-8">Listas Reportadas (Visualización General)</h1>

    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-800 font-bold text-sm shadow-[2px_2px_0px_#000]">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($lists as $list)
            <x-card class="border-l-8 border-l-red-500" wire:key="list-{{ $list->id }}">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="font-black uppercase text-lg">{{ $list->name }}</h3>
                    <span class="bg-red-100 text-red-800 text-xs font-bold uppercase px-2 py-0.5 border border-red-200">
                        Inspeccionar
                    </span>
                </div>
                <p class="text-sm text-gray-600 mb-4">
                    Creada por: 
                    <span class="font-bold">{{ optional($list->user)->name ?? 'Anonimo' }}</span>
                    el {{ $list->created_at->format('d M, Y') }}
                </p>

                <div class="flex items-center gap-4 mt-auto">
                    <button wire:click="deleteList({{ $list->id }})" wire:confirm="¿Seguro que deseas eliminar la lista?"
                        class="neo-btn-secondary py-1 px-4 text-xs bg-red-600 text-white hover:bg-red-700 border-black transition-colors">
                        Eliminar Lista
                    </button>
                    <button wire:click="ignoreReport({{ $list->id }})"
                        class="text-xs font-bold uppercase text-gray-500 hover:text-black hover:underline transition-colors">
                        Descartar
                    </button>
                    
                    <a href="{{ route('lists.show', $list->id) }}" target="_blank" class="text-xs font-bold uppercase text-brand-blue hover:underline ml-auto">
                        Ver Lista Completa
                    </a>
                </div>
            </x-card>
        @empty
            <div class="col-span-full text-center text-gray-500 font-bold uppercase text-sm py-12 bg-gray-50 border-2 border-dashed border-gray-300">
                No hay reportes de listas activos en este momento.
            </div>
        @endforelse
    </div>
    
    <div class="mt-8">
        {{ $lists->links() }}
    </div>
</div>
