{{--
    Parcial Blade — Búsqueda Avanzada de Libros

    Plantilla parcial incluida dentro del componente Livewire padre (pages.books.index).
    Los wire:model bindan directamente a las propiedades del padre.
    No es un componente Livewire independiente.
--}}

<div class="neo-card p-6 mb-12 bg-gray-100">
    <h2 class="text-xl font-black uppercase mb-4 flex items-center gap-2">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>
        Búsqueda Avanzada
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <input type="text" wire:model.live.debounce.400ms="title" placeholder="Título"
               class="neo-input bg-white">
        <input type="text" wire:model.live.debounce.400ms="author" placeholder="Autor"
               class="neo-input bg-white">
        <input type="text" wire:model.live.debounce.400ms="isbn" placeholder="ISBN"
               class="neo-input bg-white">
        {{-- Indicador de carga durante la búsqueda --}}
        <div class="flex items-center justify-center">
            <div wire:loading wire:target="title, author, isbn" class="flex items-center gap-2 text-sm font-bold uppercase text-gray-500">
                <div class="w-4 h-4 border-2 border-black border-t-transparent rounded-full animate-spin"></div>
                Buscando...
            </div>
            <span wire:loading.remove wire:target="title, author, isbn" class="text-sm font-bold uppercase text-gray-400">
                Escribe para buscar
            </span>
        </div>
    </div>
</div>
