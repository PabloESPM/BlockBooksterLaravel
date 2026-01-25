@extends('layouts.app')

@section('title', '404 Not Found')

@section('content')
    <div class="flex flex-col items-center justify-center min-h-[60vh] text-center">
        <h1
            class="text-9xl font-black font-display mb-4 text-transparent bg-clip-text bg-gradient-to-r from-brand-blue to-brand-yellow drop-shadow-[4px_4px_0px_#000]">
            404
        </h1>
        <h2 class="text-3xl font-black uppercase mb-6">Page Not Found</h2>
        <p class="text-xl max-w-lg mb-10 font-bold text-gray-600">
            The page you are looking for might have been removed, had its name changed, or is temporarily unavailable. Or
            maybe it never existed.
        </p>

        <div class="flex gap-4">
            <a href="/" class="neo-btn-primary px-8 py-4 text-lg">Go Home</a>
            <a href="{{ url()->previous() }}" class="neo-btn-secondary px-8 py-4 text-lg">Go Back</a>
        </div>
    </div>
@endsection