@extends('layouts.app')

@section('title', $user->name . '\'s Profile')

@section('content')
    <div class="max-w-7xl mx-auto">
        {{-- Header Section --}}
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
                            <div class="text-3xl font-black">{{ $readBooks->count() }}</div>
                            <div class="text-xs font-bold uppercase text-gray-600">Books Read</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-black">{{ $readingBooks->count() }}</div>
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

        {{-- Reading Activity Section --}}
        <section class="mb-8">
            <h2 class="text-2xl font-black uppercase mb-4 flex items-center gap-2">
                <span class="w-3 h-3 bg-brand-blue border border-black"></span>
                Reading Activity
            </h2>

            {{-- Tabs --}}
            <div class="flex gap-2 mb-6 border-b-2 border-black pb-2">
                <button class="tab-btn active" data-tab="read">
                    Read ({{ $readBooks->count() }})
                </button>
                <button class="tab-btn" data-tab="reading">
                    Reading ({{ $readingBooks->count() }})
                </button>
                <button class="tab-btn" data-tab="pending">
                    Want to Read ({{ $pendingBooks->count() }})
                </button>
            </div>

            {{-- Read Books --}}
            <div class="tab-content active" id="read">
                @if($readBooks->isEmpty())
                    <x-card class="text-center py-12 text-gray-500">
                        <p class="font-bold uppercase text-sm">No books read yet</p>
                    </x-card>
                @else
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                        @foreach($readBooks as $bookUser)
                            <x-book-card
                                :title="$bookUser->book->title"
                                :author="$bookUser->book->authors->pluck('name')->join(', ')"
                                :cover="$bookUser->book->cover_image"
                                :rating="$bookUser->rating ?? 0"
                                :id="$bookUser->book->isbn"
                            />
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Reading Books --}}
            <div class="tab-content hidden" id="reading">
                @if($readingBooks->isEmpty())
                    <x-card class="text-center py-12 text-gray-500">
                        <p class="font-bold uppercase text-sm">Not currently reading anything</p>
                    </x-card>
                @else
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                        @foreach($readingBooks as $bookUser)
                            <x-book-card
                                :title="$bookUser->book->title"
                                :author="$bookUser->book->authors->pluck('name')->join(', ')"
                                :cover="$bookUser->book->cover_image"
                                :rating="0"
                                :id="$bookUser->book->isbn"
                            />
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Pending Books --}}
            <div class="tab-content hidden" id="pending">
                @if($pendingBooks->isEmpty())
                    <x-card class="text-center py-12 text-gray-500">
                        <p class="font-bold uppercase text-sm">No books in want-to-read list</p>
                    </x-card>
                @else
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                        @foreach($pendingBooks as $bookUser)
                            <x-book-card
                                :title="$bookUser->book->title"
                                :author="$bookUser->book->authors->pluck('name')->join(', ')"
                                :cover="$bookUser->book->cover_image"
                                :rating="0"
                                :id="$bookUser->book->isbn"
                            />
                        @endforeach
                    </div>
                @endif
            </div>
        </section>

        {{-- Lists Section --}}
        @if($user->lists->isNotEmpty())
            <section class="mb-8">
                <h2 class="text-2xl font-black uppercase mb-4 flex items-center gap-2">
                    <span class="w-3 h-3 bg-brand-yellow border border-black"></span>
                    Public Lists
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($user->lists as $list)
                        <x-list-card :list="$list" />
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Reviews Section --}}
        @if($user->reviews->isNotEmpty())
            <section class="mb-8">
                <h2 class="text-2xl font-black uppercase mb-4 flex items-center gap-2">
                    <span class="w-3 h-3 bg-brand-pink border border-black"></span>
                    Reviews
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($user->reviews as $review)
                        <x-review-card :review="$review" :showBook="true" />
                    @endforeach
                </div>
                
                @if($user->reviews->count() > 3)
                    <button class="w-full mt-6 neo-btn-secondary">Load More Reviews</button>
                @endif
            </section>
        @endif
        @if($user->followedAuthors->isNotEmpty())
            <section class="mb-8">
                <h2 class="text-2xl font-black uppercase mb-4 flex items-center gap-2">
                    <span class="w-3 h-3 bg-brand-blue border border-black"></span>
                    Following Authors
                </h2>

                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
                    @foreach($user->followedAuthors as $followedAuthor)
                        <x-author-card :author="$followedAuthor" :showFollow="auth()->id() !== $user->id" />
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Social Section --}}
        <section class="mb-8">
            <h2 class="text-2xl font-black uppercase mb-4 flex items-center gap-2">
                <span class="w-3 h-3 bg-black border border-black"></span>
                Social
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Following --}}
                <x-card>
                    <h3 class="font-black uppercase text-lg mb-4 pb-2 border-b-2 border-black/10">
                        Following ({{ $followingCount }})
                    </h3>
                    @if($followingCount > 0)
                        <p class="text-sm text-gray-600 font-bold">{{ $user->name }} is following {{ $followingCount }} {{ Str::plural('user', $followingCount) }}</p>
                    @else
                        <p class="text-sm text-gray-500 italic">Not following anyone yet</p>
                    @endif
                </x-card>

                {{-- Followers --}}
                <x-card>
                    <h3 class="font-black uppercase text-lg mb-4 pb-2 border-b-2 border-black/10">
                        Followers ({{ $followersCount }})
                    </h3>
                    @if($followersCount > 0)
                        <p class="text-sm text-gray-600 font-bold">{{ $followersCount }} {{ Str::plural('user', $followersCount) }} {{ $followersCount === 1 ? 'follows' : 'follow' }} {{ $user->name }}</p>
                    @else
                        <p class="text-sm text-gray-500 italic">No followers yet</p>
                    @endif
                </x-card>
            </div>
        </section>
    </div>

    {{-- Tab Switching Script --}}
    <script>
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const tab = this.dataset.tab;
                
                // Update buttons
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                // Update content
                document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
                document.getElementById(tab).classList.remove('hidden');
            });
        });
    </script>

    <style>
        .tab-btn {
            @apply px-4 py-2 font-bold uppercase text-sm border-2 border-black bg-white hover:bg-gray-100 transition-colors;
        }
        .tab-btn.active {
            @apply bg-brand-blue text-white;
        }
    </style>
@endsection
