@extends('layouts.admin')

@section('title', 'Gestionar Autores')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-4xl font-black uppercase font-display">Autores</h1>
        <button class="neo-btn-primary px-6 py-2 text-sm flex items-center gap-2">
            <span>+ Añadir Nuevo Autor</span>
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @for($i = 0; $i < 8; $i++)
            <x-card class="group">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-16 h-16 bg-gray-200 rounded-full border-2 border-black flex-shrink-0 overflow-hidden">
                        <img src="https://ui-avatars.com/api/?name=Autor+{{$i}}&background=random"
                             class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h3 class="font-bold uppercase leading-tight group-hover:text-brand-blue">Stephen King</h3>
                        <p class="text-xs text-gray-500 font-bold uppercase">65 Libros</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button class="flex-1 neo-btn-secondary py-1 text-xs">Editar</button>
                    <button
                        class="bg-red-50 border-2 border-black px-3 py-1 text-xs font-bold uppercase hover:bg-red-500 hover:text-white transition-colors">Eliminar</button>
                </div>
            </x-card>
        @endfor
    </div>

    <!-- Paginación -->
    <div class="mt-6 flex justify-center gap-2">
        <button class="w-8 h-8 border-2 border-black bg-white hover:bg-black hover:text-white transition-colors font-bold">
            << </button>
        <button class="w-8 h-8 border-2 border-black bg-brand-blue text-white font-bold">1</button>
        <button
            class="w-8 h-8 border-2 border-black bg-white hover:bg-black hover:text-white transition-colors font-bold">></button>
    </div>
@endsection
