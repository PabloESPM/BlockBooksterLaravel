@extends('layouts.app')

@section('title', 'Contacto')

@section('content')
    <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-12">
        <div>
            <h1 class="text-5xl font-black uppercase font-display mb-6">
                Ponte en <span class="text-brand-blue">Contacto</span>
            </h1>
            <p class="text-xl mb-8 font-bold text-gray-700">
                ¿Tienes alguna pregunta? ¿Has encontrado un error? ¿Solo quieres saludar?
                Nos encantaría saber de ti.
            </p>

            <div class="space-y-6">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 bg-black text-white flex items-center justify-center font-black rounded-full text-xl shadow-[4px_4px_0px_#888]">
                        @</div>
                    <div>
                        <h3 class="font-black uppercase text-sm">Envíanos un correo</h3>
                        <p class="font-mono text-sm">hello@blockbookster.com</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 bg-brand-yellow text-black flex items-center justify-center font-black rounded-full text-xl border-2 border-black shadow-[4px_4px_0px_#000]">
                        X</div>
                    <div>
                        <h3 class="font-black uppercase text-sm">Síguenos</h3>
                        <p class="font-mono text-sm">@BlockBookster</p>
                    </div>
                </div>
            </div>
        </div>

        <x-card class="bg-white">
            <h2 class="font-black text-2xl uppercase mb-6">Enviar un mensaje</h2>
            <form class="space-y-4">
                <div>
                    <label class="block text-xs font-bold uppercase mb-2">Nombre</label>
                    <input type="text" class="neo-input w-full" placeholder="Tu nombre">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase mb-2">Correo electrónico</label>
                    <input type="email" class="neo-input w-full" placeholder="tu@ejemplo.com">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase mb-2">Asunto</label>
                    <select class="neo-input w-full">
                        <option>Consulta general</option>
                        <option>Reporte de error</option>
                        <option>Sugerencia de funcionalidad</option>
                        <option>Solicitud de incorporación de libro</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase mb-2">Mensaje</label>
                    <textarea rows="5" class="neo-input w-full" placeholder="¿Cómo podemos ayudarte?"></textarea>
                </div>
                <button type="submit" class="neo-btn-primary w-full py-3">Enviar mensaje</button>
            </form>
        </x-card>
    </div>
@endsection
