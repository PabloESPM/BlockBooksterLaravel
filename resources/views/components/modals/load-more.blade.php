@props(['url', 'label' => 'Load More'])

<div x-data="{
        loading: false,
        hasMore: true,
        page: 2,
        fetchUrl: '{{ $url }}'
    }" x-show="hasMore" id="load-more-container">

    <button @click="
            loading = true;
            fetch(fetchUrl + '?page=' + page)
                .then(r => r.json())
                .then(data => {
                    document.getElementById('load-more-grid').insertAdjacentHTML('beforeend', data.html);
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
        <span x-text="loading ? 'Loading...' : '{{ $label }}'"></span>
    </button>
</div>