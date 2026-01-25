<aside class="w-full lg:w-64 flex-shrink-0">
    <x-card class="bg-white p-0 overflow-hidden">
        <div class="p-6 bg-black text-white">
            <h2 class="font-black uppercase text-xl">My Account</h2>
            <p class="text-xs font-bold text-gray-400">Manage your collection</p>
        </div>
        <nav class="flex flex-col">
            <a href="{{ route('dashboard.index') }}"
                class="px-6 py-4 border-b border-black font-bold uppercase hover:bg-brand-yellow hover:text-black transition-colors {{ request()->routeIs('dashboard.index') ? 'bg-brand-yellow text-black' : '' }}">
                Overview
            </a>
            <a href="{{ route('dashboard.profile') }}"
                class="px-6 py-4 border-b border-black font-bold uppercase hover:bg-brand-yellow hover:text-black transition-colors {{ request()->routeIs('dashboard.profile') ? 'bg-brand-yellow text-black' : '' }}">
                Edit Profile
            </a>
            <a href="{{ route('dashboard.lists') }}"
                class="px-6 py-4 border-b border-black font-bold uppercase hover:bg-brand-yellow hover:text-black transition-colors {{ request()->routeIs('dashboard.lists') ? 'bg-brand-yellow text-black' : '' }}">
                My Lists
            </a>
            <a href="{{ route('dashboard.reviews') }}"
                class="px-6 py-4 border-b border-black font-bold uppercase hover:bg-brand-yellow hover:text-black transition-colors {{ request()->routeIs('dashboard.reviews') ? 'bg-brand-yellow text-black' : '' }}">
                My Reviews
            </a>
            <a href="{{ route('dashboard.settings') }}"
                class="px-6 py-4 font-bold uppercase hover:bg-red-500 hover:text-white transition-colors {{ request()->routeIs('dashboard.settings') ? 'bg-red-500 text-white' : '' }}">
                Settings
            </a>
        </nav>
    </x-card>
</aside>