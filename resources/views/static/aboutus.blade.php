@extends('layouts.app')

@section('title', 'About Us')

@section('content')
    <!-- Mission Section -->
    <section class="text-center mb-20">
        <h1 class="text-5xl md:text-7xl font-black uppercase font-display mb-8">We Love <br><span
                class="bg-black text-white px-2">Data</span> & Books.</h1>
        <p class="max-w-2xl mx-auto text-xl font-bold text-gray-700 leading-relaxed">
            BlockBookster was born from a frustration with cluttered, ad-filled book tracking sites. We believe in brutal
            simplicity, raw data, and a community that cares more about the story than the status.
        </p>
    </section>

    <!-- Stats Section -->
    <section class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-20">
        <div class="text-center border-r-2 border-black last:border-0">
            <div class="text-4xl font-black text-brand-blue">24k+</div>
            <div class="text-xs font-bold uppercase">Books Tracked</div>
        </div>
        <div class="text-center border-r-2 border-black last:border-0">
            <div class="text-4xl font-black text-brand-yellow">12k+</div>
            <div class="text-xs font-bold uppercase">Active Users</div>
        </div>
        <div class="text-center border-r-2 border-black last:border-0">
            <div class="text-4xl font-black">85k+</div>
            <div class="text-xs font-bold uppercase">Reviews</div>
        </div>
        <div class="text-center">
            <div class="text-4xl font-black text-gray-400">0</div>
            <div class="text-xs font-bold uppercase">Ads Shown</div>
        </div>
    </section>

    <!-- Team Section -->
    <section>
        <h2
            class="text-3xl font-black uppercase font-display mb-8 text-center border-b-4 border-black pb-4 inline-block mx-auto">
            The Team</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Member 1 -->
            <x-card class="text-center group hover:-translate-y-2 transition-transform">
                <div class="w-32 h-32 mx-auto bg-gray-200 rounded-full border-4 border-black mb-4 overflow-hidden">
                    <img src="https://ui-avatars.com/api/?name=Alex+Founder&background=random"
                        class="w-full h-full object-cover">
                </div>
                <h3 class="font-black uppercase text-xl">Alex Founder</h3>
                <p class="text-sm font-bold text-brand-blue uppercase mb-2">CEO & Lead Dev</p>
                <p class="text-sm text-gray-600">"I just wanted a place to list my sci-fi collection without the noise."</p>
            </x-card>

            <!-- Member 2 -->
            <x-card class="text-center group hover:-translate-y-2 transition-transform">
                <div class="w-32 h-32 mx-auto bg-gray-200 rounded-full border-4 border-black mb-4 overflow-hidden">
                    <img src="https://ui-avatars.com/api/?name=Sarah+Design&background=random"
                        class="w-full h-full object-cover">
                </div>
                <h3 class="font-black uppercase text-xl">Sarah Design</h3>
                <p class="text-sm font-bold text-brand-yellow uppercase mb-2 text-shadow-neo">Head of Product</p>
                <p class="text-sm text-gray-600">"Brutalism isn't just an aesthetic, it's a philosophy of honesty."</p>
            </x-card>

            <!-- Member 3 -->
            <x-card class="text-center group hover:-translate-y-2 transition-transform">
                <div class="w-32 h-32 mx-auto bg-gray-200 rounded-full border-4 border-black mb-4 overflow-hidden">
                    <img src="https://ui-avatars.com/api/?name=Mike+Ops&background=random"
                        class="w-full h-full object-cover">
                </div>
                <h3 class="font-black uppercase text-xl">Mike Ops</h3>
                <p class="text-sm font-bold text-gray-500 uppercase mb-2">Community Manager</p>
                <p class="text-sm text-gray-600">"Keeping the trolls under the bridge and the discussions civil."</p>
            </x-card>
        </div>
    </section>
@endsection