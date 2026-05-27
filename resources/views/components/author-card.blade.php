@props([
'author',
'showFollow' => true
])

<div
    class="neo-card p-4 text-center group hover:bg-blue-50 transition-all cursor-pointer hover:shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] hover:-translate-y-1">
    <a href="{{ route('authors.show', $author->id) }}" class="block">
        <div class="w-24 h-24 mx-auto bg-gray-300 rounded-full border-2 border-black mb-3 overflow-hidden">
            <img src="{{ $author->photo }}"
                alt="{{ $author->name }}" class="w-full h-full object-cover">
        </div>
        <h3 class="text-sm font-bold uppercase mb-1 group-hover:underline">{{ $author->name }}</h3>
        <div class="text-xs font-bold text-gray-500">{{ $author->books_count }} Libros</div>
    </a>
    @auth
    @if($showFollow)
    <div class="mt-3 flex justify-center">
        <livewire:components.follow-button :model="$author" type="author" :wire:key="'follow-author-' . $author->id" />
    </div>
    @endif
    @endauth
</div>
