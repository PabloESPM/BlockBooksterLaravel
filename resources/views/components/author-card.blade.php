@props([
    'author',
    'showFollow' => true
])

<div class="neo-card p-4 text-center group hover:bg-blue-50 transition-all cursor-pointer hover:shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] hover:-translate-y-1">
    <a href="{{ route('authors.show', $author->id) }}">
        <div class="w-24 h-24 mx-auto bg-gray-300 rounded-full border-2 border-black mb-3 overflow-hidden">
            <img src="{{ $author->photo ?? 'https://ui-avatars.com/api/?name=' . urlencode($author->name) . '&background=random' }}"
                alt="{{ $author->name }}" class="w-full h-full object-cover">
        </div>
        <h3 class="text-sm font-bold uppercase mb-1 group-hover:underline">{{ $author->name }}</h3>
        <div class="text-xs font-bold text-gray-500">{{ $author->books_count }} Books</div>
        @auth
            @if($showFollow)
                <x-modals.follow-modal :followableId="$author->id" followableType="author" :isFollowing="false"
                    :followUrl="route('authors.follow', $author)"
                    class="mt-3 w-full text-xs font-black uppercase border-2 border-black py-1 hover:bg-black hover:text-white transition-colors" />
            @endif
        @endauth
    </a>
</div>
