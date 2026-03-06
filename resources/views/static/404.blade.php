@extends('layouts.app')

@section('title', '404 No Encontrado')

@section('content')
    <div class="flex flex-col items-center justify-center min-h-[60vh] text-center">
        <h1
            class="text-9xl font-black font-display mb-4 text-transparent bg-clip-text bg-gradient-to-r from-brand-blue to-brand-yellow drop-shadow-[4px_4px_0px_#000]">
            404
        </h1>
        <h2 class="text-3xl font-black uppercase mb-6">Página no encontrada</h2>
        <p class="text-xl max-w-lg mb-10 font-bold text-gray-600">
            La página que estás buscando puede haber sido eliminada, haber cambiado de nombre o no estar disponible temporalmente.
            O quizás nunca existió.
        </p>

        <div class="flex gap-4">
            <a href="/" class="neo-btn-primary px-8 py-4 text-lg">Ir al inicio</a>
            <a href="{{ url()->previous() }}" class="neo-btn-secondary px-8 py-4 text-lg">Volver atrás</a>
        </div>
    </div>
@endsection
