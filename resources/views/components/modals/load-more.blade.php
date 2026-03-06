@props(['url', 'label' => 'Cargar más', 'target' => 'load-more-grid', 'initialHasMore' => true])

<div x-data="{
        loading: false,
        hasMore: {{ $initialHasMore ? 'true' : 'false' }},
        page: 2,
        fetchUrl: '{{ $url }}',
        targetId: '{{ $target }}'
    }" x-show="hasMore" class="mt-6">

    <button @click="
            loading = true;
            fetch(fetchUrl + '?page=' + page)
                .then(r => r.json())
                .then(data => {
                    const grid = document.getElementById(targetId);
                    if (grid) {
                        grid.insertAdjacentHTML('beforeend', data.html);
                    }
                    hasMore = data.hasMore;
                    page = data.nextPage;
                    loading = false;
                })
                .catch(() => { loading = false; });
        " :disabled="loading" class="w-full mt-6 neo-btn-secondary flex items-center justify-center gap-2"
        :class="{ 'opacity-60 cursor-wait': loading }">
        <svg x-show="loading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
        </svg>
        <span x-text="loading ? 'Cargando...' : '{{ $label }}'"></span>
    </button>
</div>