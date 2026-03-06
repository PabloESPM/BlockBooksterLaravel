@extends('layouts.admin')

@section('title', 'Panel de Control')

@section('content')
    <h1 class="text-4xl font-black uppercase font-display mb-8">Panel de Control</h1>

    <!-- Fila de Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        <div class="bg-white border-2 border-black p-6 shadow-[4px_4px_0px_#000]">
            <div class="text-xs font-bold uppercase text-gray-500 mb-1">Total de Libros</div>
            <div class="text-4xl font-black">1,248</div>
            <div class="text-xs font-bold text-green-600 mt-2">↑ 12 esta semana</div>
        </div>
        <div class="bg-white border-2 border-black p-6 shadow-[4px_4px_0px_#000]">
            <div class="text-xs font-bold uppercase text-gray-500 mb-1">Total de Usuarios</div>
            <div class="text-4xl font-black">5,892</div>
            <div class="text-xs font-bold text-green-600 mt-2">↑ 45 esta semana</div>
        </div>
        <div class="bg-white border-2 border-black p-6 shadow-[4px_4px_0px_#000]">
            <div class="text-xs font-bold uppercase text-gray-500 mb-1">Reseñas Pendientes</div>
            <div class="text-4xl font-black text-brand-yellow">34</div>
            <div class="text-xs font-bold text-gray-500 mt-2">Requiere moderación</div>
        </div>
        <div class="bg-white border-2 border-black p-6 shadow-[4px_4px_0px_#000]">
            <div class="text-xs font-bold uppercase text-gray-500 mb-1">Listas Reportadas</div>
            <div class="text-4xl font-black text-red-500">2</div>
            <div class="text-xs font-bold text-gray-500 mt-2">Problemas críticos</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Acciones Recientes -->
        <div>
            <h2 class="text-xl font-black uppercase mb-4 border-b-2 border-black pb-2">Eventos Recientes del Sistema</h2>
            <div class="bg-white border-2 border-black p-0">
                <div class="p-4 border-b border-gray-200 hover:bg-gray-50 flex justify-between items-center">
                    <div>
                        <span
                            class="font-bold uppercase text-xs bg-blue-100 text-blue-800 px-2 py-0.5 mr-2 border border-blue-200">Usuario</span>
                        <span class="text-sm font-medium">Nuevo registro de usuario: <strong>kyle_ranner</strong></span>
                    </div>
                    <span class="text-xs text-gray-500 uppercase font-bold">Hace 2 min</span>
                </div>
                <div class="p-4 border-b border-gray-200 hover:bg-gray-50 flex justify-between items-center">
                    <div>
                        <span
                            class="font-bold uppercase text-xs bg-yellow-100 text-yellow-800 px-2 py-0.5 mr-2 border border-yellow-200">Reseña</span>
                        <span class="text-sm font-medium">Nueva reseña en <strong>Dune</strong></span>
                    </div>
                    <span class="text-xs text-gray-500 uppercase font-bold">Hace 15 min</span>
                </div>
                <div class="p-4 border-b border-gray-200 hover:bg-gray-50 flex justify-between items-center">
                    <div>
                        <span
                            class="font-bold uppercase text-xs bg-red-100 text-red-800 px-2 py-0.5 mr-2 border border-red-200">Alerta</span>
                        <span class="text-sm font-medium">Reporte registrado en la Lista #442</span>
                    </div>
                    <span class="text-xs text-gray-500 uppercase font-bold">Hace 1 h</span>
                </div>
                <div class="p-4 hover:bg-gray-50 flex justify-between items-center">
                    <div>
                        <span
                            class="font-bold uppercase text-xs bg-green-100 text-green-800 px-2 py-0.5 mr-2 border border-green-200">Sistema</span>
                        <span class="text-sm font-medium">Copia de seguridad completada correctamente</span>
                    </div>
                    <span class="text-xs text-gray-500 uppercase font-bold">Hace 3 h</span>
                </div>
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div>
            <h2 class="text-xl font-black uppercase mb-4 border-b-2 border-black pb-2">Acciones Rápidas</h2>
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('admin.books.create') }}"
                   class="flex flex-col items-center justify-center p-6 bg-brand-blue text-white border-2 border-black shadow-[4px_4px_0px_#000] hover:translate-y-[-2px] hover:shadow-[6px_6px_0px_#000] transition-all">
                    <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="font-bold uppercase">Añadir Libro</span>
                </a>
                <a href="#"
                   class="flex flex-col items-center justify-center p-6 bg-white border-2 border-black shadow-[4px_4px_0px_#000] hover:translate-y-[-2px] hover:shadow-[6px_6px_0px_#000] transition-all">
                    <svg class="w-8 h-8 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                    <span class="font-bold uppercase">Añadir Usuario</span>
                </a>
                <a href="{{ route('admin.reviews.moderation') }}"
                   class="flex flex-col items-center justify-center p-6 bg-brand-yellow border-2 border-black shadow-[4px_4px_0px_#000] hover:translate-y-[-2px] hover:shadow-[6px_6px_0px_#000] transition-all">
                    <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-bold uppercase">Moderar</span>
                </a>
                <a href="#"
                   class="flex flex-col items-center justify-center p-6 bg-white border-2 border-black shadow-[4px_4px_0px_#000] hover:translate-y-[-2px] hover:shadow-[6px_6px_0px_#000] transition-all">
                    <svg class="w-8 h-8 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="font-bold uppercase">Configuración</span>
                </a>
            </div>
        </div>
    </div>
@endsection
