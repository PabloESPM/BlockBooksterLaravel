<div x-data="{
        show: false,
        title: 'Compartir',
        url: ''
    }" @open-share-modal.window="
        show = true;
        title = $event.detail.title || 'Compartir';
        url = $event.detail.url || window.location.href;
    " x-show="show" style="display: none;"
     class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 text-left"
     x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @keydown.escape.window="show = false">

    <div @click.away="show = false"
         class="bg-white border-2 border-black shadow-[8px_8px_0px_#000] w-full max-w-sm p-6 relative"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="scale-95 opacity-0 translate-y-4"
         x-transition:enter-end="scale-100 opacity-100 translate-y-0">

        <button @click="show = false"
                class="absolute top-4 right-4 text-2xl font-black hover:text-red-600">&times;</button>

        <h2 class="text-2xl font-black uppercase mb-4 font-display" x-text="title"></h2>

        <p class="font-bold text-sm text-gray-600 mb-4">Copia el enlace de abajo para compartirlo con tus amigos:</p>

        <div class="flex flex-col gap-3">
            <div class="relative">
                <input type="text" readonly :value="url" id="shareUrlInput"
                       class="w-full border-2 border-black p-2 bg-gray-100 text-sm font-bold text-gray-700 focus:outline-none">
            </div>
            <button
                @click="navigator.clipboard.writeText(url); $el.innerText = '¡Copiado!'; setTimeout(() => $el.innerText = 'Copiar enlace', 2000)"
                class="neo-btn-primary w-full text-center py-2 text-sm">
                Copiar enlace
            </button>
        </div>
    </div>
</div>
