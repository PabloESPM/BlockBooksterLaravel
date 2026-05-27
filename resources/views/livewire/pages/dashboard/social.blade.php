<?php
/**
 * Página Livewire SFC — Social
 *
 * Muestra tres secciones para el usuario autenticado:
 *  1. Autores que sigue
 *  2. Usuarios que sigue
 *  3. Usuarios que le siguen (sus seguidores)
 *
 * Cada sección permite carga incremental (+8) mediante el patrón loadMore.
 */

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\On;
use Livewire\Component;

new #[Layout('layouts.app')] #[Title('Social')] class extends Component {
    #[On('follow-updated')]
    public function handleFollowUpdated()
    {
        // Fuerza el re-render de la vista para actualizar las listas y contadores sociales
    }

    /** Límite actual de autores seguidos visibles */
    public int $authorsLimit = 8;

    /** Límite actual de usuarios seguidos visibles */
    public int $followingLimit = 8;

    /** Límite actual de seguidores visibles */
    public int $followersLimit = 8;

    /**
     * Redirige al login si el usuario no está autenticado.
     */
    public function mount(): void
    {
        if (auth()->guest()) {
            $this->redirectRoute('login', navigate: true);
        }
    }

    /**
     * Incrementa el límite de la sección indicada (+8 por carga).
     *
     * @param string $section  'authors' | 'following' | 'followers'
     */
    public function loadMore(string $section): void
    {
        match ($section) {
            'authors'   => $this->authorsLimit   += 8,
            'following' => $this->followingLimit += 8,
            'followers' => $this->followersLimit += 8,
            default     => null,
        };
    }

    /**
     * Construye y devuelve las variables necesarias para la vista.
     */
    public function with(): array
    {
        $user = auth()->user();

        // ── Sección 1: Autores seguidos ────────────────────────────────────
        $followedAuthors = $user->followedAuthors()
            ->withCount('books')
            ->latest('author_followers.created_at')
            ->take($this->authorsLimit)
            ->get();

        $totalAuthors   = $user->followedAuthors()->count();
        $hasMoreAuthors = $totalAuthors > $this->authorsLimit;

        // ── Sección 2: Usuarios que sigo ───────────────────────────────────
        // following() devuelve registros Follow; se eager-load el User seguido
        // con sus conteos para no generar N+1 queries.
        $followingRecords = $user->following()
            ->with(['followed' => fn ($q) => $q->withCount(['followers', 'books'])])
            ->latest()
            ->take($this->followingLimit)
            ->get();

        $followingUsers   = $followingRecords->pluck('followed')->filter()->values();
        $totalFollowing   = $user->following()->count();
        $hasMoreFollowing = $totalFollowing > $this->followingLimit;

        // ── Sección 3: Usuarios que me siguen ─────────────────────────────
        $followersRecords = $user->followers()
            ->with(['follower' => fn ($q) => $q->withCount(['followers', 'books'])])
            ->latest()
            ->take($this->followersLimit)
            ->get();

        $followerUsers    = $followersRecords->pluck('follower')->filter()->values();
        $totalFollowers   = $user->followers()->count();
        $hasMoreFollowers = $totalFollowers > $this->followersLimit;

        return [
            'followedAuthors'  => $followedAuthors,
            'totalAuthors'     => $totalAuthors,
            'hasMoreAuthors'   => $hasMoreAuthors,

            'followingUsers'   => $followingUsers,
            'totalFollowing'   => $totalFollowing,
            'hasMoreFollowing' => $hasMoreFollowing,

            'followerUsers'    => $followerUsers,
            'totalFollowers'   => $totalFollowers,
            'hasMoreFollowers' => $hasMoreFollowers,
        ];
    }
}; ?>

<div>
    <div class="flex flex-col lg:flex-row gap-8" x-data>
        @include('livewire.pages.dashboard.partials.sidebar')

        <div class="flex-1">

            {{-- ── Cabecera principal ──────────────────────────────────────── --}}
            <header class="mb-8 border-b-4 border-black pb-4">
                <h1 class="text-3xl font-black uppercase font-display">Social</h1>
                <p class="text-gray-600 font-bold mt-1">Tus conexiones con autores y otros lectores</p>
            </header>

            {{-- ═══════════════════════════════════════════════════════════════ --}}
            {{-- SECCIÓN 1 — AUTORES QUE SIGO                                   --}}
            {{-- ═══════════════════════════════════════════════════════════════ --}}
            <section class="mb-12">
                <h2 class="text-xl font-black uppercase mb-4 flex items-center gap-2 border-b-2 border-black pb-2">
                    <span class="w-3 h-3 bg-brand-blue border-2 border-black block"></span>
                    Autores que sigo
                    <span class="text-sm font-bold text-gray-500 normal-case ml-1">({{ $totalAuthors }})</span>
                </h2>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @forelse($followedAuthors as $author)
                        <div wire:key="author-{{ $author->id }}">
                            <x-author-card :author="$author" />
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12 border-2 border-dashed border-black bg-gray-50">
                            <p class="font-bold text-gray-500 uppercase">Aún no sigues a ningún autor.</p>
                            <a href="{{ route('authors.index') }}" wire:navigate
                               class="mt-4 inline-block text-brand-blue underline font-bold">
                                Explorar autores
                            </a>
                        </div>
                    @endforelse
                </div>

                {{-- Botón cargar más autores --}}
                @if($hasMoreAuthors)
                    <div class="mt-6 flex justify-center">
                        <button wire:click="loadMore('authors')" wire:loading.attr="disabled"
                                class="neo-btn-secondary px-8 py-3 uppercase font-black">
                            <span wire:loading wire:target="loadMore('authors')"
                                  class="inline-block w-4 h-4 border-2 border-black border-t-transparent rounded-full animate-spin mr-2"></span>
                            Cargar más autores
                        </button>
                    </div>
                @endif
            </section>

            {{-- ═══════════════════════════════════════════════════════════════ --}}
            {{-- SECCIÓN 2 — USUARIOS QUE SIGO                                  --}}
            {{-- ═══════════════════════════════════════════════════════════════ --}}
            <section class="mb-12">
                <h2 class="text-xl font-black uppercase mb-4 flex items-center gap-2 border-b-2 border-black pb-2">
                    <span class="w-3 h-3 bg-brand-yellow border-2 border-black block"></span>
                    Usuarios que sigo
                    <span class="text-sm font-bold text-gray-500 normal-case ml-1">({{ $totalFollowing }})</span>
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($followingUsers as $user)
                        <div wire:key="following-{{ $user->id }}">
                            <x-user-card
                                :user="$user"
                                statLabel="seguidores"
                                :statValue="$user->followers_count"
                            />
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12 border-2 border-dashed border-black bg-gray-50">
                            <p class="font-bold text-gray-500 uppercase">Aún no sigues a ningún usuario.</p>
                            <a href="{{ route('community.index') }}" wire:navigate
                               class="mt-4 inline-block text-brand-blue underline font-bold">
                                Explorar la comunidad
                            </a>
                        </div>
                    @endforelse
                </div>

                {{-- Botón cargar más usuarios seguidos --}}
                @if($hasMoreFollowing)
                    <div class="mt-6 flex justify-center">
                        <button wire:click="loadMore('following')" wire:loading.attr="disabled"
                                class="neo-btn-secondary px-8 py-3 uppercase font-black">
                            <span wire:loading wire:target="loadMore('following')"
                                  class="inline-block w-4 h-4 border-2 border-black border-t-transparent rounded-full animate-spin mr-2"></span>
                            Cargar más usuarios
                        </button>
                    </div>
                @endif
            </section>

            {{-- ═══════════════════════════════════════════════════════════════ --}}
            {{-- SECCIÓN 3 — USUARIOS QUE ME SIGUEN                             --}}
            {{-- ═══════════════════════════════════════════════════════════════ --}}
            <section>
                <h2 class="text-xl font-black uppercase mb-4 flex items-center gap-2 border-b-2 border-black pb-2">
                    <span class="w-3 h-3 bg-black border-2 border-black block"></span>
                    Usuarios que me siguen
                    <span class="text-sm font-bold text-gray-500 normal-case ml-1">({{ $totalFollowers }})</span>
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($followerUsers as $user)
                        <div wire:key="follower-{{ $user->id }}">
                            <x-user-card
                                :user="$user"
                                statLabel="libros"
                                :statValue="$user->books_count"
                            />
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12 border-2 border-dashed border-black bg-gray-50">
                            <p class="font-bold text-gray-500 uppercase">Aún nadie te sigue.</p>
                            <a href="{{ route('community.index') }}" wire:navigate
                               class="mt-4 inline-block text-brand-blue underline font-bold">
                                Descubrir lectores
                            </a>
                        </div>
                    @endforelse
                </div>

                {{-- Botón cargar más seguidores --}}
                @if($hasMoreFollowers)
                    <div class="mt-6 flex justify-center">
                        <button wire:click="loadMore('followers')" wire:loading.attr="disabled"
                                class="neo-btn-secondary px-8 py-3 uppercase font-black">
                            <span wire:loading wire:target="loadMore('followers')"
                                  class="inline-block w-4 h-4 border-2 border-black border-t-transparent rounded-full animate-spin mr-2"></span>
                            Cargar más seguidores
                        </button>
                    </div>
                @endif
            </section>

        </div>
    </div>
</div>
