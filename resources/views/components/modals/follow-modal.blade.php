@props([
    'followableId',         // e.g. $user->id or $author->id
    'followableType',       // 'user' or 'author'
    'isFollowing' => false, // pass auth()->user()->isFollowing($target)
    'followUrl',            // route to POST follow
    'unfollowUrl' => null,  // route to DELETE/POST unfollow (defaults to followUrl)
    'class' => 'neo-btn-primary',
])

@auth
    <div
        x-data="{
            following: {{ $isFollowing ? 'true' : 'false' }},
            loading: false,
            toggle() {
                this.loading = true;
                const url = this.following
                    ? '{{ $unfollowUrl ?? $followUrl }}'
                    : '{{ $followUrl }}';
                const method = this.following ? 'DELETE' : 'POST';

                fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json',
                    }
                })
                .then(r => r.json())
                .then(data => {
                    this.following = data.following;
                    this.loading = false;
                })
                .catch(() => { this.loading = false; });
            }
        }">
        <button
            @click="toggle()"
            :disabled="loading"
            class="{{ $class }} flex items-center gap-2 transition-all"
            :class="{
                'opacity-60 cursor-wait': loading,
                'bg-gray-200 !border-gray-400 !shadow-none': following
            }">
            <svg x-show="loading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24" style="display:none">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
            <span x-text="following ? 'Unfollow' : '+ Follow'"></span>
        </button>
    </div>
@endauth
