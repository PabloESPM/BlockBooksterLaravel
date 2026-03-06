@extends('layouts.admin')

@section('title', 'Editar Libro')

@section('content')
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.books.index') }}"
           class="w-10 h-10 border-2 border-black flex items-center justify-center hover:bg-black hover:text-white transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
            </svg>
        </a>
        <h1 class="text-3xl font-black uppercase font-display">Editar Libro</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Formulario Principal -->
        <div class="lg:col-span-2 space-y-8">
            <x-card>
                <h3 class="font-black text-lg uppercase mb-6 border-b-2 border-black pb-2">Detalles del Libro</h3>
                <form class="space-y-6">
                    <div>
                        <label class="block text-xs font-bold uppercase mb-2">Título</label>
                        <input type="text" class="neo-input w-full" value="Project Hail Mary">
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold uppercase mb-2">Autor</label>
                            <select class="neo-input w-full">
                                <option>Andy Weir</option>
                                <option>Seleccionar Autor...</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase mb-2">Género</label>
                            <select class="neo-input w-full">
                                <option>Ciencia Ficción</option>
                                <option>Seleccionar Género...</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-6">
                        <div>
                            <label class="block text-xs font-bold uppercase mb-2">ISBN</label>
                            <input type="text" class="neo-input w-full" value="978-0593135204">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase mb-2">Año de Publicación</label>
                            <input type="number" class="neo-input w-full" value="2021">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase mb-2">Páginas</label>
                            <input type="number" class="neo-input w-full" value="496">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase mb-2">Idioma</label>
                        <select class="neo-input w-full">
                            <option>Inglés</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase mb-2">Descripción</label>
                        <textarea rows="6"
                                  class="neo-input w-full">Ryland Grace es el único superviviente en una desesperada misión de última oportunidad...</textarea>
                    </div>
                </form>
            </x-card>

            <x-card>
                <h3 class="font-black text-lg uppercase mb-6 border-b-2 border-black pb-2">Enlaces de Afiliado</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase mb-2">URL de Amazon</label>
                        <input type="url" class="neo-input w-full" placeholder="https://amazon.com/...">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase mb-2">URL de Bookshop.org</label>
                        <input type="url" class="neo-input w-full" placeholder="https://bookshop.org/...">
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Barra Lateral -->
        <div class="space-y-8">
            <!-- Imagen de Portada -->
            <x-card class="bg-gray-100">
                <h3 class="font-black text-sm uppercase mb-4">Imagen de Portada</h3>
                <div
                    class="aspect-[2/3] bg-gray-300 border-2 border-black mb-4 flex items-center justify-center overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&q=80&w=400"
                         class="w-full h-full object-cover">
                </div>
                <input type="file"
                       class="block w-full text-xs font-bold file:mr-4 file:py-2 file:px-4 file:border-2 file:border-black file:text-black file:bg-white hover:file:bg-black hover:file:text-white file:font-bold file:uppercase cursor-pointer">
            </x-card>

            <!-- Acciones -->
            <div class="space-y-4">
                <button class="w-full neo-btn-primary py-4 text-lg">Guardar Cambios</button>
                <button
                    class="w-full bg-white border-2 border-black py-4 font-black uppercase hover:bg-red-50 hover:text-red-600 hover:border-red-600 transition-colors">Eliminar
                    Libro</button>
            </div>
        </div>
    </div>
@endsection
