@extends('layouts.app')

@section('title', 'Community')

@section('content')
    <div class="mb-12 border-b-4 border-black pb-4">
        <h1 class="text-4xl md:text-6xl font-black font-display uppercase tracking-tighter">Community <span
                class="text-brand-yellow text-shadow-neo">Hub</span></h1>
        <p class="text-lg font-bold mt-2 text-gray-600 uppercase tracking-widest">Connect with fellow readers</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <!-- Column 1: Most Followed -->
        <section>
            <div class="bg-black text-white p-4 mb-6 shadow-[4px_4px_0px_#888]">
                <h2 class="text-xl font-black uppercase text-center tracking-widest">Most Followed</h2>
            </div>

            <div class="space-y-6">
                @foreach ($mostFollowed as $user)
                    <x-user-card 
                        :user="$user" 
                        :rank="$loop->iteration" 
                        statLabel="Followers" 
                        :statValue="$user->followers_count" 
                        avatarBg="bg-brand-blue"
                    />
                @endforeach
            </div>
        </section>

        <!-- Column 2: Most Lists Created -->
        <section>
            <div class="bg-brand-blue text-white p-4 mb-6 shadow-[4px_4px_0px_#000]">
                <h2 class="text-xl font-black uppercase text-center tracking-widest">Top Curators</h2>
            </div>

            <div class="space-y-6">
                @foreach ($topCurators as $user)
                    <x-user-card 
                        :user="$user" 
                        :rank="$loop->iteration" 
                        statLabel="Lists Created" 
                        :statValue="$user->lists_count" 
                        avatarBg="bg-brand-yellow"
                    />
                @endforeach
            </div>
        </section>

        <!-- Column 3: Most Active -->
        <section>
            <div class="bg-brand-yellow text-black border-2 border-black p-4 mb-6 shadow-[4px_4px_0px_#000]">
                <h2 class="text-xl font-black uppercase text-center tracking-widest">Most Active</h2>
            </div>

            <div class="space-y-6">
                @foreach ($mostActive as $user)
                    <x-user-card 
                        :user="$user" 
                        :rank="$loop->iteration" 
                        statLabel="Reviews" 
                        :statValue="$user->reviews_count" 
                        avatarBg="bg-gray-200"
                    />
                @endforeach
            </div>
        </section>
    </div>
@endsection