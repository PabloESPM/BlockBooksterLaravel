@props([
    'title',
    'count',
    'message',
    'emptyMessage'
])

<x-card class="hover:shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] hover:-translate-y-1 transition-all">
    <h3 class="font-black uppercase text-lg mb-4 pb-2 border-b-2 border-black/10">
        {{ $title }} ({{ $count }})
    </h3>
    @if($count > 0)
        <p class="text-sm text-gray-600 font-bold">{{ $message }}</p>
    @else
        <p class="text-sm text-gray-500 italic">{{ $emptyMessage }}</p>
    @endif
</x-card>
