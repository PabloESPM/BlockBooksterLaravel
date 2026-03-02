@auth
    <div x-data="{
            show: false,
            bookId: null,
            title: '',
            rating: 0,
            hoverRating: null,
            body: ''
        }" @open-add-review-modal.window="
            show = true;
            bookId = $event.detail.bookId;
            title = '';
            rating = 0;
            hoverRating = null;
            body = '';
        " x-show="show" style="display: none;"
        class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 text-left"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @keydown.escape.window="show = false">

        <div @click.away="show = false"
            class="bg-white border-2 border-black shadow-[8px_8px_0px_#000] w-full max-w-lg p-6 relative"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="scale-95 opacity-0 translate-y-4"
            x-transition:enter-end="scale-100 opacity-100 translate-y-0">

            <button @click="show = false"
                class="absolute top-4 right-4 text-2xl font-black hover:text-red-600 z-50">&times;</button>

            <h2 class="text-xl font-black uppercase mb-6 font-display">Escribe una Reseña</h2>

            <form action="{{ route('reviews.store') }}" method="POST">
                @csrf

                <input type="hidden" name="book_isbn" :value="bookId">

                <!-- Title -->
                <div class="mb-4">
                    <label for="create_title" class="block font-bold uppercase text-sm mb-2">Titulo de la Reseña</label>
                    <input type="text" name="title" id="create_title" required x-model="title"
                        class="w-full border-2 border-black p-3 focus:outline-none focus:shadow-[4px_4px_0px_#000] focus:ring-0 transition-shadow"
                        placeholder="Brief summary of your review...">
                </div>

                <!-- Star Rating -->
                <div class="mb-6">
                    <label class="block font-bold uppercase text-sm mb-2">Valoración</label>
                    <div class="flex items-center gap-1">
                        <template x-for="i in 5">
                            <button type="button" @click="rating = i" @mouseenter="hoverRating = i"
                                @mouseleave="hoverRating = null"
                                class="text-2xl focus:outline-none transition-transform hover:scale-110"
                                :class="{ 'text-brand-yellow': (hoverRating || rating) >= i, 'text-gray-300': (hoverRating || rating) < i }">
                                ★
                            </button>
                        </template>
                        <span class="ml-2 font-bold text-lg" x-text="(rating > 0 ? rating : 0) + ' / 5'"></span>
                        <input type="hidden" name="rating" x-model="rating">
                    </div>
                </div>

                <!-- Comment / Body -->
                <div class="mb-6">
                    <label for="create_body" class="block font-bold uppercase text-sm mb-2">Tu Reseña</label>
                    <textarea name="body" id="create_body" rows="5" required x-model="body"
                        class="w-full border-2 border-black p-3 focus:outline-none focus:shadow-[4px_4px_0px_#000] focus:ring-0 transition-shadow resize-none"
                        placeholder="Write your review here..."></textarea>
                </div>

                <div class="flex gap-4 justify-end">
                    <button type="button" @click="show = false"
                        class="px-4 py-2 bg-white border-2 border-black font-bold uppercase hover:bg-gray-100 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-6 py-2 bg-[#FFA903] border-2 border-black font-bold uppercase shadow-[4px_4px_0px_#000] hover:translate-y-px hover:translate-x-px hover:shadow-[2px_2px_0px_#000] transition-all">
                        Publicar Reseña
                    </button>
                </div>
            </form>
        </div>
    </div>
@endauth
