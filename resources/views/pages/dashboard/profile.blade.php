@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar -->
        @include('pages.dashboard.partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1">
            <header class="mb-8 border-b-4 border-black pb-4">
                <h1 class="text-3xl font-black uppercase font-display">Edit Profile</h1>
                <p class="text-gray-600 font-bold mt-1">Update your personal information</p>
            </header>

            <x-card>
                <form action="" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Avatar Section -->
                    <div class="flex items-center gap-6 pb-6 border-b-2 border-gray-200">
                        <div
                            class="w-24 h-24 bg-gray-200 rounded-full border-2 border-black flex-shrink-0 relative overflow-hidden group">
                            <!-- Placeholder/Current Avatar -->
                            <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name ?? 'Reader' }}&background=random"
                                class="w-full h-full object-cover">
                            <div
                                class="absolute inset-0 bg-black/50 hidden group-hover:flex items-center justify-center text-white text-xs font-bold uppercase cursor-pointer">
                                Upload
                            </div>
                        </div>
                        <div>
                            <h3 class="font-bold uppercase text-sm mb-1">Profile Photo</h3>
                            <p class="text-xs text-gray-500 mb-3">Recommended size: 500x500px</p>
                            <button type="button" class="neo-btn-secondary py-1 px-3 text-xs">Change Photo</button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold uppercase mb-2">Display Name</label>
                            <input type="text" name="name" value="{{ auth()->user()->name ?? 'Reader' }}"
                                class="neo-input w-full">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase mb-2">Location</label>
                            <input type="text" name="location" placeholder="e.g. Madrid, Spain" class="neo-input w-full">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold uppercase mb-2">Bio</label>
                            <textarea name="bio" rows="4" class="neo-input w-full"
                                placeholder="Tell us about your reading habits..."></textarea>
                        </div>
                    </div>

                    <div class="border-t-2 border-black pt-6 mt-6">
                        <h3 class="font-bold uppercase text-sm mb-4">Social Links</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold uppercase mb-2">Website</label>
                                <input type="url" name="website" placeholder="https://" class="neo-input w-full">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase mb-2">Twitter / X</label>
                                <input type="text" name="twitter" placeholder="@username" class="neo-input w-full">
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 flex justify-end">
                        <button type="submit" class="neo-btn-primary px-8">Save Changes</button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
@endsection