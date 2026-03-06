@props(['paginator'])

@if ($paginator->hasPages())
    <div class="mt-16 flex justify-center items-center gap-2">

        {{-- Anterior --}}
        @if ($paginator->onFirstPage())
            <span class="w-10 h-10 flex items-center justify-center border-2 border-black bg-gray-100 font-bold text-gray-400 cursor-not-allowed">
                &lt;
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}"
               class="w-10 h-10 flex items-center justify-center border-2 border-black bg-white font-bold hover:bg-black hover:text-white transition-colors">
                &lt;
            </a>
        @endif

        {{-- Números de página --}}
        @foreach ($paginator->getUrlRange(
            max(1, $paginator->currentPage() - 2),
            min($paginator->lastPage(), $paginator->currentPage() + 2)
        ) as $page => $url)
            @if ($page == $paginator->currentPage())
                <span class="w-10 h-10 flex items-center justify-center border-2 border-black bg-brand-blue text-white font-bold shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                    {{ $page }}
                </span>
            @else
                <a href="{{ $url }}"
                   class="w-10 h-10 flex items-center justify-center border-2 border-black bg-white font-bold hover:bg-black hover:text-white transition-colors">
                    {{ $page }}
                </a>
            @endif
        @endforeach

        {{-- Puntos suspensivos + Última página --}}
        @if ($paginator->currentPage() + 2 < $paginator->lastPage())
            <span class="flex items-end font-bold px-2">...</span>
            <a href="{{ $paginator->url($paginator->lastPage()) }}"
               class="w-10 h-10 flex items-center justify-center border-2 border-black bg-white font-bold hover:bg-black hover:text-white transition-colors">
                {{ $paginator->lastPage() }}
            </a>
        @endif

        {{-- Siguiente --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"
               class="w-10 h-10 flex items-center justify-center border-2 border-black bg-white font-bold hover:bg-black hover:text-white transition-colors">
                &gt;
            </a>
        @else
            <span class="w-10 h-10 flex items-center justify-center border-2 border-black bg-gray-100 font-bold text-gray-400 cursor-not-allowed">
                &gt;
            </span>
        @endif

    </div>
@endif

