@props(['type' => 'info', 'message'])

@php
    $colors = [
        'success' => 'bg-green-50 border-green-400 text-green-700',
        'error' => 'bg-red-50 border-red-400 text-red-700',
        'warning' => 'bg-yellow-50 border-yellow-400 text-yellow-700',
        'info' => 'bg-blue-50 border-blue-400 text-blue-700',
    ];
    $colorClass = $colors[$type] ?? $colors['info'];
@endphp

<div {{ $attributes->merge(['class' => "$colorClass border-l-4 p-4 mb-4 rounded-r shadow-sm"]) }} role="alert">
    <div class="flex">
        <div class="py-1">
            <svg class="fill-current h-6 w-6 mr-4 opacity-75" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                @if($type === 'success')
                    <path
                        d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM6.7 9.29L9 11.59l4.3-4.3 1.4 1.42L9 14.41l-3.7-3.7 1.4-1.42z" />
                @elseif($type === 'error')
                    <path
                        d="M10 20a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-1-5h2v2H9v-2zm0-8h2v6H9V5z" />
                @else
                    <path
                        d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z" />
                @endif
            </svg>
        </div>
        <div>
            <p class="font-bold capitalize">{{ $type }}</p>
            <p class="text-sm">{{ $message }}</p>
        </div>
    </div>
</div>