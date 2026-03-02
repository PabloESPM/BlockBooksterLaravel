@props(['genre', 'books'])

<div
    class="neo-card p-6 h-full hover:bg-gray-50 transition-all hover:shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] hover:-translate-y-1 cursor-default group">
    <h3
        class="text-2xl font-black uppercase mb-4 border-b-2 border-black pb-2 group-hover:text-brand-blue transition-colors">
        {{ $genre->name }}
    </h3>

    <div class="space-y-3">
        @forelse($books as $book)
            <div class="flex justify-between items-center text-sm font-bold border-b border-gray-300 pb-2 last:border-0">
                <a href="{{ route('books.show', $book->isbn) }}"
                    class="hover:text-brand-blue transition-colors line-clamp-1 pr-4">
                    {{ $book->title }}
                </a>
                <span class="text-gray-500 whitespace-nowrap bg-gray-100 px-1.5 py-0.5 border border-black/10">
                    {{ number_format($book->reviews_avg_rating ?? 0, 1) }}★
                </span>
            </div>
        @empty
            <p class="text-xs italic text-gray-400 uppercase">No ratings recently</p>
        @endforelse
    </div>
</div>