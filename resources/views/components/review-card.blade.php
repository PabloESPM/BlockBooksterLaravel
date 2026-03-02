@props(['review', 'showBook' => false, 'showActions' => false])

<div class="neo-card flex flex-col h-full bg-[#FFA903]/10 p-6 border-2 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] hover:-translate-y-1 transition-all"
     x-data="{
        likesCount: {{ $review->likes_count ?? 0 }},
        isLiked: {{ auth()->check() && $review->likes->contains('user_id', auth()->id()) ? 'true' : 'false' }},
        toggleLike() {
            @if(!auth()->check())
                window.location.href = '{{ route('login') }}';
                return;
            @endif
            fetch('{{ route('reviews.toggle-like', $review) }}', {
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

    <div class="flex items-start justify-between mb-4 border-b-2 border-black pb-4">
        <div class="flex items-center">
            @if($showBook && $review->book)
                <a href="{{ route('books.show', $review->book->isbn) }}">
                    <img src="{{ $review->book->cover ?? 'https://via.placeholder.com/50x75' }}"
                         alt="{{ $review->book->title }}"
                         class="w-10 h-14 object-cover border-2 border-black shadow-[2px_2px_0px_#000] mr-3">
                </a>
                <div>
                    <a href="{{ route('books.show', $review->book->isbn) }}"
                       class="font-bold text-sm uppercase hover:text-brand-blue line-clamp-1"
                       title="{{ $review->book->title }}">{{ $review->book->title }}</a>
                    <div class="flex text-brand-yellow text-xs gap-0.5 mt-1">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $review->rating)
                                <span class="text-black">★</span>
                            @else
                                <span class="text-gray-300">★</span>
                            @endif
                        @endfor
                    </div>
                </div>
            @else
                <a href="{{ route('users.show', $review->user->id) }}">
                    <img src="{{ $review->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($review->user->name) . '&background=random&color=fff' }}"
                         alt="{{ $review->user->name }}"
                         class="w-10 h-10 object-cover border-2 border-black shadow-[2px_2px_0px_#000] mr-3">
                </a>
                <div>
                    <a href="{{ route('users.show', $review->user->id) }}"
                       class="font-bold text-sm uppercase hover:text-brand-blue">{{ $review->user->name }}</a>
                    <div class="flex text-brand-yellow text-xs gap-0.5 mt-1">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $review->rating)
                                <span class="text-black">★</span>
                            @else
                                <span class="text-gray-300">★</span>
                            @endif
                        @endfor
                    </div>
                </div>
            @endif
        </div>
        <div class="flex flex-col items-center ml-2">
            <button @click="toggleLike" class="transition-transform active:scale-90"
                    :class="isLiked ? 'text-brand-blue' : 'text-black hover:text-brand-blue'">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24"
                     :fill="isLiked ? 'currentColor' : 'none'" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path
                        d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3">
                    </path>
                </svg>
            </button>
            <span class="text-xs font-bold mt-1" x-text="likesCount"></span>
        </div>
    </div>

    @if($review->title)
        <h3 class="font-display font-bold text-xl mb-2 leading-tight">"{{ $review->title }}"</h3>
    @endif

    <p class="text-sm font-medium line-clamp-4 mb-4 flex-grow italic text-gray-700">
        {{ $review->body }}
    </p>

    <div class="mt-auto border-t-2 border-black/10 pt-4 flex justify-between items-center">
        @if($showActions)
            <div class="flex gap-4">
                <button @click="$dispatch('open-edit-review-modal', {
                                    reviewId: '{{ $review->id }}',
                                    title: '{{ addslashes($review->title) }}',
                                    rating: {{ $review->rating }},
                                    body: '{{ addslashes($review->body) }}',
                                    updateUrl: '{{ route('reviews.update', $review) }}'
                                })" class="text-xs font-black uppercase hover:text-brand-blue underline">
                    Editar
                </button>
                <button @click="$dispatch('open-delete-modal', {
                                deleteUrl: '{{ route('reviews.destroy', $review) }}',
                                title: '¿Eliminar Reseña?',
                                message: '¿Estás seguro de que quieres eliminar esta reseña? Esta acción no se puede deshacer.'
                            })" class="text-xs font-black uppercase hover:text-red-600 underline">
                    Eliminar
                </button>
            </div>
        @else
            <span class="text-xs font-bold text-gray-500 uppercase">
                {{ $review->created_at->translatedFormat('d M, Y') }}
                @if($review->updated_at && $review->created_at->ne($review->updated_at))
                    (Editado)
                @endif
            </span>
        @endif

        @if(request()->routeIs('home') && !$showBook && $review->book)
            <a href="{{ route('books.show', $review->book->isbn) }}"
               class="text-xs font-black uppercase underline hover:text-brand-blue ml-auto flex items-center gap-1">
                Ver Libro ->
            </a>
        @elseif($showActions && $review->book)
            <a href="{{ route('books.show', $review->book->isbn) }}"
               class="text-xs font-black uppercase underline hover:text-brand-blue ml-auto">Ver Libro</a>
        @endif
    </div>
</div>
