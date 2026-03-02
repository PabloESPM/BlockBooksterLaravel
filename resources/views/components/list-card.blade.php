@props(['list', 'dashboard' => false])

<div
    class="neo-card p-0 overflow-hidden group flex flex-col h-full hover:shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] hover:-translate-y-1 transition-all">
    <a href="{{ route('lists.show', $list->id) }}" class="block">
        <div class="h-32 bg-gray-200 border-b-2 border-black relative">
            <div class="grid grid-cols-5 h-full">
                <!-- Preview Covers -->
                @foreach($list->books->take(5) as $book)
                    <div class="bg-gray-300 border-r-2 border-black overflow-hidden relative">
                        @if($book->cover ?? $book->cover_image)
                            <img src="{{ $book->cover ?? $book->cover_image }}" class="w-full h-full object-cover">
                        @else
                            <div
                                class="absolute inset-0 flex items-center justify-center bg-brand-yellow/50 text-xs font-bold rotate-90">
                                Book</div>
                        @endif
                    </div>
                @endforeach
                @for($i = $list->books->count(); $i < 5; $i++)
                    <div class="bg-gray-100 border-r-2 border-black flex items-center justify-center">
                        <span class="text-gray-300 text-xs">Empty</span>
                    </div>
                @endfor
            </div>
        </div>
    </a>
    <div class="p-6 flex flex-col flex-grow">
        <div class="flex items-start justify-between mb-1 gap-2">
            <a href="{{ route('lists.show', $list->id) }}" class="min-w-0">
                <h3 class="text-xl font-bold uppercase group-hover:text-brand-blue transition-colors truncate">
                    {{ $list->name }}
                </h3>
            </a>
            @if($dashboard && isset($list->visibility))
                <span
                    class="bg-gray-100 text-gray-800 text-[10px] font-bold uppercase px-1.5 py-0.5 border border-black whitespace-nowrap mt-1">
                    {{ ucfirst($list->visibility) }}
                </span>
            @endif
        </div>

        <div class="flex items-center gap-2 mb-4">
            <div class="w-6 h-6 rounded-full bg-gray-300 border border-black overflow-hidden flex-shrink-0">
                <!-- User Avatar (Mock or Real) -->
                <img src="{{ $list->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($list->user->name ?? 'User') . '&background=random' }}"
                    class="w-full h-full object-cover">
            </div>
            <span class="text-xs font-bold uppercase text-gray-600 truncate">by <span
                    class="text-black">{{ $list->user->name ?? 'Unknown' }}</span></span>
        </div>

        <div class="mt-auto flex items-center justify-between text-xs font-bold border-t-2 border-black pt-4">
            <span>{{ $list->books_count ?? $list->books->count() }} Books</span>
            @if($dashboard)
                <span class="text-gray-500 truncate ml-2">Updated {{ $list->updated_at->diffForHumans() }}</span>
            @else
                <span class="text-gray-500 ml-2 whitespace-nowrap">{{ $list->created_at->format('M d, Y') }}</span>
            @endif
        </div>

        @if($dashboard)
            <div class="flex gap-2 mt-4 pt-4 border-t-2 border-dashed border-gray-300">
                <a href="{{ route('lists.show', $list) }}"
                    class="neo-btn-secondary py-1.5 px-3 text-xs flex-1 text-center">View</a>
                <button @click.prevent="$dispatch('open-delete-modal', { 
                                deleteUrl: '{{ route('dashboard.lists.destroy', $list) }}',
                                title: 'Delete List?',
                                message: 'Are you sure you want to delete this list? This action cannot be undone.'
                            })"
                    class="bg-red-100 border-2 border-black py-1.5 px-3 text-xs font-bold uppercase hover:bg-red-500 hover:text-white transition-colors">
                    Delete
                </button>
            </div>
        @endif
    </div>
</div>