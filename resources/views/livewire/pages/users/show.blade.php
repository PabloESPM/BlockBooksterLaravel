<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Str;

new #[Layout('layouts.app')] class extends Component {
    public User $user;
    
    // Estados para "Cargar más"
    public $readLimit = 5;
    public $readingLimit = 5;
    public $pendingLimit = 5;
    public $reviewsLimit = 3;
    public $listsLimit = 3;
    public $followedListsLimit = 3;

    // Estados de modales
    public bool $showFollowingModal = false;
    public bool $showFollowersModal = false;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function openFollowingModal()
    {
        $this->showFollowingModal = true;
    }

    public function closeFollowingModal()
    {
        $this->showFollowingModal = false;
    }

    public function openFollowersModal()
    {
        $this->showFollowersModal = true;
    }

    public function closeFollowersModal()
    {
        $this->showFollowersModal = false;
    }

    #[On('follow-updated')]
    public function handleFollowUpdated($type, $id, $following)
    {
        $this->user->refresh();
    }

    public function loadMore($section)
    {
        if ($section === 'read') $this->readLimit += 5;
        if ($section === 'reading') $this->readingLimit += 5;
        if ($section === 'pending') $this->pendingLimit += 5;
        if ($section === 'reviews') $this->reviewsLimit += 3;
        if ($section === 'lists') $this->listsLimit += 3;
        if ($section === 'followedLists') $this->followedListsLimit += 3;
    }

    public function with()
    {
        $viewer = auth()->user();
        $isOwner = $viewer && $viewer->id === $this->user->id;

        // Lógica de privacidad
        $canViewContent = match ($this->user->profile_visibility) {
            'public' => true,
            'followers' => $isOwner || ($viewer && $viewer->isFollowing($this->user)),
            'friends' => $isOwner || ($viewer && $viewer->isFriend($this->user)),
            'private' => $isOwner,
            default => true,
        };

        if (!$canViewContent) {
            return [
                'canViewContent' => false,
                'user' => $this->user,
            ];
        }

        // Cargamos datos con límites reactivos
        $readBooks = $this->user->books()->where('status', 'read')
            ->with('book.authors')
            ->take($this->readLimit)
            ->get();
        $hasMoreRead = $this->user->books()->where('status', 'read')->count() > $this->readLimit;

        $readingBooks = $this->user->books()->where('status', 'reading')
            ->with('book.authors')
            ->take($this->readingLimit)
            ->get();
        $hasMoreReading = $this->user->books()->where('status', 'reading')->count() > $this->readingLimit;

        $pendingBooks = $this->user->books()->where('status', 'pending')
            ->with('book.authors')
            ->take($this->pendingLimit)
            ->get();
        $hasMorePending = $this->user->books()->where('status', 'pending')->count() > $this->pendingLimit;

        $reviews = $this->user->reviews()
            ->with(['book', 'likes'])
            ->withCount('likes')
            ->latest()
            ->take($this->reviewsLimit)
            ->get();
        $hasMoreReviews = $this->user->reviews()->count() > $this->reviewsLimit;

        $lists = $this->user->lists()
            ->with(['likes', 'user'])
            ->withCount(['books', 'likes'])
            ->when(!$isOwner, function ($q) use ($viewer) {
                if ($viewer && $viewer->isFriend($this->user)) {
                    $q->whereIn('visibility', ['public', 'followers', 'friends']);
                } elseif ($viewer && $viewer->isFollowing($this->user)) {
                    $q->whereIn('visibility', ['public', 'followers']);
                } else {
                    $q->where('visibility', 'public');
                }
            })
            ->latest()
            ->take($this->listsLimit)
            ->get();
        $hasMoreLists = $this->user->lists()
            ->when(!$isOwner, function ($q) use ($viewer) {
                if ($viewer && $viewer->isFriend($this->user)) {
                    $q->whereIn('visibility', ['public', 'followers', 'friends']);
                } elseif ($viewer && $viewer->isFollowing($this->user)) {
                    $q->whereIn('visibility', ['public', 'followers']);
                } else {
                    $q->where('visibility', 'public');
                }
            })
            ->count() > $this->listsLimit;

        $followersCount = $this->user->followers()->count();
        $followingCount = $this->user->following()->count();

        // Listas SEGUIDAS por el usuario (solo las públicas, visibles para terceros)
        $followedLists = $this->user->likedLists()
            ->where('visibility', 'public')
            ->with(['user', 'books', 'likes'])
            ->withCount(['books', 'likes'])
            ->latest('list_likes.created_at')
            ->take($this->followedListsLimit)
            ->get();

        $hasMoreFollowedLists = $this->user->likedLists()
            ->where('visibility', 'public')
            ->count() > $this->followedListsLimit;

        $followingUsers = $this->showFollowingModal
            ? $this->user->following()
                ->with(['followed' => fn ($q) => $q->withCount(['followers', 'books'])])
                ->latest()
                ->get()
                ->pluck('followed')
                ->filter()
                ->values()
            : collect();

        $followerUsers = $this->showFollowersModal
            ? $this->user->followers()
                ->with(['follower' => fn ($q) => $q->withCount(['followers', 'books'])])
                ->latest()
                ->get()
                ->pluck('follower')
                ->filter()
                ->values()
            : collect();

        return [
            'canViewContent' => true,
            'readBooks' => $readBooks,
            'hasMoreRead' => $hasMoreRead,
            'readingBooks' => $readingBooks,
            'hasMoreReading' => $hasMoreReading,
            'pendingBooks' => $pendingBooks,
            'hasMorePending' => $hasMorePending,
            'reviews' => $reviews,
            'hasMoreReviews' => $hasMoreReviews,
            'listsPaginated' => $lists, // Mantengo el nombre de la variable original para el blade
            'hasMoreLists' => $hasMoreLists,
            'followedLists' => $followedLists,
            'hasMoreFollowedLists' => $hasMoreFollowedLists,
            'followersCount' => $followersCount,
            'followingCount' => $followingCount,
            'followingUsers' => $followingUsers,
            'followerUsers' => $followerUsers,
        ];
    }

    public function rendering($view)
    {
        $view->title('Perfil de ' . $this->user->name);
    }
}; ?>

<div class="max-w-7xl mx-auto"
     x-data
     @open-user-list-modal.window="
         if ($event.detail.type === 'following') {
             $wire.openFollowingModal();
         } else if ($event.detail.type === 'followers') {
             $wire.openFollowersModal();
         }
     ">
    {{-- Sección de Encabezado --}}
    <x-user-profile-header
        :user="$user"
        :readBooksCount="$user->books()->where('status', 'read')->count()"
        :readingBooksCount="$user->books()->where('status', 'reading')->count()"
    />

    {{-- Las secciones de contenido solo se muestran si el visitante tiene permiso --}}
    @if($canViewContent)

        {{-- Sección de Actividad de Lectura --}}
        <section class="mb-8" x-data="{ activeTab: 'read' }">
            <h2 class="text-2xl font-black uppercase mb-4 flex items-center gap-2">
                <span class="w-3 h-3 bg-brand-blue border border-black"></span>
                Actividad de Lectura
            </h2>

            {{-- Pestañas --}}
            <div class="flex gap-2 mb-6 border-b-2 border-black pb-2">
                <button @click="activeTab = 'read'" :class="activeTab === 'read' ? 'bg-brand-blue text-white' : 'bg-white'" class="px-4 py-2 font-bold uppercase text-sm border-2 border-black hover:bg-gray-100 transition-colors">
                    Leídos ({{ $user->books()->where('status', 'read')->count() }})
                </button>
                <button @click="activeTab = 'reading'" :class="activeTab === 'reading' ? 'bg-brand-blue text-white' : 'bg-white'" class="px-4 py-2 font-bold uppercase text-sm border-2 border-black hover:bg-gray-100 transition-colors">
                    Leyendo ({{ $user->books()->where('status', 'reading')->count() }})
                </button>
                <button @click="activeTab = 'pending'" :class="activeTab === 'pending' ? 'bg-brand-blue text-white' : 'bg-white'" class="px-4 py-2 font-bold uppercase text-sm border-2 border-black hover:bg-gray-100 transition-colors">
                    Quiero Leer ({{ $user->books()->where('status', 'pending')->count() }})
                </button>
            </div>

            {{-- Libros Leídos --}}
            <div x-show="activeTab === 'read'">
                @if($readBooks->isEmpty())
                    <x-card class="text-center py-12 text-gray-500">
                        <p class="font-bold uppercase text-sm">Aún no ha leído ningún libro</p>
                    </x-card>
                @else
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                        @foreach($readBooks as $bookUser)
                            <div wire:key="read-{{ $bookUser->id }}">
                                <x-book-card
                                    :title="$bookUser->book->title"
                                    :author="$bookUser->book->authors->pluck('name')->join(', ')"
                                    :cover="$bookUser->book->cover_image"
                                    :rating="$bookUser->rating ?? 0"
                                    :id="$bookUser->book->isbn"
                                />
                            </div>
                        @endforeach
                    </div>
                    @if($hasMoreRead)
                        <div class="mt-8 flex justify-center">
                            <button wire:click="loadMore('read')" wire:loading.attr="disabled" class="neo-btn-secondary px-8 py-3 uppercase font-black">
                                <span wire:loading wire:target="loadMore('read')" class="inline-block w-4 h-4 border-2 border-black border-t-transparent rounded-full animate-spin mr-2"></span>
                                Cargar más
                            </button>
                        </div>
                    @endif
                @endif
            </div>

            {{-- Libros Leyendo --}}
            <div x-show="activeTab === 'reading'" x-cloak>
                @if($readingBooks->isEmpty())
                    <x-card class="text-center py-12 text-gray-500">
                        <p class="font-bold uppercase text-sm">Actualmente no está leyendo nada</p>
                    </x-card>
                @else
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                        @foreach($readingBooks as $bookUser)
                            <div wire:key="reading-{{ $bookUser->id }}">
                                <x-book-card
                                    :title="$bookUser->book->title"
                                    :author="$bookUser->book->authors->pluck('name')->join(', ')"
                                    :cover="$bookUser->book->cover_image"
                                    :rating="0"
                                    :id="$bookUser->book->isbn"
                                />
                            </div>
                        @endforeach
                    </div>
                    @if($hasMoreReading)
                        <div class="mt-8 flex justify-center">
                            <button wire:click="loadMore('reading')" wire:loading.attr="disabled" class="neo-btn-secondary px-8 py-3 uppercase font-black">
                                <span wire:loading wire:target="loadMore('reading')" class="inline-block w-4 h-4 border-2 border-black border-t-transparent rounded-full animate-spin mr-2"></span>
                                Cargar más
                            </button>
                        </div>
                    @endif
                @endif
            </div>

            {{-- Libros Pendientes --}}
            <div x-show="activeTab === 'pending'" x-cloak>
                @if($pendingBooks->isEmpty())
                    <x-card class="text-center py-12 text-gray-500">
                        <p class="font-bold uppercase text-sm">No hay libros en la lista de deseos</p>
                    </x-card>
                @else
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                        @foreach($pendingBooks as $bookUser)
                            <div wire:key="pending-{{ $bookUser->id }}">
                                <x-book-card
                                    :title="$bookUser->book->title"
                                    :author="$bookUser->book->authors->pluck('name')->join(', ')"
                                    :cover="$bookUser->book->cover_image"
                                    :rating="0"
                                    :id="$bookUser->book->isbn"
                                />
                            </div>
                        @endforeach
                    </div>
                    @if($hasMorePending)
                        <div class="mt-8 flex justify-center">
                            <button wire:click="loadMore('pending')" wire:loading.attr="disabled" class="neo-btn-secondary px-8 py-3 uppercase font-black">
                                <span wire:loading wire:target="loadMore('pending')" class="inline-block w-4 h-4 border-2 border-black border-t-transparent rounded-full animate-spin mr-2"></span>
                                Cargar más
                            </button>
                        </div>
                    @endif
                @endif
            </div>
        </section>

        {{-- Sección de Listas Públicas (creadas por el usuario) --}}
        @if($listsPaginated->isNotEmpty())
            <section class="mb-8">
                <h2 class="text-2xl font-black uppercase mb-4 flex items-center gap-2">
                    <span class="w-3 h-3 bg-brand-yellow border border-black"></span>
                    Listas Públicas
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($listsPaginated as $list)
                        <div wire:key="list-{{ $list->id }}">
                            <x-list-card :list="$list" />
                        </div>
                    @endforeach
                </div>

                @if($hasMoreLists)
                    <div class="mt-8 flex justify-center">
                        <button wire:click="loadMore('lists')" wire:loading.attr="disabled" class="neo-btn-secondary px-8 py-3 uppercase font-black">
                            Cargar más listas
                        </button>
                    </div>
                @endif
            </section>
        @endif

        {{-- Sección de Listas Seguidas (solo públicas, solo si el perfil es accesible) --}}
        @if($user->profile_visibility === 'public' && $followedLists->isNotEmpty())
            <section class="mb-8">
                <h2 class="text-2xl font-black uppercase mb-4 flex items-center gap-2">
                    <span class="w-3 h-3 bg-brand-blue border border-black"></span>
                    Listas Seguidas
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($followedLists as $list)
                        <div wire:key="followed-list-{{ $list->id }}">
                            <x-list-card :list="$list" />
                        </div>
                    @endforeach
                </div>

                @if($hasMoreFollowedLists)
                    <div class="mt-8 flex justify-center">
                        <button wire:click="loadMore('followedLists')" wire:loading.attr="disabled"
                            class="neo-btn-secondary px-8 py-3 uppercase font-black">
                            <span wire:loading wire:target="loadMore('followedLists')"
                                class="inline-block w-4 h-4 border-2 border-black border-t-transparent rounded-full animate-spin mr-2"></span>
                            Cargar más listas seguidas
                        </button>
                    </div>
                @endif
            </section>
        @endif

        {{-- Sección de Reseñas --}}
        @if($reviews->isNotEmpty())
            <section class="mb-8">
                <h2 class="text-2xl font-black uppercase mb-4 flex items-center gap-2">
                    <span class="w-3 h-3 bg-brand-pink border border-black"></span>
                    Reseñas
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($reviews as $review)
                        <div wire:key="review-{{ $review->id }}">
                            <x-review-card :review="$review" :showBook="true" />
                        </div>
                    @endforeach
                </div>

                @if($hasMoreReviews)
                    <div class="mt-8 flex justify-center">
                        <button wire:click="loadMore('reviews')" wire:loading.attr="disabled" class="neo-btn-secondary px-8 py-3 uppercase font-black">
                            Cargar más reseñas
                        </button>
                    </div>
                @endif
            </section>
        @endif

        {{-- Autores Seguidos --}}
        @if($user->followedAuthors->isNotEmpty())
            <section class="mb-8">
                <h2 class="text-2xl font-black uppercase mb-4 flex items-center gap-2">
                    <span class="w-3 h-3 bg-brand-blue border border-black"></span>
                    Autores Seguidos
                </h2>

                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
                    @foreach($user->followedAuthors as $followedAuthor)
                        <div wire:key="followed-author-{{ $followedAuthor->id }}">
                            <x-author-card :author="$followedAuthor" :showFollow="auth()->id() !== $user->id" />
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Sección Social --}}
        <section class="mb-8">
            <h2 class="text-2xl font-black uppercase mb-4 flex items-center gap-2">
                <span class="w-3 h-3 bg-black border border-black"></span>
                Social
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Siguiendo --}}
                <x-social-stats-card
                    title="Siguiendo"
                    :count="$followingCount"
                    :message="$user->name . ' sigue a ' . $followingCount . ' ' . Str::plural('usuario', $followingCount)"
                    emptyMessage="Aún no sigue a nadie"
                    :userId="$user->id"
                    type="following"
                />

                {{-- Seguidores --}}
                <x-social-stats-card
                    title="Seguidores"
                    :count="$followersCount"
                    :message="$followersCount . ' ' . Str::plural('usuario', $followersCount) . ' sigue a ' . $user->name"
                    emptyMessage="Aún no tiene seguidores"
                    :userId="$user->id"
                    type="followers"
                />
            </div>
        </section>

        @if($showFollowingModal)
            <x-modals.following-list :users="$followingUsers" />
        @endif

        @if($showFollowersModal)
            <x-modals.followers-list :users="$followerUsers" />
        @endif

    @else
        {{-- Mensaje cuando el perfil tiene contenido restringido --}}
        <x-card class="text-center py-16">
            <div class="text-4xl mb-4">🔒</div>
            <h2 class="text-xl font-black uppercase mb-2">Contenido Restringido</h2>
            <p class="text-gray-500 font-bold text-sm">
                @if($user->profile_visibility === 'private')
                    Este perfil es privado. Solo el propietario puede ver su contenido.
                @elseif($user->profile_visibility === 'followers')
                    Este contenido es visible únicamente para los seguidores de {{ $user->name }}.
                @else
                    Este contenido es visible únicamente para los amigos de {{ $user->name }}.
                @endif
            </p>
        </x-card>
    @endif
</div>
