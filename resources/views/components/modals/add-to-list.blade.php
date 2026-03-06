@auth
    <div x-data="{
                show: false,
                bookId: null
            }" @open-add-to-list-modal.window="show = true; bookId = $event.detail?.bookId || null" x-show="show"
        style="display: none;"
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

            <!-- Título dinámico -->
            <h2 class="text-xl font-black uppercase mb-4 font-display"
                x-text="bookId ? 'Agregar a la Lista' : 'Crear Nueva Lista'"></h2>

            <!-- Listas Existentes (Solo si se añade un libro) -->
            @if(auth()->user()->lists->count() > 0)
                <div class="mb-6" x-show="bookId">
                    <h3 class="font-bold text-sm uppercase mb-2 text-gray-500">Tus Listas</h3>
                    <div class="space-y-2 max-h-40 overflow-y-auto border border-black p-2 bg-gray-50">
                        @foreach(auth()->user()->lists as $list)
                            <form action="{{ route('dashboard.lists.attach', $list) }}" method="POST">
                                @csrf
                                <input type="hidden" name="book_isbn" :value="bookId">
                                <button type="submit"
                                    class="w-full text-left flex justify-between items-center group hover:bg-white p-1 transition-colors">
                                    <span class="font-bold truncate text-sm">{{ $list->name }}</span>
                                    <span
                                        class="text-xs bg-black text-white px-2 py-0.5 opacity-0 group-hover:opacity-100 transition-opacity">AGREGAR</span>
                                </button>
                            </form>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Crear Nueva Lista -->
            <div :class="bookId ? 'border-t-2 border-black pt-4' : ''">
                <h3 class="font-bold text-sm uppercase mb-3 text-brand-blue" x-show="bookId">O Crear Nueva Lista</h3>

                <form
                    :action="bookId ? '{{ route('dashboard.lists.storeAndAttach') }}' : '{{ route('dashboard.lists.store') }}'"
                    method="POST">
                    @csrf
                    <input type="hidden" name="book_isbn" :value="bookId" x-bind:disabled="!bookId">

                    <!-- Nombre de la lista -->
                    <div class="mb-3">
                        <label for="name" class="block font-bold uppercase text-xs mb-1" x-show="!bookId">Nombre de la
                            lista</label>
                        <input type="text" name="name" id="name" required
                            class="w-full border-2 border-black p-2 text-sm focus:outline-none focus:shadow-[2px_2px_0px_#000] transition-shadow placeholder-gray-500"
                            placeholder="Nombre de la Lista">
                    </div>

                    <!-- Descripción -->
                    <div class="mb-3">
                        <label for="description" class="block font-bold uppercase text-xs mb-1"
                            x-show="!bookId">Descripción</label>
                        <textarea name="description" id="description" rows="2"
                            class="w-full border-2 border-black p-2 text-sm focus:outline-none focus:shadow-[2px_2px_0px_#000] transition-shadow placeholder-gray-500"
                            placeholder="Descripción breve..."></textarea>
                    </div>

                    <!-- Visibilidad -->
                    <div class="mb-4">
                        <label for="visibility" class="block font-bold uppercase text-xs mb-1"
                            x-show="!bookId">Visibilidad</label>
                        <select name="visibility" id="visibility"
                            class="w-full border-2 border-black p-2 text-sm bg-white focus:outline-none focus:shadow-[2px_2px_0px_#000] transition-shadow">
                            <option value="public">Pública</option>
                            <option value="friends">Solo Amigos</option>
                            <option value="private">Privada</option>
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <button type="button" @click="show = false" x-show="!bookId"
                            class="w-1/3 py-2 bg-white border-2 border-black font-bold uppercase text-sm hover:bg-gray-100 transition-colors">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="py-2 bg-brand-yellow border-2 border-black font-bold uppercase text-sm shadow-[2px_2px_0px_#000] hover:translate-y-px hover:translate-x-px hover:shadow-none transition-all"
                            :class="bookId ? 'w-full' : 'w-2/3'" x-text="bookId ? 'Crear y Agregar' : 'Crear Lista'">
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endauth