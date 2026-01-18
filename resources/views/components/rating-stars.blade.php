@props(['rating' => 0, 'class' => 'w-5 h-5'])

<div class="flex text-brand-yellow">
    @for ($i = 1; $i <= 5; $i++)
        @if ($i <= $rating)
            <!-- Full Star -->
            <svg class="{{ $class }} fill-current" viewBox="0 0 24 24">
                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
            </svg>
        @elseif ($i - 0.5 <= $rating)
            <!-- Half Star (Approximated or skip for simple CSS) -->
            <svg class="{{ $class }} fill-current opacity-50" viewBox="0 0 24 24">
                <path
                    d="M22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21 12 17.27 18.18 21l-1.64-7.03L22 9.24zM12 15.4V6.1l1.71 4.16 4.38.38-3.32 2.88 1 4.28L12 15.4z" />
            </svg>
        @else
            <!-- Empty Star -->
            <svg class="{{ $class }} text-gray-300 fill-current" viewBox="0 0 24 24">
                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
            </svg>
        @endif
    @endfor
</div>