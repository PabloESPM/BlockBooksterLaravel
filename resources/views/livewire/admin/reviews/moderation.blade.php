<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Review;

new #[Layout('layouts.admin')] #[Title('Moderación de Reseñas')] class extends Component {
    use WithPagination;

    public function deleteReview($id)
    {
        $review = Review::find($id);
        if ($review) {
            $review->delete();
            session()->flash("message", "Reseña eliminada correctamente.");
        }
    }

    public function ignoreReport($id)
    {
        // En una app real aquí se cambiaría el status o se eliminaría el flag de reportado.
        session()->flash("message", "Reporte ignorado.");
    }

    public function with()
    {
        return [
            // Cargamos con relations para evitar N+1
            'reviews' => Review::with(['user', 'book'])
                ->orderBy('created_at', 'desc')
                ->paginate(10)
        ];
    }
};
?>

<div>
    <h1 class="text-4xl font-black uppercase font-display mb-8">Moderación de Reseñas</h1>

    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-800 font-bold text-sm shadow-[2px_2px_0px_#000]">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6">
        @forelse($reviews as $review)
            <x-card class="border-l-8 border-l-brand-yellow" wire:key="review-{{ $review->id }}">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gray-200 rounded-full border border-black overflow-hidden flex-shrink-0">
                            <img src="{{ optional($review->user)->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(optional($review->user)->name ?? 'User').'&background=random' }}" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <div class="font-bold uppercase text-sm">{{ optional($review->user)->name ?? 'Usuario Eliminado' }}</div>
                            <div class="text-xs text-gray-500">Reportado por: <span class="text-red-600 font-bold">Revisión de contenido</span></div>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-gray-400 border border-gray-200 px-2 py-1 uppercase">{{ $review->created_at->diffForHumans() }}</span>
                </div>

                <div class="bg-gray-50 p-4 border border-gray-200 mb-4">
                    <h4 class="font-bold text-sm mb-1">Reseña en "{{ optional($review->book)->title ?? 'Libro Eliminado' }}"</h4>
                    <p class="text-sm italic text-gray-600">"{{ \Illuminate\Support\Str::limit($review->body, 250) }}"</p>
                </div>

                <div class="flex items-center gap-4">
                    <button wire:click="deleteReview({{ $review->id }})"
                        class="neo-btn-secondary py-1 px-4 text-xs bg-red-100 text-red-800 hover:bg-red-600 hover:text-white border-red-800 transition-colors">
                        Eliminar Reseña
                    </button>
                    <button wire:click="ignoreReport({{ $review->id }})" 
                        class="neo-btn-secondary py-1 px-4 text-xs bg-white hover:bg-gray-100 transition-colors">
                        Ignorar Reporte
                    </button>
                    @if($review->user)
                        <a href="{{ route('users.show', $review->user->id) }}" target="_blank" class="text-xs font-bold uppercase text-black hover:underline ml-auto">
                            Ver Perfil de Usuario
                        </a>
                    @endif
                </div>
            </x-card>
        @empty
            <div class="text-center text-gray-500 font-bold uppercase text-sm py-12 bg-gray-50 border-2 border-dashed border-gray-300">
                No hay más reportes pendientes. ¡Todo limpio!
            </div>
        @endforelse

        <div class="mt-4">
            {{ $reviews->links() }}
        </div>
    </div>
</div>
