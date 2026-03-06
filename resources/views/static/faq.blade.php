@extends('layouts.app')

@section('title', 'Preguntas Frecuentes')

@section('content')
    <div class="max-w-4xl mx-auto">
        <h1 class="text-4xl font-black uppercase font-display mb-12 text-center">
            Preguntas Frecuentes
        </h1>

        <div x-data="{ active: null }" class="space-y-4">

            <!-- Pregunta 1 -->
            <div class="border-2 border-black bg-white shadow-[4px_4px_0px_#000]">
                <button @click="active = (active === 1 ? null : 1)"
                        class="w-full text-left p-6 font-black uppercase flex justify-between items-center hover:bg-gray-50 transition-colors">
                    <span>¿Es BlockBookster gratuito?</span>
                    <span x-text="active === 1 ? '-' : '+'" class="text-xl"></span>
                </button>
                <div x-show="active === 1" class="p-6 pt-0 text-sm leading-relaxed border-t-2 border-black/10">
                    ¡Sí! BlockBookster es completamente gratuito para todos los usuarios. Puedes llevar el seguimiento
                    de tus lecturas, crear listas y reseñar libros sin pagar nada. Es posible que introduzcamos
                    funcionalidades premium en el futuro, pero la experiencia principal siempre será gratuita.
                </div>
            </div>

            <!-- Pregunta 2 -->
            <div class="border-2 border-black bg-white shadow-[4px_4px_0px_#000]">
                <button @click="active = (active === 2 ? null : 2)"
                        class="w-full text-left p-6 font-black uppercase flex justify-between items-center hover:bg-gray-50 transition-colors">
                    <span>¿Cómo puedo añadir un libro que no aparece?</span>
                    <span x-text="active === 2 ? '-' : '+'" class="text-xl"></span>
                </button>
                <div x-show="active === 2" class="p-6 pt-0 text-sm leading-relaxed border-t-2 border-black/10">
                    Actualmente, solo los administradores pueden añadir nuevos libros a la base de datos para garantizar
                    la calidad de la información. Puedes solicitar la incorporación de un libro a través de nuestra
                    página de Contacto y nuestro equipo lo añadirá en un plazo de 24 a 48 horas.
                </div>
            </div>

            <!-- Pregunta 3 -->
            <div class="border-2 border-black bg-white shadow-[4px_4px_0px_#000]">
                <button @click="active = (active === 3 ? null : 3)"
                        class="w-full text-left p-6 font-black uppercase flex justify-between items-center hover:bg-gray-50 transition-colors">
                    <span>¿Puedo exportar mis datos?</span>
                    <span x-text="active === 3 ? '-' : '+'" class="text-xl"></span>
                </button>
                <div x-show="active === 3" class="p-6 pt-0 text-sm leading-relaxed border-t-2 border-black/10">
                    ¡Estamos trabajando en una función de exportación! Próximamente podrás descargar un archivo CSV con
                    tu biblioteca, reseñas e historial de lectura desde la página de Configuración de tu cuenta.
                </div>
            </div>

            <!-- Pregunta 4 -->
            <div class="border-2 border-black bg-white shadow-[4px_4px_0px_#000]">
                <button @click="active = (active === 4 ? null : 4)"
                        class="w-full text-left p-6 font-black uppercase flex justify-between items-center hover:bg-gray-50 transition-colors">
                    <span>¿Cómo se calcula la puntuación de «Popularidad»?</span>
                    <span x-text="active === 4 ? '-' : '+'" class="text-xl"></span>
                </button>
                <div x-show="active === 4" class="p-6 pt-0 text-sm leading-relaxed border-t-2 border-black/10">
                    La popularidad se basa en una combinación de factores, incluyendo el número de usuarios que están
                    leyendo actualmente el libro, las reseñas recientes y las incorporaciones a listas durante los
                    últimos 30 días.
                </div>
            </div>

        </div>
    </div>
@endsection
