{{--
    Parcial Blade — Filtros Laterales de Libros

    Plantilla parcial incluida dentro del componente Livewire padre (pages.books.index).
    Los wire:model bindan directamente a las propiedades del padre.
    Recibe $genres y $countries desde el contexto del padre.
--}}

<aside class="w-full lg:w-72 flex-shrink-0 hidden lg:block">
    <div class="neo-card p-6 sticky top-24 space-y-8 bg-white">

        {{-- Ordenar por --}}
        <div class="mb-6">
            <h3 class="font-black text-sm mb-2 uppercase border-b-2 border-black pb-1">Ordenar por</h3>
            <select wire:model.live="sort" class="neo-input w-full text-sm">
                <option value="">Seleccionar orden</option>
                <option value="newest">Más recientes primero</option>
                <option value="oldest">Más antiguos primero</option>
                <option value="title_asc">Título A-Z</option>
                <option value="title_desc">Título Z-A</option>
            </select>
        </div>

        {{-- Género --}}
        <div class="mb-6">
            <h3 class="font-black text-sm mb-2 uppercase border-b-2 border-black pb-1">Género</h3>
            <select wire:model.live="genre" class="neo-input w-full text-sm">
                <option value="">Todos los géneros</option>
                @foreach($genres as $genreItem)
                    <option value="{{ $genreItem->id }}">{{ $genreItem->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- País del autor --}}
        <div class="mb-6">
            <h3 class="font-black text-sm mb-2 uppercase border-b-2 border-black pb-1">País del autor</h3>
            <select wire:model.live="country" class="neo-input w-full text-sm">
                <option value="">Todos los países</option>
                @foreach($countries as $countryItem)
                    <option value="{{ $countryItem->id }}">{{ $countryItem->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Idioma --}}
        <div class="mb-6">
            <h3 class="font-black text-sm mb-2 uppercase border-b-2 border-black pb-1">Idioma</h3>
            <select wire:model.live="language" class="neo-input w-full text-sm">
                <option value="">Todos los idiomas</option>
                <option value="en">Inglés</option>
                <option value="es">Español</option>
                <option value="fr">Francés</option>
                <option value="de">Alemán</option>
            </select>
        </div>

        {{-- Rango de páginas --}}
        <div class="mb-6">
            <h3 class="font-black text-sm mb-2 uppercase border-b-2 border-black pb-1">Rango de páginas</h3>
            <div class="flex gap-2">
                <input type="number" wire:model.live.debounce.500ms="pages_from" placeholder="Mín"
                       class="neo-input w-full text-sm px-2">
                <input type="number" wire:model.live.debounce.500ms="pages_to" placeholder="Máx"
                       class="neo-input w-full text-sm px-2">
            </div>
        </div>

        {{-- Año de publicación --}}
        <div class="mb-6">
            <h3 class="font-black text-sm mb-2 uppercase border-b-2 border-black pb-1">Año de publicación</h3>
            <div class="flex gap-2">
                <input type="number" wire:model.live.debounce.500ms="year_from" placeholder="Desde"
                       class="neo-input w-full text-sm px-2">
                <input type="number" wire:model.live.debounce.500ms="year_to" placeholder="Hasta"
                       class="neo-input w-full text-sm px-2">
            </div>
        </div>

        {{-- Valoración --}}
        <div class="mb-6">
            <h3 class="font-black text-sm mb-4 uppercase inline-block bg-brand-yellow px-2 py-0.5 border border-black">
                Valoración</h3>
            <div class="space-y-2 font-bold text-sm">
                @foreach([5, 4, 3, 2, 1] as $ratingValue)
                    <label class="flex items-center space-x-3 cursor-pointer group">
                        <input type="radio" wire:model.live="rating" value="{{ $ratingValue }}"
                            class="w-4 h-4 border-2 border-black rounded-full focus:ring-0 checked:bg-brand-yellow checked:text-black">
                        <span class="group-hover:translate-x-1 transition-transform flex items-center gap-1">
                            {{ $ratingValue }}+ <span class="text-brand-yellow text-lg leading-none">★</span>
                        </span>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Indicador de carga --}}
        <div wire:loading wire:target="sort, genre, country, language, pages_from, pages_to, year_from, year_to, rating"
             class="flex items-center justify-center gap-2 text-sm font-bold text-gray-500 py-2">
            <div class="w-4 h-4 border-2 border-black border-t-transparent rounded-full animate-spin"></div>
            Aplicando filtros...
        </div>

        {{-- Botón restablecer filtros --}}
        <button wire:click="resetFilters" class="neo-btn-secondary w-full block text-center text-sm">
            Restablecer filtros
        </button>

    </div>
</aside>
