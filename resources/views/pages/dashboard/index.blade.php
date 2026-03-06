@extends('layouts.app')

@section('title', 'Mi Panel')

@section('content')
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Barra lateral -->
        @include('pages.dashboard.partials.sidebar')

        <!-- Contenido Principal -->
        <div class="flex-1 space-y-8">
            <!-- Encabezado -->
            <header class="flex flex-col md:flex-row justify-between items-start md:items-end border-b-4 border-black pb-6">
                <div>
                    <h1 class="text-4xl font-black uppercase font-display">Hola, <span
                            class="text-brand-blue">{{ auth()->user()->name ?? 'Lector' }}</span></h1>
                    <p class="text-gray-600 font-bold mt-2">Esto es lo que está pasando con tus libros.</p>
                </div>
                <a href="{{ route('books.index') }}" class="hidden md:inline-block neo-btn-primary text-sm">
                    + Registrar nuevo libro
                </a>
            </header>

            <!-- Cuadrícula de estadísticas -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <x-card class="text-center py-6 bg-brand-yellow/10">
                    <div class="text-4xl font-black">{{ $readBooksCount }}</div>
                    <div class="text-xs font-bold uppercase text-gray-600">Libros leídos</div>
                </x-card>
                <x-card class="text-center py-6">
                    <div class="text-4xl font-black">{{ $readingBooksCount }}</div>
                    <div class="text-xs font-bold uppercase text-gray-600">Leyendo actualmente</div>
                </x-card>
                <x-card class="text-center py-6">
                    <div class="text-4xl font-black">{{ $listsCount }}</div>
                    <div class="text-xs font-bold uppercase text-gray-600">Listas creadas</div>
                </x-card>
                <x-card class="text-center py-6">
                    <div class="text-4xl font-black">{{ $reviewsCount }}</div>
                    <div class="text-xs font-bold uppercase text-gray-600">Reseñas</div>
                </x-card>
            </div>

            <!-- Leyendo actualmente -->
            <section>
                <h2 class="text-xl font-black uppercase mb-4 flex items-center gap-2">
                    <span class="w-3 h-3 bg-brand-yellow border border-black"></span>
                    Leyendo actualmente
                </h2>
                <x-card class="flex flex-col md:flex-row gap-6 items-center md:items-start">
                    <div class="w-24 flex-shrink-0 border-2 border-black shadow-[4px_4px_0px_#000]">
                        <img src="https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&q=80&w=300"
                            alt="Portada" class="w-full h-auto">
                    </div>
                    <div class="flex-1 w-full">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h3 class="font-bold text-lg uppercase leading-tight">Project Hail Mary</h3>
                                <p class="text-sm text-gray-600">por Andy Weir</p>
                            </div>
                            <span class="text-xs font-black bg-brand-yellow px-2 py-1 border border-black">74%</span>
                        </div>

                        <!-- Barra de progreso -->
                        <div class="w-full h-4 bg-gray-200 border-2 border-black mb-4 relative">
                            <div class="absolute top-0 left-0 h-full bg-brand-blue w-[74%]"></div>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-xs font-bold text-gray-500 uppercase">Página 366 de 496</span>
                            <button class="neo-btn-secondary py-1 px-3 text-xs">Actualizar progreso</button>
                        </div>
                    </div>
                </x-card>
            </section>

            <!-- Actividad reciente -->
            <section>
                <h2 class="text-xl font-black uppercase mb-4 flex items-center gap-2">
                    <span class="w-3 h-3 bg-black border border-black"></span>
                    Actividad reciente
                </h2>
                <div class="space-y-4">
                    <x-card class="flex items-center gap-4 py-4">
                        <div
                            class="w-10 h-10 bg-green-100 rounded-full border-2 border-black flex items-center justify-center shrink-0">
                            <span class="text-xl">📚</span>
                        </div>
                        <div>
                            <p class="text-sm font-bold">Terminaste de leer <span class="text-brand-blue">Dune</span></p>
                            <p class="text-xs text-gray-500 uppercase">Hace 2 días</p>
                        </div>
                    </x-card>
                    <x-card class="flex items-center gap-4 py-4">
                        <div
                            class="w-10 h-10 bg-brand-yellow/20 rounded-full border-2 border-black flex items-center justify-center shrink-0">
                            <span class="text-xl">⭐</span>
                        </div>
                        <div>
                            <p class="text-sm font-bold">Valoraste <span class="text-brand-blue">1984</span> con 5 estrellas
                            </p>
                            <p class="text-xs text-gray-500 uppercase">Hace 1 semana</p>
                        </div>
                    </x-card>
                </div>
            </section>
        </div>
    </div>
@endsection