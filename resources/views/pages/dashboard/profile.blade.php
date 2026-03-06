@extends('layouts.app')

@section('title', 'Editar Perfil')

@section('content')
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Barra lateral -->
        @include('pages.dashboard.partials.sidebar')

        <!-- Contenido principal -->
        <div class="flex-1">
            <header class="mb-8 border-b-4 border-black pb-4">
                <h1 class="text-3xl font-black uppercase font-display">Editar Perfil</h1>
                <p class="text-gray-600 font-bold mt-1">Actualiza tu información personal</p>
            </header>

            <x-card>

                {{-- Mensajes de éxito o error --}}
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-100 border-2 border-green-600 text-green-700 font-bold uppercase text-sm">
                        {{ session('success') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-100 border-2 border-red-600 text-red-700 font-bold uppercase text-sm">
                        Por favor, corrige los errores del formulario.
                    </div>
                @endif

                <form action="{{ route('dashboard.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- Sección Avatar con previsualización en tiempo real (Alpine.js) --}}
                    <div class="flex items-center gap-6 pb-6 border-b-2 border-gray-200"
                         x-data="{
                             preview: '{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name ?? 'U') . '&background=random' }}',
                             triggerInput() { $refs.avatarInput.click(); },
                             handleFile(e) {
                                 const file = e.target.files[0];
                                 if (file) {
                                     const reader = new FileReader();
                                     reader.onload = ev => { this.preview = ev.target.result; };
                                     reader.readAsDataURL(file);
                                 }
                             }
                         }">
                        <div
                            class="w-24 h-24 bg-gray-200 rounded-full border-2 border-black flex-shrink-0 relative overflow-hidden group cursor-pointer"
                            @click="triggerInput()">
                            <!-- Muestra la imagen actual del usuario o la previsualización de la nueva -->
                            <img :src="preview"
                                alt="Avatar de {{ auth()->user()->name }}"
                                class="w-full h-full object-cover">
                            <!-- Overlay visible al pasar el ratón para indicar que la imagen es clickeable -->
                            <div
                                class="absolute inset-0 bg-black/50 hidden group-hover:flex items-center justify-center text-white text-xs font-bold uppercase">
                                Subir
                            </div>
                        </div>
                        <div>
                            <h3 class="font-bold uppercase text-sm mb-1">Foto de Perfil</h3>
                            <p class="text-xs text-gray-500 mb-1">Tamaño recomendado: 500x500px. Máx. 3 MB.</p>
                            @error('avatar')
                                <p class="text-xs text-red-600 font-bold mb-2">{{ $message }}</p>
                            @enderror
                            <!-- Input oculto que recibe el archivo; se activa desde el botón y el overlay -->
                            <input type="file"
                                   name="avatar"
                                   id="avatar"
                                   class="hidden"
                                   accept="image/*"
                                   x-ref="avatarInput"
                                   @change="handleFile($event)">
                            <!-- Botón decorativo que dispara el selector de archivos -->
                            <button type="button" class="neo-btn-secondary py-1 px-3 text-xs" @click="triggerInput()">Cambiar Foto</button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Campo: Nombre de usuario --}}
                        <div>
                            <label class="block text-xs font-bold uppercase mb-2">Nombre Visible</label>
                            <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                                class="neo-input w-full">
                            @error('name')
                                <p class="text-xs text-red-600 font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Campo: País (selector con lista de países de la base de datos) --}}
                        <div>
                            <label class="block text-xs font-bold uppercase mb-2">País</label>
                            <select name="country_id" class="neo-input w-full">
                                <option value="">— Sin especificar —</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}"
                                        {{ old('country_id', auth()->user()->country_id) == $country->id ? 'selected' : '' }}>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('country_id')
                                <p class="text-xs text-red-600 font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Campo: Biografía (texto libre, ocupa las dos columnas) --}}
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold uppercase mb-2">Biografía</label>
                            <textarea name="bio" rows="4" class="neo-input w-full"
                                placeholder="Cuéntanos sobre tus hábitos de lectura...">{{ old('bio', auth()->user()->bio) }}</textarea>
                            @error('bio')
                                <p class="text-xs text-red-600 font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Sección de enlaces sociales (decorativos por ahora) --}}
                    <div class="border-t-2 border-black pt-6 mt-6">
                        <h3 class="font-bold uppercase text-sm mb-4">Enlaces Sociales</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold uppercase mb-2">Sitio Web</label>
                                <input type="url" name="website" placeholder="https://" class="neo-input w-full">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase mb-2">Twitter / X</label>
                                <input type="text" name="twitter" placeholder="@usuario" class="neo-input w-full">
                            </div>
                        </div>
                    </div>

                    {{-- Botón de guardar --}}
                    <div class="pt-6 flex justify-end">
                        <button type="submit" class="neo-btn-primary px-8">Guardar Cambios</button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
@endsection