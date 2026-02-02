@extends('layouts.app')

@section('title', 'Account Settings')

@section('content')
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar -->
        @include('pages.dashboard.partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1">
            <header class="mb-8 border-b-4 border-black pb-4">
                <h1 class="text-3xl font-black uppercase font-display">Account Settings</h1>
                <p class="text-gray-600 font-bold mt-1">Security and privacy preferences</p>
            </header>

            <div class="space-y-8">
                <!-- Email & Password -->
                <x-card>
                    <h3 class="font-black text-lg uppercase mb-6 flex items-center gap-2">
                        <span class="w-3 h-3 bg-brand-blue border border-black"></span>
                        Login & Security
                    </h3>
                    <form action="" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold uppercase mb-2">Email Address</label>
                            <input type="email" name="email" value="{{ auth()->user()->email ?? 'reader@example.com' }}"
                                class="neo-input w-full">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-gray-200">
                            <div>
                                <label class="block text-xs font-bold uppercase mb-2">New Password</label>
                                <input type="password" name="password" class="neo-input w-full">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase mb-2">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="neo-input w-full">
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="neo-btn-secondary px-6">Update Credentials</button>
                        </div>
                    </form>
                </x-card>

                <!-- Privacy -->
                <x-card>
                    <h3 class="font-black text-lg uppercase mb-6 flex items-center gap-2">
                        <span class="w-3 h-3 bg-brand-yellow border border-black"></span>
                        Privacy
                    </h3>
                    <div class="space-y-4">
                        <label
                            class="flex items-center space-x-3 cursor-pointer p-3 border-2 border-transparent hover:border-black/10 transition-colors">
                            <input type="checkbox" checked
                                class="w-5 h-5 border-2 border-black rounded-none focus:ring-0 checked:bg-brand-yellow checked:text-black">
                            <div>
                                <div class="font-bold uppercase text-sm">Public Profile</div>
                                <div class="text-xs text-gray-500">Allow users to see your lists and reviews</div>
                            </div>
                        </label>
                        <label
                            class="flex items-center space-x-3 cursor-pointer p-3 border-2 border-transparent hover:border-black/10 transition-colors">
                            <input type="checkbox" checked
                                class="w-5 h-5 border-2 border-black rounded-none focus:ring-0 checked:bg-brand-yellow checked:text-black">
                            <div>
                                <div class="font-bold uppercase text-sm">Show Currently Reading</div>
                                <div class="text-xs text-gray-500">Display your current book progress on profile</div>
                            </div>
                        </label>
                    </div>
                    <div class="flex justify-end mt-4">
                        <button class="neo-btn-secondary px-6">Save Preferences</button>
                    </div>
                </x-card>

                <!-- Danger Zone -->
                <div class="border-2 border-red-600 p-6 bg-red-50 shadow-[4px_4px_0px_#dc2626]">
                    <h3 class="font-black text-lg uppercase mb-4 text-red-600">Danger Zone</h3>
                    <p class="text-sm font-bold text-gray-800 mb-6">Once you delete your account, there is no going back.
                        Please be certain.</p>
                    <div class="flex justify-end">
                        <button
                            class="bg-red-600 text-white font-black uppercase px-6 py-2 border-2 border-black shadow-[2px_2px_0px_#000] hover:translate-y-[-1px] hover:shadow-[4px_4px_0px_#000] transition-all">
                            Delete Account
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection