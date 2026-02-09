@auth
    <div x-data="{
            show: false,
            reviewId: null,
            rating: 5,
            comment: '',
            updateUrl: ''
        }" @open-edit-review-modal.window="
            show = true; 
            reviewId = $event.detail.reviewId;
            rating = $event.detail.rating;
            comment = $event.detail.comment;
            updateUrl = $event.detail.updateUrl;
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

            <h2 class="text-xl font-black uppercase mb-6 font-display">Edit Review</h2>

            <form :action="updateUrl" method="POST">
                @csrf
                @method('PUT')

                <!-- Star Rating -->
                <div class="mb-6">
                    <label class="block font-bold uppercase text-sm mb-2">Rating</label>
                    <div class="flex items-center gap-1">
                        <template x-for="i in 5">
                            <button type="button" @click="rating = i" @mouseenter="hoverRating = i"
                                @mouseleave="hoverRating = null"
                                class="text-2xl focus:outline-none transition-transform hover:scale-110"
                                :class="{ 'text-brand-yellow': rating >= i, 'text-gray-300': rating < i }">
                                ★
                            </button>
                        </template>
                        <span class="ml-2 font-bold text-lg" x-text="rating + ' / 5'"></span>
                        <input type="hidden" name="rating" x-model="rating">
                    </div>
                </div>

                <!-- Comment -->
                <div class="mb-6">
                    <label for="edit_comment" class="block font-bold uppercase text-sm mb-2">Your Review</label>
                    <textarea name="comment" id="edit_comment" rows="5" required x-model="comment"
                        class="w-full border-2 border-black p-3 focus:outline-none focus:shadow-[4px_4px_0px_#000] focus:ring-0 transition-shadow resize-none"
                        placeholder="Write your review here..."></textarea>
                </div>

                <div class="flex gap-4 justify-end">
                    <button type="button" @click="show = false"
                        class="px-4 py-2 bg-white border-2 border-black font-bold uppercase hover:bg-gray-100 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-6 py-2 bg-[#FFA903] border-2 border-black font-bold uppercase shadow-[4px_4px_0px_#000] hover:translate-y-px hover:translate-x-px hover:shadow-[2px_2px_0px_#000] transition-all">
                        Update Review
                    </button>
                </div>
            </form>
        </div>
    </div>
@endauth