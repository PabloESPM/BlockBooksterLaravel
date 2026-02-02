@props(['href' => null])

@if ($href)
    <a href="{{ $href }}"
        {{ $attributes->merge(['class' => 'neo-btn-secondary inline-flex items-center justify-center']) }}>
        {{ $slot }}
    </a>
@else
    <button type="button"
        {{ $attributes->merge(['class' => 'neo-btn-secondary']) }}>
        {{ $slot }}
    </button>
@endif
