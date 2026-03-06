@extends('layouts.app')

@section('title', 'Sobre Nosotros')

@section('content')
    <!-- Sección Misión -->
    <section class="text-center mb-20">
        <h1 class="text-5xl md:text-7xl font-black uppercase font-display mb-8">
            Amamos los <br><span class="bg-black text-white px-2">Datos</span> y los Libros.
        </h1>
        <p class="max-w-2xl mx-auto text-xl font-bold text-gray-700 leading-relaxed">
            BlockBookster nació de la frustración con sitios de seguimiento de libros saturados y llenos de anuncios.
            Creemos en la simplicidad radical, los datos puros y una comunidad que se preocupa más por la historia que por el estatus.
        </p>
    </section>

    <!-- Sección Estadísticas -->
    <section class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-20">
        <div class="text-center border-r-2 border-black last:border-0">
            <div class="text-4xl font-black text-brand-blue">24k+</div>
            <div class="text-xs font-bold uppercase">Libros registrados</div>
        </div>
        <div class="text-center border-r-2 border-black last:border-0">
            <div class="text-4xl font-black text-brand-yellow">12k+</div>
            <div class="text-xs font-bold uppercase">Usuarios activos</div>
        </div>
        <div class="text-center border-r-2 border-black last:border-0">
            <div class="text-4xl font-black">85k+</div>
            <div class="text-xs font-bold uppercase">Reseñas</div>
        </div>
        <div class="text-center">
            <div class="text-4xl font-black text-gray-400">0</div>
            <div class="text-xs font-bold uppercase">Anuncios mostrados</div>
        </div>
    </section>

    <!-- Sección Equipo -->
    <section>
        <h2
            class="text-3xl font-black uppercase font-display mb-8 text-center border-b-4 border-black pb-4 inline-block mx-auto">
            El Equipo
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Miembro 1 -->
            <x-card class="text-center group hover:-translate-y-2 transition-transform">
                <div class="w-32 h-32 mx-auto bg-gray-200 rounded-full border-4 border-black mb-4 overflow-hidden">
                    <img src="https://ui-avatars.com/api/?name=Alex+Founder&background=random"
                         class="w-full h-full object-cover">
                </div>
                <h3 class="font-black uppercase text-xl">Alex Founder</h3>
                <p class="text-sm font-bold text-brand-blue uppercase mb-2">CEO y Desarrollador Principal</p>
                <p class="text-sm text-gray-600">
                    "Solo quería un lugar donde listar mi colección de ciencia ficción sin ruido."
                </p>
            </x-card>

            <!-- Miembro 2 -->
            <x-card class="text-center group hover:-translate-y-2 transition-transform">
                <div class="w-32 h-32 mx-auto bg-gray-200 rounded-full border-4 border-black mb-4 overflow-hidden">
                    <img src="https://ui-avatars.com/api/?name=Sarah+Design&background=random"
                         class="w-full h-full object-cover">
                </div>
                <h3 class="font-black uppercase text-xl">Sarah Design</h3>
                <p class="text-sm font-bold text-brand-yellow uppercase mb-2 text-shadow-neo">
                    Directora de Producto
                </p>
                <p class="text-sm text-gray-600">
                    "El brutalismo no es solo una estética, es una filosofía de honestidad."
                </p>
            </x-card>

            <!-- Miembro 3 -->
            <x-card class="text-center group hover:-translate-y-2 transition-transform">
                <div class="w-32 h-32 mx-auto bg-gray-200 rounded-full border-4 border-black mb-4 overflow-hidden">
                    <img src="https://ui-avatars.com/api/?name=Mike+Ops&background=random"
                         class="w-full h-full object-cover">
                </div>
                <h3 class="font-black uppercase text-xl">Mike Ops</h3>
                <p class="text-sm font-bold text-gray-500 uppercase mb-2">Responsable de Comunidad</p>
                <p class="text-sm text-gray-600">
                    "Manteniendo a los trolls bajo el puente y las conversaciones en un tono civilizado."
                </p>
            </x-card>
        </div>
    </section>
@endsection
