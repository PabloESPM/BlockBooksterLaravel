<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;

new #[Layout('layouts.admin')] #[Title('Gestión de Usuarios')] class extends Component {
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function toggleBan($userId)
    {
        $user = User::find($userId);
        
        if ($user) {
            // NOTA: Se requiere agregar un campo 'is_banned' (boolean) a la tabla users 
            // a través de una migración para que esta funcionalidad persista correctamente.
            // Por ahora, simulamos el comportamiento si el campo no existe.
            if (in_array('is_banned', $user->getFillable()) || \Schema::hasColumn('users', 'is_banned')) {
                $user->is_banned = !$user->is_banned;
                $user->save();
            }
        }
    }

    public function with()
    {
        return [
            'users' => User::where('name', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%')
                ->orderBy('created_at', 'desc')
                ->paginate(10)
        ];
    }
};
?>

<div>
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-4xl font-black uppercase font-display">Usuarios</h1>
        <div class="flex gap-2">
            <!-- wire:model.live para búsqueda reactiva -->
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar usuario..." class="neo-input w-64 bg-white text-sm">
            <button class="neo-btn-secondary px-4 py-2 text-sm">Buscar</button>
        </div>
    </div>

    <div class="bg-white border-2 border-black overflow-hidden mb-8">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-black text-white text-xs font-bold uppercase tracking-wider">
                    <th class="p-4 border-b border-gray-800">Usuario</th>
                    <th class="p-4 border-b border-gray-800">Email</th>
                    <th class="p-4 border-b border-gray-800">Registrado</th>
                    <th class="p-4 border-b border-gray-800">Estado</th>
                    <th class="p-4 border-b border-gray-800 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-black/10">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition-colors" wire:key="user-{{ $user->id }}">
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gray-200 rounded-full border border-black overflow-hidden flex-shrink-0">
                                    <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=random' }}"
                                        class="w-full h-full object-cover">
                                </div>
                                <span class="font-bold">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="p-4 font-mono text-xs">{{ $user->email }}</td>
                        <td class="p-4 text-sm font-bold text-gray-500">{{ $user->created_at->format('M d, Y') }}</td>
                        <td class="p-4">
                            @if($user->is_banned)
                                <span class="text-xs font-bold uppercase bg-red-100 text-red-800 px-2 py-1 border border-red-200">Bloqueado</span>
                            @else
                                <span class="text-xs font-bold uppercase bg-green-100 text-green-800 px-2 py-1 border border-green-200">Activo</span>
                            @endif
                        </td>
                        <td class="p-4 text-right">
                            @if($user->is_banned)
                                <button wire:click="toggleBan({{ $user->id }})" class="text-xs font-black uppercase text-gray-500 hover:underline">Desbloquear Usuario</button>
                            @else
                                <button wire:click="toggleBan({{ $user->id }})" class="text-xs font-black uppercase text-red-600 hover:underline">Bloquear Usuario</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-gray-500 font-bold uppercase">
                            No se encontraron usuarios.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Paginación usando los estilos por defecto de Livewire o Tailwind -->
    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
