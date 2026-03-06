@props([
    'user',
    'readBooksCount',
    'readingBooksCount'
])

<x-card class="mb-8">
    <div class="flex flex-col md:flex-row gap-6 items-center md:items-start">
        {{-- Avatar --}}
        <div class="w-32 h-32 bg-gray-200 rounded-full border-2 border-black flex-shrink-0 overflow-hidden">
            <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random' }}"
                alt="{{ $user->name }}"
                class="w-full h-full object-cover">
        </div>

        {{-- info usuario --}}
        <div class="flex-1 text-center md:text-left">
            <h1 class="text-4xl font-black uppercase font-display mb-2">{{ $user->name }}</h1>
            @if($user->country)
                <p class="text-sm font-bold text-gray-600 uppercase mb-4">
                    📍 {{ $user->country->name }}
                </p>
            @endif

            {{-- Biografía del usuario (solo se muestra si tiene contenido) --}}
            @if($user->bio)
                <p class="text-sm text-gray-700 mb-4 max-w-prose">{{ $user->bio }}</p>
            @endif
            {{-- Informacion usuario --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                <div class="text-center">
                    <div class="text-3xl font-black">{{ $readBooksCount }}</div>
                    <div class="text-xs font-bold uppercase text-gray-600">Libros Leídos</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-black">{{ $readingBooksCount }}</div>
                    <div class="text-xs font-bold uppercase text-gray-600">Leyendo</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-black">{{ $user->lists->count() }}</div>
                    <div class="text-xs font-bold uppercase text-gray-600">Listas</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-black">{{ $user->reviews->count() }}</div>
                    <div class="text-xs font-bold uppercase text-gray-600">Reseñas</div>
                </div>
            </div>
        </div>

        {{-- Boton Follow --}}
        @auth
            @if(auth()->id() !== $user->id)
                <div class="flex-shrink-0">
                    <x-modals.follow-modal
                        :followableId="$user->id"
                        followableType="user"
                        :isFollowing="auth()->user()->isFollowing($user)"
                        :followUrl="route('users.follow', $user)"
                    />
                </div>
            @endif
        @endauth
    </div>
</x-card>
