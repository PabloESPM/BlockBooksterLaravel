@extends('layouts.app')

@section('title', 'Configuración de Cuenta')

@section('content')
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Barra Lateral -->
        @include('pages.dashboard.partials.sidebar')

        <!-- Contenido Principal -->
        <div class="flex-1">
            <header class="mb-8 border-b-4 border-black pb-4">
                <h1 class="text-3xl font-black uppercase font-display">Configuración de Cuenta</h1>
                <p class="text-gray-600 font-bold mt-1">Preferencias de seguridad y privacidad</p>
            </header>

            <div class="space-y-8">
                <!-- Email y Contraseña -->
                <x-card>
                    <h3 class="font-black text-lg uppercase mb-6 flex items-center gap-2">
                        <span class="w-3 h-3 bg-brand-blue border border-black"></span>
                        Inicio de Sesión y Seguridad
                    </h3>

                    {{-- Mensaje de éxito tras actualizar credenciales --}}
                    @if(session('success'))
                        <div class="mb-6 p-4 bg-green-100 border-2 border-green-600 text-green-700 font-bold uppercase text-sm">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('dashboard.settings.update') }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        {{-- Campo: Correo Electrónico --}}
                        <div>
                            <label class="block text-xs font-bold uppercase mb-2">Correo Electrónico</label>
                            <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                                class="neo-input w-full">
                            @error('email')
                                <p class="text-xs text-red-600 font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Campo: Número de Teléfono --}}
                        <div>
                            <label class="block text-xs font-bold uppercase mb-2">Número de Teléfono</label>
                            <input type="tel" name="telephone" value="{{ old('telephone', auth()->user()->telephone) }}"
                                placeholder="Ej. +34 600 000 000" class="neo-input w-full">
                            @error('telephone')
                                <p class="text-xs text-red-600 font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Campos: Nueva Contraseña (opcional) --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-gray-200">
                            <div>
                                <label class="block text-xs font-bold uppercase mb-2">Nueva Contraseña</label>
                                <input type="password" name="password" class="neo-input w-full"
                                    placeholder="Dejar en blanco para no cambiarla">
                                @error('password')
                                    <p class="text-xs text-red-600 font-bold mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase mb-2">Confirmar Contraseña</label>
                                <input type="password" name="password_confirmation" class="neo-input w-full"
                                    placeholder="Repite la nueva contraseña">
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="neo-btn-secondary px-6">Actualizar Credenciales</button>
                        </div>
                    </form>
                </x-card>

                <!-- Privacidad del Perfil -->
                <x-card>
                    <h3 class="font-black text-lg uppercase mb-6 flex items-center gap-2">
                        <span class="w-3 h-3 bg-brand-yellow border border-black"></span>
                        Privacidad del Perfil
                    </h3>

                    {{-- Mensaje de éxito --}}
                    @if(session('privacy_success'))
                        <div class="mb-6 p-4 bg-green-100 border-2 border-green-600 text-green-700 font-bold uppercase text-sm">
                            {{ session('privacy_success') }}
                        </div>
                    @endif

                    {{-- Formulario de visibilidad del perfil --}}
                    <form action="{{ route('dashboard.settings.privacy') }}" method="POST" class="space-y-3">
                        @csrf
                        @method('PUT')

                        {{-- Opción: Público (cualquier visitante puede ver el perfil) --}}
                        <label class="flex items-start gap-4 cursor-pointer p-4 border-2 {{ auth()->user()->profile_visibility === 'public' ? 'border-black bg-brand-yellow/10' : 'border-gray-200' }} hover:border-black transition-colors">
                            <input type="radio" name="profile_visibility" value="public"
                                {{ auth()->user()->profile_visibility === 'public' ? 'checked' : '' }}
                                class="mt-1 w-4 h-4 accent-black">
                            <div>
                                <div class="font-bold uppercase text-sm">🌐 Público</div>
                                <div class="text-xs text-gray-500 mt-0.5">Cualquier visitante puede ver tus listas, reseñas y actividad de lectura.</div>
                            </div>
                        </label>

                        {{-- Opción: Seguidores (solo los usuarios que te siguen) --}}
                        <label class="flex items-start gap-4 cursor-pointer p-4 border-2 {{ auth()->user()->profile_visibility === 'followers' ? 'border-black bg-brand-yellow/10' : 'border-gray-200' }} hover:border-black transition-colors">
                            <input type="radio" name="profile_visibility" value="followers"
                                {{ auth()->user()->profile_visibility === 'followers' ? 'checked' : '' }}
                                class="mt-1 w-4 h-4 accent-black">
                            <div>
                                <div class="font-bold uppercase text-sm">👥 Solo Seguidores</div>
                                <div class="text-xs text-gray-500 mt-0.5">Únicamente los usuarios que te siguen pueden ver tu actividad completa.</div>
                            </div>
                        </label>

                        {{-- Opción: Amigos (seguimiento mutuo) --}}
                        <label class="flex items-start gap-4 cursor-pointer p-4 border-2 {{ auth()->user()->profile_visibility === 'friends' ? 'border-black bg-brand-yellow/10' : 'border-gray-200' }} hover:border-black transition-colors">
                            <input type="radio" name="profile_visibility" value="friends"
                                {{ auth()->user()->profile_visibility === 'friends' ? 'checked' : '' }}
                                class="mt-1 w-4 h-4 accent-black">
                            <div>
                                <div class="font-bold uppercase text-sm">🤝 Solo Amigos</div>
                                <div class="text-xs text-gray-500 mt-0.5">Solo los usuarios con los que te sigues mutuamente pueden ver tu perfil.</div>
                            </div>
                        </label>

                        {{-- Opción: Privado (nadie excepto tú mismo) --}}
                        <label class="flex items-start gap-4 cursor-pointer p-4 border-2 {{ auth()->user()->profile_visibility === 'private' ? 'border-black bg-brand-yellow/10' : 'border-gray-200' }} hover:border-black transition-colors">
                            <input type="radio" name="profile_visibility" value="private"
                                {{ auth()->user()->profile_visibility === 'private' ? 'checked' : '' }}
                                class="mt-1 w-4 h-4 accent-black">
                            <div>
                                <div class="font-bold uppercase text-sm">🔒 Privado</div>
                                <div class="text-xs text-gray-500 mt-0.5">Nadie puede ver tu actividad ni tus secciones de perfil, solo el encabezado con tu nombre.</div>
                            </div>
                        </label>

                        <div class="flex justify-end mt-4">
                            <button type="submit" class="neo-btn-secondary px-6">Guardar Preferencias</button>
                        </div>
                    </form>
                </x-card>

                <!-- Zona de Peligro -->
                <div class="border-2 border-red-600 p-6 bg-red-50 shadow-[4px_4px_0px_#dc2626]">
                    <h3 class="font-black text-lg uppercase mb-4 text-red-600">Zona de Peligro</h3>
                    <p class="text-sm font-bold text-gray-800 mb-6">Una vez que elimines tu cuenta, no habrá vuelta atrás.
                        Por favor, asegúrate de tu decisión.</p>
                    <div class="flex justify-end">
                        <button x-data="" @click.prevent="$dispatch('open-delete-modal', {
                                        deleteUrl: '{{ route('dashboard.settings.destroy') }}',
                                        title: 'Eliminar Cuenta',
                                        message: '¿Estás seguro de que deseas eliminar tu cuenta permanentemente? Perderás todas tus listas, libros, reseñas y seguidores. Esta acción no se puede deshacer.'
                                    })"
                            class="bg-red-600 text-white font-black uppercase px-6 py-2 border-2 border-black shadow-[2px_2px_0px_#000] hover:translate-y-[-1px] hover:shadow-[4px_4px_0px_#000] transition-all">
                            Eliminar Cuenta
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación de eliminación genérico -->
    <x-modals.delete-modal />
@endsection