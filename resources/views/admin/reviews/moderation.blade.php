@extends('layouts.admin')

@section('title', 'Review Moderation')

@section('content')
    <h1 class="text-4xl font-black uppercase font-display mb-8">Review Moderation</h1>

    <div class="grid grid-cols-1 gap-6">
        @for($i = 0; $i < 3; $i++)
            <x-card class="border-l-8 border-l-brand-yellow">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gray-200 rounded-full border border-black overflow-hidden">
                            <!-- Avatar -->
                        </div>
                        <div>
                            <div class="font-bold uppercase text-sm">User_{{$i}}</div>
                            <div class="text-xs text-gray-500">Reported for: <span class="text-red-600 font-bold">Spam /
                                    Advertising</span></div>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-gray-400 uppercase">2 hours ago</span>
                </div>

                <div class="bg-gray-50 p-4 border border-gray-200 mb-4">
                    <h4 class="font-bold text-sm mb-1">Review on "The Great Gatsby"</h4>
                    <p class="text-sm italic text-gray-600">"Buy cheap sunglasses at [suspicious link]..."</p>
                </div>

                <div class="flex items-center gap-4">
                    <button
                        class="neo-btn-secondary py-1 px-4 text-xs bg-red-100 text-red-800 hover:bg-red-600 hover:text-white border-red-800">Delete
                        Review</button>
                    <button class="neo-btn-secondary py-1 px-4 text-xs bg-white hover:bg-gray-100">Ignore Report</button>
                    <button class="text-xs font-bold uppercase text-black hover:underline ml-auto">View User Profile</button>
                </div>
            </x-card>
        @endfor

        <div class="text-center text-gray-500 font-bold uppercase text-xs py-8">
            No more pending reports
        </div>
    </div>
@endsection