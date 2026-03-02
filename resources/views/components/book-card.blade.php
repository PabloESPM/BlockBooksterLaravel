@props(['title', 'author', 'cover', 'rating' => 0, 'id'])

<div x-data
    class="group relative flex flex-col h-full bg-white border-2 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] hover:-translate-y-1 transition-all">
    <!-- Full Card Link -->
    <a href="{{ route('books.show', $id) }}" class="absolute inset-0 z-10 focus:outline-none"
        aria-label="View {{ $title }}"></a>
    <!-- Cover Image Aspect 2:3 -->
    <div class="aspect-[2/3] w-full border-b-2 border-black relative overflow-hidden bg-gray-100">
        <img src="{{ $cover ?? 'https://via.placeholder.com/300x450' }}" alt="{{ $title }}"
            class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-300"
            loading="lazy">

        <!-- Hard Badge for Rating -->
        @php
            $bookForRating = \App\Models\Book::with('reviews')->find($id);
            $totalReviewsForRating = $bookForRating ? $bookForRating->reviews->count() : 0;

            if ($totalReviewsForRating > 0) {
                $sum = $bookForRating->reviews->sum('rating');
                $averageRating = round(($sum / $totalReviewsForRating) * 2) / 2;
            } else {
                $averageRating = $rating > 0 ? round($rating * 2) / 2 : 0;
            }
        @endphp

        @if($averageRating > 0)
            <div class="absolute top-2 right-2 bg-brand-yellow border-2 border-black px-2 py-1 font-bold text-xs shadow-sm">
                {{ number_format($averageRating, 1) }} ★
            </div>
        @endif
    </div>

    <!-- Panel de control -->
    <div class="p-3 flex flex-col flex-grow">
        <h3 class="font-display font-bold text-lg leading-tight mb-1 truncate">{{ $title }}</h3>
        <p class="text-sm text-gray-600 truncate mb-3">{{ $author }}</p>

        <!-- Botones de Acción -->
        <div class="mt-auto pt-2 border-t-2 border-black/10 flex justify-between items-center relative z-20">
            @auth
                <button @click.prevent="$dispatch('open-add-to-list-modal', { bookId: '{{ $id }}' })"
                    class="text-xs font-bold uppercase hover:bg-brand-yellow hover:text-black px-2 py-1 -ml-2 transition-colors">
                    + Lista
                </button>
            @else
                <div></div> <!-- Spacio -->
            @endauth
            @auth
                <button @click.prevent="$dispatch('open-add-review-modal', { bookId: '{{ $id }}' })"
                    class="text-xs font-bold uppercase hover:bg-brand-blue hover:text-white px-2 py-1 -mr-2 transition-colors">
                    + Reseña
                </button>
            @endauth
        </div>
    </div>
</div>
