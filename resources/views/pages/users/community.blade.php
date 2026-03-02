@extends('layouts.app')

@section('title', 'Comunidad')

@section('content')
    <div class="mb-12 border-b-4 border-black pb-4">
        <h1 class="text-4xl md:text-6xl font-black font-display uppercase tracking-tighter">Centro de <span
                class="text-brand-yellow text-shadow-neo">Comunidad</span></h1>
        <p class="text-lg font-bold mt-2 text-gray-600 uppercase tracking-widest">Conecta con otros lectores</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <!-- Columna 1: Más Seguidos -->
        <section>
            <div class="bg-black text-white p-4 mb-6 shadow-[4px_4px_0px_#888]">
                <h2 class="text-xl font-black uppercase text-center tracking-widest">Más Seguidos</h2>
            </div>

            <div class="space-y-6">
                @foreach ($mostFollowed as $user)
                    <x-user-card
                        :user="$user"
                        :rank="$loop->iteration"
                        statLabel="Seguidores"
                        :statValue="$user->followers_count"
                        avatarBg="bg-brand-blue"
                    />
                @endforeach
            </div>
        </section>

        <!-- Columna 2: Más Listas Creadas -->
        <section>
            <div class="bg-brand-blue text-white p-4 mb-6 shadow-[4px_4px_0px_#000]">
                <h2 class="text-xl font-black uppercase text-center tracking-widest">Top Curadores</h2>
            </div>

            <div class="space-y-6">
                @foreach ($topCurators as $user)
                    <x-user-card
                        :user="$user"
                        :rank="$loop->iteration"
                        statLabel="Listas Creadas"
                        :statValue="$user->lists_count"
                        avatarBg="bg-brand-yellow"
                    />
                @endforeach
            </div>
        </section>

        <!-- Columna 3: Más Activos -->
        <section>
            <div class="bg-brand-yellow text-black border-2 border-black p-4 mb-6 shadow-[4px_4px_0px_#000]">
                <h2 class="text-xl font-black uppercase text-center tracking-widest">Más Activos</h2>
            </div>

            <div class="space-y-6">
                @foreach ($mostActive as $user)
                    <x-user-card
                        :user="$user"
                        :rank="$loop->iteration"
                        statLabel="Reseñas"
                        :statValue="$user->reviews_count"
                        avatarBg="bg-gray-200"
                    />
                @endforeach
            </div>
        </section>
    </div>
@endsection
