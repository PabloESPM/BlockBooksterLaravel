@extends('layouts.app')

@section('title', 'Mis Listas')

@section('content')
    <div class="flex flex-col lg:flex-row gap-8" x-data>
        <!-- Barra lateral -->
        @include('pages.dashboard.partials.sidebar')

        <!-- Contenido principal -->
        <div class="flex-1">
            <header class="mb-8 border-b-4 border-black pb-4 flex justify-between items-end">
                <div>
                    <h1 class="text-3xl font-black uppercase font-display">Mis Listas</h1>
                    <p class="text-gray-600 font-bold mt-1">Colecciones que has creado</p>
                </div>
                <button @click="$dispatch('open-add-to-list-modal')"
                    class="neo-btn-primary text-sm flex items-center gap-2">
                    <span>+ Crear nueva lista</span>
                </button>
            </header>

            {{-- Mensaje de éxito --}}
            @if(session('success'))
                <div class="mb-6 p-4 border-2 border-black bg-green-100 font-bold uppercase relative">
                    {{ session('success') }}
                    <button onclick="this.parentElement.remove()" class="absolute top-2 right-2 text-xl">&times;</button>
                </div>
            @endif

            {{-- Mensajes de error --}}
            @if ($errors->any())
                <div class="mb-6 p-4 border-2 border-black bg-red-100 font-bold relative">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button onclick="this.parentElement.remove()" class="absolute top-2 right-2 text-xl">&times;</button>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($lists as $list)
                    <x-list-card :list="$list" :dashboard="true" />
                @empty
                    <div class="col-span-full text-center py-12 border-2 border-dashed border-black bg-gray-50">
                        <p class="font-bold text-gray-500 uppercase">Aún no has creado ninguna lista.</p>
                        <button @click="$dispatch('open-add-to-list-modal')"
                            class="mt-4 text-brand-blue underline font-bold">Crea tu primera
                            lista</button>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Componente modal unificado -->
        <x-modals.add-to-list />

        <!-- Componente modal de eliminación -->
        <x-modals.delete-modal />
    </div>
@endsection
