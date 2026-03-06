@auth
    <div x-data="{
            show: false,
            deleteUrl: '',
            title: '¿Eliminar Ítem?',
            message: '¿Estás seguro de que deseas eliminar este ítem? Esta acción no se puede deshacer.'
        }" @open-delete-modal.window="
            show = true;
            deleteUrl = $event.detail.deleteUrl;
            if($event.detail.title) title = $event.detail.title;
            if($event.detail.message) message = $event.detail.message;
        " x-show="show" style="display: none;"
         class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 text-left"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @keydown.escape.window="show = false">

        <div @click.away="show = false"
             class="bg-white border-2 border-black shadow-[8px_8px_0px_#000] w-full max-w-md p-6 relative"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="scale-95 opacity-0 translate-y-4"
             x-transition:enter-end="scale-100 opacity-100 translate-y-0">

            <button @click="show = false"
                    class="absolute top-4 right-4 text-2xl font-black hover:text-red-600">&times;</button>

            <h2 class="text-2xl font-black uppercase mb-4 font-display text-red-600" x-text="title"></h2>

            <p class="font-bold text-gray-800 mb-6" x-text="message"></p>

            <form :action="deleteUrl" method="POST">
                @csrf
                @method('DELETE')

                <div class="flex gap-4 justify-end">
                    <button type="button" @click="show = false"
                            class="px-4 py-2 bg-white border-2 border-black font-bold uppercase hover:bg-gray-100 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="px-6 py-2 bg-red-500 text-white border-2 border-black font-bold uppercase shadow-[4px_4px_0px_#000] hover:translate-y-px hover:translate-x-px hover:shadow-[2px_2px_0px_#000] transition-all">
                        Eliminar Permanentemente
                    </button>
                </div>
            </form>
        </div>
    </div>
@endauth
