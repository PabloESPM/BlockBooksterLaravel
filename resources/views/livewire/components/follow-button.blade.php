<?php

use Livewire\Component;
use Illuminate\Database\Eloquent\Model;

new class extends Component {
    public Model $model;
    public string $type; // 'user', 'author', 'list'
    public bool $isFollowing = false;

    public function mount(Model $model, string $type)
    {
        $this->model = $model;
        $this->type = $type;
        $this->checkFollowing();
    }

    public function toggle()
    {
        if (auth()->guest()) {
            return $this->redirectRoute('login', navigate: true);
        }

        $user = auth()->user();

        if ($this->type === 'user') {
            if ($user->isFollowing($this->model)) {
                $user->unfollow($this->model);
                $this->isFollowing = false;
            } else {
                $user->follow($this->model);
                $this->isFollowing = true;
            }
        } elseif ($this->type === 'author') {
            if ($user->isFollowingAuthor($this->model)) {
                $user->unfollowAuthor($this->model);
                $this->isFollowing = false;
            } else {
                $user->followAuthor($this->model);
                $this->isFollowing = true;
            }
        } elseif ($this->type === 'list') {
            if ($user->isFollowingList($this->model)) {
                $user->unfollowList($this->model);
                $this->isFollowing = false;
            } else {
                $user->followList($this->model);
                $this->isFollowing = true;
            }
        }
        
        $this->dispatch('follow-updated', type: $this->type, id: $this->model->id, following: $this->isFollowing);
    }

    protected function checkFollowing()
    {
        if (auth()->guest()) {
            $this->isFollowing = false;
            return;
        }

        $user = auth()->user();

        if ($this->type === 'user') {
            $this->isFollowing = $user->isFollowing($this->model);
        } elseif ($this->type === 'author') {
            $this->isFollowing = $user->isFollowingAuthor($this->model);
        } elseif ($this->type === 'list') {
            $this->isFollowing = $user->isFollowingList($this->model);
        }
    }
}; ?>

<button
    wire:click="toggle"
    wire:loading.attr="disabled"
    class="neo-btn-primary text-sm px-4 py-2 flex items-center justify-center gap-2 transition-all {{ $isFollowing ? 'bg-gray-200 !border-gray-400 !shadow-none' : '' }}"
>
    <div wire:loading class="w-4 h-4 border-2 border-black border-t-transparent rounded-full animate-spin"></div>
    <span wire:loading.remove>{{ $isFollowing ? 'Dejar de seguir' : 'Seguir' }}</span>
    <span wire:loading>Cargando...</span>
</button>
