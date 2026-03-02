@props([
    'user',
    'rank' => null,
    'statLabel',
    'statValue',
    'avatarBg' => 'bg-gray-200'
])

<div class="neo-card p-4 flex items-center gap-4 hover:translate-x-1 transition-transform">
    @if($rank)
        <div class="text-2xl font-black text-gray-300 w-8">#{{ $rank }}</div>
    @endif
    <a href="{{ route('users.show', $user->id) }}" class="flex items-center gap-4 flex-grow">
        <div class="w-12 h-12 {{ $avatarBg }} rounded-full border-2 border-black flex-shrink-0 overflow-hidden">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random"
                class="w-full h-full object-cover">
        </div>
        <div class="flex-grow">
            <h3 class="font-bold uppercase text-sm truncate hover:text-brand-blue">{{ $user->name }}</h3>
            <p class="text-xs font-bold text-gray-500">{{ $statValue }} {{ $statLabel }}</p>
        </div>
    </a>
    @auth
        @if(auth()->id() !== $user->id)
            <x-modals.follow-modal
                :followableId="$user->id"
                followableType="user"
                :isFollowing="auth()->user()->isFollowing($user)"
                :followUrl="route('users.follow', $user)"
                class="text-xs font-black uppercase border-2 border-black px-3 py-1 hover:bg-brand-yellow transition-colors"
            />
        @endif
    @endauth
</div>
