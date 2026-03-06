@extends('layouts.app')

@section('title', 'Perfil de ' . $user->name)

@section('content')
    <div class="max-w-7xl mx-auto">
        {{-- Sección de Encabezado --}}
        <x-user-profile-header
            :user="$user"
            :readBooksCount="$readBooks->total()"
            :readingBooksCount="$readingBooks->total()"
        />

        {{-- Las secciones de contenido solo se muestran si el visitante tiene permiso según la configuración de privacidad --}}
        @if($canViewContent)

            {{-- Sección de Actividad de Lectura --}}
            <section class="mb-8">
                <h2 class="text-2xl font-black uppercase mb-4 flex items-center gap-2">
                    <span class="w-3 h-3 bg-brand-blue border border-black"></span>
                    Actividad de Lectura
                </h2>

                {{-- Pestañas --}}
                <div class="flex gap-2 mb-6 border-b-2 border-black pb-2">
                    <button class="tab-btn active" data-tab="read">
                        Leídos ({{ $readBooks->count() }})
                    </button>
                    <button class="tab-btn" data-tab="reading">
                        Leyendo ({{ $readingBooks->count() }})
                    </button>
                    <button class="tab-btn" data-tab="pending">
                        Quiero Leer ({{ $pendingBooks->count() }})
                    </button>
                </div>

                {{-- Libros Leídos --}}
                <div class="tab-content active" id="read">
                    @if($readBooks->isEmpty())
                        <x-card class="text-center py-12 text-gray-500">
                            <p class="font-bold uppercase text-sm">Aún no ha leído ningún libro</p>
                        </x-card>
                    @else
                        <div id="read-books-grid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                            @foreach($readBooks as $bookUser)
                                <x-book-card
                                    :title="$bookUser->book->title"
                                    :author="$bookUser->book->authors->pluck('name')->join(', ')"
                                    :cover="$bookUser->book->cover_image"
                                    :rating="$bookUser->rating ?? 0"
                                    :id="$bookUser->book->isbn"
                                />
                            @endforeach
                        </div>
                        @if($readBooks->hasMorePages())
                            <x-modals.load-more :url="route('users.load-books', [$user, 'read'])" target="read-books-grid" />
                        @endif
                    @endif
                </div>

                {{-- Libros Leyendo --}}
                <div class="tab-content hidden" id="reading">
                    @if($readingBooks->isEmpty())
                        <x-card class="text-center py-12 text-gray-500">
                            <p class="font-bold uppercase text-sm">Actualmente no está leyendo nada</p>
                        </x-card>
                    @else
                        <div id="reading-books-grid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                            @foreach($readingBooks as $bookUser)
                                <x-book-card
                                    :title="$bookUser->book->title"
                                    :author="$bookUser->book->authors->pluck('name')->join(', ')"
                                    :cover="$bookUser->book->cover_image"
                                    :rating="0"
                                    :id="$bookUser->book->isbn"
                                />
                            @endforeach
                        </div>
                        @if($readingBooks->hasMorePages())
                            <x-modals.load-more :url="route('users.load-books', [$user, 'reading'])" target="reading-books-grid" />
                        @endif
                    @endif
                </div>

                {{-- Libros Pendientes --}}
                <div class="tab-content hidden" id="pending">
                    @if($pendingBooks->isEmpty())
                        <x-card class="text-center py-12 text-gray-500">
                            <p class="font-bold uppercase text-sm">No hay libros en la lista de deseos</p>
                        </x-card>
                    @else
                        <div id="pending-books-grid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                            @foreach($pendingBooks as $bookUser)
                                <x-book-card
                                    :title="$bookUser->book->title"
                                    :author="$bookUser->book->authors->pluck('name')->join(', ')"
                                    :cover="$bookUser->book->cover_image"
                                    :rating="0"
                                    :id="$bookUser->book->isbn"
                                />
                            @endforeach
                        </div>
                        @if($pendingBooks->hasMorePages())
                            <x-modals.load-more :url="route('users.load-books', [$user, 'pending'])" target="pending-books-grid" />
                        @endif
                    @endif
                </div>
            </section>

            {{-- Sección de Listas --}}
            @if($listsPaginated->isNotEmpty())
                <section class="mb-8">
                    <h2 class="text-2xl font-black uppercase mb-4 flex items-center gap-2">
                        <span class="w-3 h-3 bg-brand-yellow border border-black"></span>
                        Listas Públicas
                    </h2>

                    <div id="lists-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($listsPaginated as $list)
                            <x-list-card :list="$list" />
                        @endforeach
                    </div>

                    @if($listsPaginated->hasMorePages())
                        <x-modals.load-more :url="route('users.load-lists', $user)" target="lists-grid" />
                    @endif
                </section>
            @endif

            {{-- Sección de Reseñas --}}
            @if($reviews->isNotEmpty())
                <section class="mb-8">
                    <h2 class="text-2xl font-black uppercase mb-4 flex items-center gap-2">
                        <span class="w-3 h-3 bg-brand-pink border border-black"></span>
                        Reseñas
                    </h2>

                    <div id="reviews-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($reviews as $review)
                            <x-review-card :review="$review" :showBook="true" />
                        @endforeach
                    </div>

                    @if($reviews->hasMorePages())
                        <x-modals.load-more :url="route('users.load-reviews', $user)" target="reviews-grid" />
                    @endif
                </section>
            @endif

            {{-- Autores Seguidos --}}
            @if($user->followedAuthors->isNotEmpty())
                <section class="mb-8">
                    <h2 class="text-2xl font-black uppercase mb-4 flex items-center gap-2">
                        <span class="w-3 h-3 bg-brand-blue border border-black"></span>
                        Autores Seguidos
                    </h2>

                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
                        @foreach($user->followedAuthors as $followedAuthor)
                            <x-author-card :author="$followedAuthor" :showFollow="auth()->id() !== $user->id" />
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- Sección Social --}}
            <section class="mb-8">
                <h2 class="text-2xl font-black uppercase mb-4 flex items-center gap-2">
                    <span class="w-3 h-3 bg-black border border-black"></span>
                    Social
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Siguiendo --}}
                    <x-social-stats-card
                        title="Siguiendo"
                        :count="$followingCount"
                        :message="$user->name . ' sigue a ' . $followingCount . ' ' . Str::plural('usuario', $followingCount)"
                        emptyMessage="Aún no sigue a nadie"
                        :userId="$user->id"
                        type="following"
                    />

                    {{-- Seguidores --}}
                    <x-social-stats-card
                        title="Seguidores"
                        :count="$followersCount"
                        :message="$followersCount . ' ' . Str::plural('usuario', $followersCount) . ' sigue a ' . $user->name"
                        emptyMessage="Aún no tiene seguidores"
                        :userId="$user->id"
                        type="followers"
                    />
                </div>
            </section>

        @else
            {{-- Mensaje cuando el perfil tiene contenido restringido --}}
            <x-card class="text-center py-16">
                <div class="text-4xl mb-4">🔒</div>
                <h2 class="text-xl font-black uppercase mb-2">Contenido Restringido</h2>
                <p class="text-gray-500 font-bold text-sm">
                    @if($user->profile_visibility === 'private')
                        Este perfil es privado. Solo el propietario puede ver su contenido.
                    @elseif($user->profile_visibility === 'followers')
                        Este contenido es visible únicamente para los seguidores de {{ $user->name }}.
                    @else
                        Este contenido es visible únicamente para los amigos de {{ $user->name }}.
                    @endif
                </p>
            </x-card>
        @endif
    </div>

    {{-- Script para cambiar pestañas --}}
    <script>
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const tab = this.dataset.tab;

                // Actualizar botones
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                // Actualizar contenido
                document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
                document.getElementById(tab).classList.remove('hidden');
            });
        });
    </script>

    <style>
        .tab-btn {
            @apply px-4 py-2 font-bold uppercase text-sm border-2 border-black bg-white hover:bg-gray-100 transition-colors;
        }
        .tab-btn.active {
            @apply bg-brand-blue text-white;
        }
    </style>
@endsection

