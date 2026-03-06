@props(['item', 'url'])

<!-- Componente del Botón de Me Gusta Genérico -->
<div class="flex flex-col items-center" x-data="{
        likesCount: {{ $item->likes_count ?? 0 }},
        isLiked: {{ auth()->check() && $item->likes->contains('user_id', auth()->id()) ? 'true' : 'false' }},
        toggleLike() {
            @if(!auth()->check())
                window.location.href = '{{ route('login') }}';
                return;
            @endif
            fetch('{{ $url }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => res.json())
            .then(data => {
                this.isLiked = data.status === 'liked';
                this.likesCount = data.likes_count;
            });
        }
    }">

    <!-- Solo mostrar el botón si el usuario autenticado NO es el autor del ítem -->
    @if(!auth()->check() || auth()->id() !== $item->user_id)
        <button @click.prevent.stop="toggleLike" class="transition-transform active:scale-90"
            :class="isLiked ? 'text-brand-blue' : 'text-black hover:text-brand-blue'">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24"
                :fill="isLiked ? 'currentColor' : 'none'" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round">
                <path
                    d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3">
                </path>
            </svg>
        </button>
    @else
        <!-- Si el usuario autor, mostrar ícono fijo en gris (o quitarlo completamente si prefieres, aquí lo dejo como un indicador visual sin acción) -->
        <div class="text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path
                    d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3">
                </path>
            </svg>
        </div>
    @endif

    <!-- Contador de likes -->
    <span class="text-xs font-bold mt-1" x-text="likesCount"></span>
</div>