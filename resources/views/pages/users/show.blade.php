@extends('layouts.app')

@section('title', $user->name . '\'s Profile')

@section('content')
    <div class="max-w-7xl mx-auto">
        {{-- Header Section --}}
        <x-user-profile-header 
            :user="$user" 
            :readBooksCount="$readBooks->total()" 
            :readingBooksCount="$readingBooks->total()" 
        />

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
                    <div id="read-books-grid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
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
                    @if($readBooks->hasMorePages())
                        <x-modals.load-more :url="route('users.load-books', [$user, 'read'])" target="read-books-grid" />
                    @endif
                @endif
            </div>

            {{-- Reading Books --}}
            <div class="tab-content hidden" id="reading">
                @if($readingBooks->isEmpty())
                    <x-card class="text-center py-12 text-gray-500">
                        <p class="font-bold uppercase text-sm">Not currently reading anything</p>
                    </x-card>
                @else
                    <div id="reading-books-grid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
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
                    @if($readingBooks->hasMorePages())
                        <x-modals.load-more :url="route('users.load-books', [$user, 'reading'])" target="reading-books-grid" />
                    @endif
                @endif
            </div>

            {{-- Pending Books --}}
            <div class="tab-content hidden" id="pending">
                @if($pendingBooks->isEmpty())
                    <x-card class="text-center py-12 text-gray-500">
                        <p class="font-bold uppercase text-sm">No books in want-to-read list</p>
                    </x-card>
                @else
                    <div id="pending-books-grid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
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
                    @if($pendingBooks->hasMorePages())
                        <x-modals.load-more :url="route('users.load-books', [$user, 'pending'])" target="pending-books-grid" />
                    @endif
                @endif
            </div>
        </section>

        {{-- Lists Section --}}
        @if($listsPaginated->isNotEmpty())
            <section class="mb-8">
                <h2 class="text-2xl font-black uppercase mb-4 flex items-center gap-2">
                    <span class="w-3 h-3 bg-brand-yellow border border-black"></span>
                    Public Lists
                </h2>

                <div id="lists-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($listsPaginated as $list)
                        <x-list-card :list="$list" />
                    @endforeach
                </div>

                @if($listsPaginated->hasMorePages())
                    <x-modals.load-more :url="route('users.load-lists', $user)" target="lists-grid" />
                @endif
            </section>
        @endif

        {{-- Reviews Section --}}
        @if($reviews->isNotEmpty())
            <section class="mb-8">
                <h2 class="text-2xl font-black uppercase mb-4 flex items-center gap-2">
                    <span class="w-3 h-3 bg-brand-pink border border-black"></span>
                    Reviews
                </h2>

                <div id="reviews-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($reviews as $review)
                        <x-review-card :review="$review" :showBook="true" />
                    @endforeach
                </div>
                
                @if($reviews->hasMorePages())
                    <x-modals.load-more :url="route('users.load-reviews', $user)" target="reviews-grid" />
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
                <x-social-stats-card 
                    title="Following" 
                    :count="$followingCount" 
                    :message="$user->name . ' is following ' . $followingCount . ' ' . Str::plural('user', $followingCount)"
                    emptyMessage="Not following anyone yet" 
                    :userId="$user->id"
                    type="following"
                />

                {{-- Followers --}}
                <x-social-stats-card 
                    title="Followers" 
                    :count="$followersCount" 
                    :message="$followersCount . ' ' . Str::plural('user', $followersCount) . ' ' . ($followersCount === 1 ? 'follows' : 'follow') . ' ' . $user->name"
                    emptyMessage="No followers yet" 
                    :userId="$user->id"
                    type="followers"
                />
            </div>
        </section>

        {{-- Modals --}}
        <x-modals.user-list-modal />
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
