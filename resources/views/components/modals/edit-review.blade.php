```php
@auth
    <div x-data="{
                show: false,
                reviewId: null,
                title: '',
                rating: 5,
                hoverRating: null,
                body: '',
                updateUrl: ''
            }" @open-edit-review-modal.window="
                show = true;
                reviewId = $event.detail.reviewId;
                title = $event.detail.title;
                rating = $event.detail.rating;
                hoverRating = null;
                body = $event.detail.body;
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

            <h2 class="text-xl font-black uppercase mb-6 font-display">Editar Reseña</h2>

            <form :action="updateUrl" method="POST">
                @csrf
                @method('PUT')

                <!-- Título -->
                <div class="mb-4">
                    <label for="edit_title" class="block font-bold uppercase text-sm mb-2">Título de la Reseña</label>
                    <input type="text" name="title" id="edit_title" required x-model="title"
                           class="w-full border-2 border-black p-3 focus:outline-none focus:shadow-[4px_4px_0px_#000] focus:ring-0 transition-shadow"
                           placeholder="Resumen breve de tu reseña...">
                </div>

                <!-- Calificación por Estrellas -->
                <div class="mb-6">
                    <label class="block font-bold uppercase text-sm mb-2">Calificación</label>
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

                <!-- Comentario / Cuerpo -->
                <div class="mb-6">
                    <label for="edit_body" class="block font-bold uppercase text-sm mb-2">Tu Reseña</label>
                    <textarea name="body" id="edit_body" rows="5" required x-model="body"
                              class="w-full border-2 border-black p-3 focus:outline-none focus:shadow-[4px_4px_0px_#000] focus:ring-0 transition-shadow resize-none"
                              placeholder="Escribe tu reseña aquí..."></textarea>
                </div>

                <div class="flex gap-4 justify-end">
                    <button type="button" @click="show = false"
                            class="px-4 py-2 bg-white border-2 border-black font-bold uppercase hover:bg-gray-100 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="px-6 py-2 bg-[#FFA903] border-2 border-black font-bold uppercase shadow-[4px_4px_0px_#000] hover:translate-y-px hover:translate-x-px hover:shadow-[2px_2px_0px_#000] transition-all">
                        Actualizar Reseña
                    </button>
                </div>
            </form>
        </div>
    </div>
@endauth
```
