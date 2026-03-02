<div x-data="{ 
        show: false, 
        userId: null, 
        type: '', 
        title: '',
        content: '',
        loading: false,
        nextPage: 1,
        hasMore: false
    }" @open-user-list-modal.window="
        show = true; 
        userId = $event.detail.userId; 
        type = $event.detail.type;
        title = $event.detail.title;
        content = '';
        nextPage = 1;
        loadUsers();
    " x-show="show" style="display: none;"
    class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 text-left"
    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @keydown.escape.window="show = false">

    <div @click.away="show = false"
        class="bg-white border-2 border-black shadow-[8px_8px_0px_#000] w-full max-w-lg p-6 relative max-h-[80vh] flex flex-col"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="scale-95 opacity-0 translate-y-4"
        x-transition:enter-end="scale-100 opacity-100 translate-y-0">

        <button @click="show = false"
            class="absolute top-4 right-4 text-2xl font-black hover:text-red-600 z-50">&times;</button>

        <h2 class="text-xl font-black uppercase mb-6 font-display border-b-2 border-black pb-2" x-text="title"></h2>

        <div class="overflow-y-auto pr-2 custom-scrollbar flex-grow" id="user-list-container">
            <div x-html="content" class="space-y-4"></div>

            <div x-show="loading" class="py-4 text-center">
                <span class="font-bold uppercase animate-pulse">Loading...</span>
            </div>

            <div x-show="hasMore && !loading" class="py-4">
                <button @click="loadUsers()"
                    class="w-full py-2 bg-white border-2 border-black font-bold uppercase hover:bg-gray-100 transition-colors">
                    Load More
                </button>
            </div>

            <div x-show="!loading && content === ''" class="py-8 text-center text-gray-500 italic">
                No users found.
            </div>
        </div>
    </div>

    <script>
        function loadUsers() {
            this.loading = true;
            const url = type === 'followers'
                ? `/users/${userId}/load-followers?page=${nextPage}`
                : `/users/${userId}/load-following?page=${nextPage}`;

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    this.content += data.html;
                    this.hasMore = data.hasMore;
                    this.nextPage = data.nextPage;
                    this.loading = false;
                })
                .catch(err => {
                    console.error(err);
                    this.loading = false;
                });
        }
    </script>
</div>