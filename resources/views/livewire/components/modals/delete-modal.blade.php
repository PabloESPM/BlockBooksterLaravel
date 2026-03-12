<div
    x-data="{
        show: false,
        action: '',
        params: null,
        title: '¿Eliminar Ítem?',
        message: '¿Estás seguro de que deseas eliminar este ítem? Esta acción no se puede deshacer.'
    }"

    @open-delete-modal.window="
        show = true;
        action = $event.detail.action;
        params = $event.detail.params ?? null;
        title = $event.detail.title ?? title;
        message = $event.detail.message ?? message;
    "

    x-show="show"
    style="display:none"
    class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
    x-transition
    @keydown.escape.window="show = false"
>

    <div
        @click.away="show = false"
        class="bg-white border-2 border-black shadow-[8px_8px_0px_#000] w-full max-w-md p-6 relative"
        x-transition
    >

        <button
            type="button"
            @click="show=false"
            class="absolute top-4 right-4 text-2xl font-black hover:text-red-600"
        >
            &times;
        </button>

        <h2
            class="text-2xl font-black uppercase mb-4 font-display text-red-600"
            x-text="title"
        ></h2>

        <p
            class="font-bold text-gray-800 mb-6"
            x-text="message"
        ></p>

        <div class="flex gap-4 justify-end">

            <button
                type="button"
                @click="show=false"
                class="px-4 py-2 bg-white border-2 border-black font-bold uppercase hover:bg-gray-100"
            >
                Cancelar
            </button>

            <button
                type="button"
                @click="
                    Livewire.dispatch(action, { id: params });
                    show = false;
                "
                class="px-6 py-2 bg-red-500 text-white border-2 border-black font-bold uppercase shadow-[4px_4px_0px_#000] hover:translate-y-px hover:translate-x-px hover:shadow-[2px_2px_0px_#000] transition-all"
            >
                Eliminar Permanentemente
            </button>

        </div>

    </div>

</div>
