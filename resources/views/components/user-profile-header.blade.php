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

        {{-- User Info --}}
        <div class="flex-1 text-center md:text-left">
            <h1 class="text-4xl font-black uppercase font-display mb-2">{{ $user->name }}</h1>
            @if($user->country)
                <p class="text-sm font-bold text-gray-600 uppercase mb-4">
                    📍 {{ $user->country->name }}
                </p>
            @endif

            {{-- Stats Grid --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                <div class="text-center">
                    <div class="text-3xl font-black">{{ $readBooksCount }}</div>
                    <div class="text-xs font-bold uppercase text-gray-600">Books Read</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-black">{{ $readingBooksCount }}</div>
                    <div class="text-xs font-bold uppercase text-gray-600">Reading</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-black">{{ $user->lists->count() }}</div>
                    <div class="text-xs font-bold uppercase text-gray-600">Lists</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-black">{{ $user->reviews->count() }}</div>
                    <div class="text-xs font-bold uppercase text-gray-600">Reviews</div>
                </div>
            </div>
        </div>

        {{-- Follow Button (if not own profile) --}}
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
