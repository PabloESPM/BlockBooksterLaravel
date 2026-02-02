@props(['href' => null])

@if ($href)
    <a href="{{ $href }}"
        {{ $attributes->merge([
            'class' => 'bg-gray-100 text-black border-2 border-black font-bold px-4 py-2 hover:bg-gray-200 transition-colors inline-flex items-center justify-center'
        ]) }}>
        {{ $slot }}
    </a>
@else
    <button type="button"
        {{ $attributes->merge([
            'class' => 'bg-gray-100 text-black border-2 border-black font-bold px-4 py-2 hover:bg-gray-200 transition-colors'
        ]) }}>
        {{ $slot }}
    </button>
@endif
