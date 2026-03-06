@extends('layouts.admin')

@section('title', 'Gestionar Libros')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-4xl font-black uppercase font-display">Libros</h1>
        <a href="{{ route('admin.books.create') }}" class="neo-btn-primary px-6 py-2 text-sm flex items-center gap-2">
            <span>+ Añadir Nuevo Libro</span>
        </a>
    </div>

    <!-- Filtros -->
    <div class="bg-white border-2 border-black p-4 mb-8 flex gap-4">
        <input type="text" placeholder="Buscar por título, ISBN..." class="neo-input flex-1 bg-white">
        <select class="neo-input w-48 bg-white">
            <option>Todos los Géneros</option>
            <option>Ciencia Ficción</option>
        </select>
        <button class="neo-btn-secondary px-6">Filtrar</button>
    </div>

    <!-- Tabla -->
    <div class="bg-white border-2 border-black overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
            <tr class="bg-black text-white text-xs font-bold uppercase tracking-wider">
                <th class="p-4 border-b border-gray-800">Portada</th>
                <th class="p-4 border-b border-gray-800">Título / ISBN</th>
                <th class="p-4 border-b border-gray-800">Autor</th>
                <th class="p-4 border-b border-gray-800">Género</th>
                <th class="p-4 border-b border-gray-800">Valoración</th>
                <th class="p-4 border-b border-gray-800 text-right">Acciones</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-black/10">
            @for($i = 0; $i < 5; $i++)
                <tr class="hover:bg-gray-50 group">
                    <td class="p-4">
                        <div class="w-10 h-14 bg-gray-200 border border-black overflow-hidden">
                            <!-- Marcador de posición -->
                        </div>
                    </td>
                    <td class="p-4">
                        <div class="font-bold leading-tight">Título del Libro {{ $i }}</div>
                        <div class="text-xs text-gray-500 font-mono">978-3-16-148410-{{ $i }}</div>
                    </td>
                    <td class="p-4 font-medium text-sm">Nombre del Autor</td>
                    <td class="p-4">
                            <span
                                class="text-xs font-bold uppercase bg-brand-yellow/20 px-2 py-1 border border-black/20">Ciencia Ficción</span>
                    </td>
                    <td class="p-4 font-bold text-sm">4.{{ $i }} ★</td>
                    <td class="p-4 text-right">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.books.edit', ['id' => $i]) }}"
                               class="text-xs font-black uppercase text-brand-blue hover:underline">Editar</a>
                            <button class="text-xs font-black uppercase text-red-600 hover:underline">Eliminar</button>
                        </div>
                    </td>
                </tr>
            @endfor
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div class="mt-6 flex justify-center gap-2">
        <button class="w-8 h-8 border-2 border-black bg-white hover:bg-black hover:text-white transition-colors font-bold">
        </button>
        <button class="w-8 h-8 border-2 border-black bg-brand-blue text-white font-bold">1</button>
        <button
            class="w-8 h-8 border-2 border-black bg-white hover:bg-black hover:text-white transition-colors font-bold">2</button>
        <button
            class="w-8 h-8 border-2 border-black bg-white hover:bg-black hover:text-white transition-colors font-bold">></button>
    </div>
@endsection
