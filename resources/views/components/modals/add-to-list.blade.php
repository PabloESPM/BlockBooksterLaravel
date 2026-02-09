@auth
    <div x-data="{
            show: false,
            bookId: null
        }" @open-add-to-list-modal.window="show = true; bookId = $event.detail.bookId" x-show="show" style="display: none;"
        class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 text-left"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @keydown.escape.window="show = false">

        <div @click.away="show = false"
            class="bg-white border-2 border-black shadow-[8px_8px_0px_#000] w-full max-w-sm p-6 relative max-h-[90vh] overflow-y-auto"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="scale-95 opacity-0 translate-y-4"
            x-transition:enter-end="scale-100 opacity-100 translate-y-0">

            <button @click="show = false"
                class="absolute top-4 right-4 text-2xl font-black hover:text-red-600 z-50">&times;</button>

            <h2 class="text-xl font-black uppercase mb-4 font-display">Add to List</h2>

            <!-- Existing Lists -->
            @if(auth()->user()->lists->count() > 0)
                <div class="mb-6">
                    <h3 class="font-bold text-sm uppercase mb-2 text-gray-500">Your Lists</h3>
                    <div class="space-y-2 max-h-40 overflow-y-auto border border-black p-2 bg-gray-50">
                        @foreach(auth()->user()->lists as $list)
                            <form action="{{ route('dashboard.lists.attach', $list) }}" method="POST">
                                @csrf
                                <input type="hidden" name="book_isbn" :value="bookId">
                                <button type="submit"
                                    class="w-full text-left flex justify-between items-center group hover:bg-white p-1 transition-colors">
                                    <span class="font-bold truncate text-sm">{{ $list->name }}</span>
                                    <span
                                        class="text-xs bg-black text-white px-2 py-0.5 opacity-0 group-hover:opacity-100 transition-opacity">ADD</span>
                                </button>
                            </form>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Create New List -->
            <div class="border-t-2 border-black pt-4">
                <h3 class="font-bold text-sm uppercase mb-3 text-brand-blue">Or Create New List</h3>
                <form action="{{ route('dashboard.lists.storeAndAttach') }}" method="POST">
                    @csrf
                    <input type="hidden" name="book_isbn" :value="bookId">

                    <div class="mb-3">
                        <input type="text" name="name" required
                            class="w-full border-2 border-black p-2 text-sm focus:outline-none focus:shadow-[2px_2px_0px_#000] transition-shadow placeholder-gray-500"
                            placeholder="New List Name">
                    </div>

                    <div class="mb-4">
                        <select name="visibility"
                            class="w-full border-2 border-black p-2 text-sm bg-white focus:outline-none focus:shadow-[2px_2px_0px_#000] transition-shadow">
                            <option value="private">Private</option>
                            <option value="public">Public</option>
                            <option value="friends">Friends Only</option>
                        </select>
                    </div>

                    <button type="submit"
                        class="w-full py-2 bg-brand-yellow border-2 border-black font-bold uppercase text-sm shadow-[2px_2px_0px_#000] hover:translate-y-px hover:translate-x-px hover:shadow-none transition-all">
                        Create & Add
                    </button>
                </form>
            </div>
        </div>
    </div>
@endauth