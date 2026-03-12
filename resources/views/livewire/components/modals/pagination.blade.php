@if ($paginator->hasPages())
    <div class="mt-8 flex justify-center items-center gap-2">
        {{-- Anterior --}}
        @if ($paginator->onFirstPage())
            <span class="w-10 h-10 flex items-center justify-center border-2 border-black bg-gray-100 font-bold text-gray-400 cursor-not-allowed select-none">
                &lt;
            </span>
        @else
            <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled"
               class="w-10 h-10 flex items-center justify-center border-2 border-black bg-white font-bold hover:bg-black hover:text-white transition-colors">
                &lt;
            </button>
        @endif

        {{-- Números de página --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="flex items-end font-bold px-2 select-none" wire:key="dot-{{ $loop->iteration }}">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="w-10 h-10 flex items-center justify-center border-2 border-black bg-brand-blue text-white font-bold shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] select-none" wire:key="paginator-{{ $paginator->getPageName() }}-page-{{ $page }}">
                            {{ $page }}
                        </span>
                    @else
                        <button type="button" wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')" wire:key="paginator-{{ $paginator->getPageName() }}-page-{{ $page }}" wire:loading.attr="disabled"
                           class="w-10 h-10 flex items-center justify-center border-2 border-black bg-white font-bold hover:bg-black hover:text-white transition-colors">
                            {{ $page }}
                        </button>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Siguiente --}}
        @if ($paginator->hasMorePages())
            <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled"
               class="w-10 h-10 flex items-center justify-center border-2 border-black bg-white font-bold hover:bg-black hover:text-white transition-colors">
                &gt;
            </button>
        @else
            <span class="w-10 h-10 flex items-center justify-center border-2 border-black bg-gray-100 font-bold text-gray-400 cursor-not-allowed select-none">
                &gt;
            </span>
        @endif
    </div>
@endif
