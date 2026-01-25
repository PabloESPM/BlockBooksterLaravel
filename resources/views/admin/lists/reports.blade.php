@extends('layouts.admin')

@section('title', 'Reported Lists')

@section('content')
    <h1 class="text-4xl font-black uppercase font-display mb-8">Reported Lists</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Report 1 -->
        <x-card class="border-l-8 border-l-red-500">
            <div class="flex justify-between items-start mb-4">
                <h3 class="font-black uppercase text-lg">Inappropriate Content Collection</h3>
                <span class="bg-red-100 text-red-800 text-xs font-bold uppercase px-2 py-0.5 border border-red-200">2
                    Reports</span>
            </div>
            <p class="text-sm text-gray-600 mb-4">Created by <span class="font-bold">BadActor99</span></p>

            <div class="flex items-center gap-4 mt-auto">
                <button
                    class="neo-btn-secondary py-1 px-4 text-xs bg-red-600 text-white hover:bg-red-700 border-black">Remove
                    List</button>
                <button class="text-xs font-bold uppercase text-gray-500 hover:text-black">Dismiss</button>
            </div>
        </x-card>

        <!-- Report 2 -->
        <x-card class="border-l-8 border-l-brand-yellow">
            <div class="flex justify-between items-start mb-4">
                <h3 class="font-black uppercase text-lg">Fake Books?</h3>
                <span
                    class="bg-yellow-100 text-yellow-800 text-xs font-bold uppercase px-2 py-0.5 border border-yellow-200">1
                    Report</span>
            </div>
            <p class="text-sm text-gray-600 mb-4">Created by <span class="font-bold">NoviceUser</span></p>

            <div class="flex items-center gap-4 mt-auto">
                <button
                    class="neo-btn-secondary py-1 px-4 text-xs bg-red-600 text-white hover:bg-red-700 border-black">Remove
                    List</button>
                <button class="text-xs font-bold uppercase text-gray-500 hover:text-black">Dismiss</button>
            </div>
        </x-card>
    </div>
@endsection