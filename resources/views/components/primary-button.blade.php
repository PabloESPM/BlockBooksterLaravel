@props(['href' => null])

@if ($href)
    <a href="{{ $href }}"
        {{ $attributes->merge(['class' => 'neo-btn-primary inline-flex items-center justify-center']) }}>
        {{ $slot }}
    </a>
@else
    <button type="button"
        {{ $attributes->merge(['class' => 'neo-btn-primary']) }}>
        {{ $slot }}
    </button>
@endif
