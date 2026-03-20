{{--
Modal de carga de foto de autor — Livewire 4.1
─────────────────────────────────────────────
Componente independiente para encargarse únicamente de subir temporalmente
la foto del autor y pasarla al componente form principal.
Inspirado en cover-upload-modal pero apuntando a la entidad Author.
--}}

<div x-data="{
        show: false,
        dragging: false,
        uploading: false,
        progress: 0,
        previewUrl: null,
        hasFile: false,
        uploadError: null,

        openModal() {
            this.show = true;
            this.previewUrl = null;
            this.hasFile = false;
            this.uploading = false;
            this.progress = 0;
            this.uploadError = null;
        },

        handleFile(file) {
            if (!file || !file.type.startsWith('image/')) {
                this.uploadError = 'El archivo seleccionado no es una imagen válida.';
                return;
            }
            if (file.size > 4 * 1024 * 1024) {
                this.uploadError = 'La imagen no debe superar los 4 MB.';
                return;
            }

            this.uploadError = null;

            // Previsualización instantánea desde el navegador (sin servidor)
            const reader = new FileReader();
            reader.onload = (e) => { this.previewUrl = e.target.result; };
            reader.readAsDataURL(file);

            // Subida temporal al servidor vía Livewire
            this.uploading = true;
            this.progress = 0;
            this.hasFile = false;

            $wire.upload(
                'photo_upload',
                file,
                (uploadedFilename) => {
                    // Éxito: el archivo temporal ya está en el servidor
                    this.uploading = false;
                    this.progress = 100;
                    this.hasFile = true;
                    console.log('[AutorFoto] Archivo temporal subido:', uploadedFilename);
                },
                () => {
                    // Error en la subida
                    this.uploading = false;
                    this.hasFile = false;
                    this.uploadError = 'Error al subir la imagen. Inténtalo de nuevo.';
                    console.error('[AutorFoto] Error en la subida');
                },
                (event) => {
                    // Progreso de subida (0-100)
                    if (event && event.detail && typeof event.detail.progress !== 'undefined') {
                        this.progress = event.detail.progress;
                    }
                },
                () => {
                    // Cancelado
                    this.uploading = false;
                    this.hasFile = false;
                }
            );
        },

        async guardarFoto() {
            try {
                // Llamamos al método savePhoto del componente padre (SFC principal)
                await $wire.savePhoto();
                console.log('[AutorFoto] Foto guardada exitosamente');
            } catch (e) {
                console.error('[AutorFoto] Error al guardar:', e);
                this.uploadError = 'Error al guardar la foto. Revisa los datos del autor.';
            }
        }
    }"
    @open-photo-upload.window="openModal()"
    @photo-saved.window="show = false; previewUrl = null; hasFile = false;"
    @keydown.escape.window="show = false"
    x-show="show"
    x-cloak
    style="display: none;"
    class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0">

    <div @click.away="show = false"
         class="bg-white border-2 border-black shadow-[8px_8px_0px_#000] w-full max-w-lg p-6 relative"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="scale-95 opacity-0 translate-y-4"
         x-transition:enter-end="scale-100 opacity-100 translate-y-0">

        {{-- Cabecera --}}
        <div class="flex justify-between items-center mb-6 border-b-2 border-black pb-4">
            <h2 class="text-xl font-black uppercase font-display">Cargar Foto del Autor</h2>
            <button type="button" @click="show = false" class="text-2xl font-black hover:text-red-600">&times;</button>
        </div>

        {{-- Zona de arrastrar y soltar / click --}}
        <div class="border-2 border-dashed border-black p-8 text-center cursor-pointer transition-all mb-4"
             :class="dragging ? 'bg-brand-yellow/20 border-brand-yellow scale-[1.01]' : 'bg-gray-50 hover:bg-gray-100'"
             @dragover.prevent="dragging = true"
             @dragleave.prevent="dragging = false"
             @drop.prevent="dragging = false; handleFile($event.dataTransfer.files[0])"
             @click="$refs.photoFileInput.click()">

            {{-- Previsualización de la imagen seleccionada --}}
            <template x-if="previewUrl">
                <div>
                    <img :src="previewUrl" class="mx-auto max-h-52 w-52 object-cover rounded-full border-4 border-black mb-3 content-center">
                    <p class="text-sm font-bold text-green-700" x-show="hasFile">✓ Foto lista para guardar</p>
                    <p class="text-sm font-bold text-blue-600" x-show="uploading">Preparando foto...</p>
                </div>
            </template>

            {{-- Estado inicial: sin archivo --}}
            <template x-if="!previewUrl">
                <div>
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="font-bold text-sm uppercase">Arrastra aquí la foto</p>
                    <p class="text-xs text-gray-500 mt-1">o haz clic para seleccionar un archivo</p>
                    <p class="text-xs text-gray-400 mt-2">JPG, PNG, WebP — Máximo 4 MB</p>
                </div>
            </template>

            {{-- Input file oculto — gestionado por $wire.upload() desde Alpine --}}
            <input type="file" x-ref="photoFileInput" accept="image/jpeg,image/png,image/webp" class="hidden"
                   @change="handleFile($event.target.files[0])">
        </div>

        {{-- Barra de progreso de subida --}}
        <div x-show="uploading" x-transition class="mb-4">
            <div class="flex justify-between text-xs font-bold mb-1">
                <span>Subiendo foto...</span>
                <span x-text="Math.round(progress) + '%'"></span>
            </div>
            <div class="w-full bg-gray-200 border border-black h-3">
                <div class="h-full bg-black transition-all" :style="'width:' + progress + '%'"></div>
            </div>
        </div>

        {{-- Mensaje de error --}}
        <template x-if="uploadError">
            <p class="text-xs font-bold text-red-600 mb-4 border border-red-400 bg-red-50 p-2"
               x-text="uploadError"></p>
        </template>

        {{-- Errores de validación de Livewire --}}
        @error('photo_upload')
            <p class="text-xs font-bold text-red-600 mb-4 border border-red-400 bg-red-50 p-2">{{ $message }}</p>
        @enderror

        {{-- Botones de acción --}}
        <div class="flex gap-3 justify-end">
            <button type="button" @click="show = false"
                    class="px-4 py-2 bg-white border-2 border-black font-bold uppercase text-sm hover:bg-gray-100 transition-colors">
                Cancelar
            </button>

            <button type="button"
                    @click="guardarFoto()"
                    :disabled="!hasFile || uploading"
                    :class="(!hasFile || uploading) ? 'opacity-40 cursor-not-allowed' : ''"
                    class="px-6 py-2 bg-black text-white border-2 border-black font-bold uppercase text-sm
                           shadow-[4px_4px_0px_#555] hover:translate-y-px hover:shadow-[2px_2px_0px_#555]
                           transition-all disabled:opacity-40 disabled:cursor-not-allowed disabled:shadow-none">
                <span x-show="!uploading">Guardar Foto</span>
                <span x-show="uploading">Subiendo...</span>
            </button>
        </div>
    </div>
</div>
