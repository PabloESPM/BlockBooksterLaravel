<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;

new #[Layout('layouts.admin')] #[Title('Gestión de Usuarios')] class extends Component {
    use WithPagination;

    public $search = '';
    public $sortColumn = 'created_at';
    public $sortDirection = 'desc';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($column)
    {
        // Ciclo de ordenación:
        // 1. Si cambiamos a una nueva columna -> 'asc'
        // 2. Si pulsamos la misma columna y es 'asc' -> 'desc'
        // 3. Si pulsamos la misma columna y es 'desc' -> ordenar por 'created_at' 'asc' (antiguo)
        // 4. Si era 'created_at' 'asc' -> volver al defecto 'created_at' 'desc' (nuevo a viejo)

        if ($this->sortColumn === $column) {
            if ($this->sortDirection === 'asc') {
                $this->sortDirection = 'desc';
            } elseif ($this->sortDirection === 'desc' && $column !== 'created_at') {
                $this->sortColumn = 'created_at';
                $this->sortDirection = 'asc';
            } elseif ($this->sortDirection === 'desc' && $column === 'created_at') {
                $this->sortDirection = 'asc';
            }
        } else {
            // Nueva columna, empezamos por 'asc'
            $this->sortColumn = $column;
            $this->sortDirection = 'asc';
        }
    }

    #[On('deleteUser')]
    public function deleteUser($id = null)
    {
        if (!$id) {
            return;
        }

        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            session()->flash('error', 'No puedes eliminar tu propia cuenta desde aquí.');
            return;
        }

        $user->delete();

        session()->flash('success', 'Usuario eliminado correctamente.');

        $this->resetPage();
    }


    public function with()
    {
        $query = User::with('country')
            ->where(function ($query) {
                $query->whereLikeAccentInsensitive('users.name', $this->search)
                      ->orWhereLikeAccentInsensitive('users.email', $this->search)
                      ->orWhereLikeAccentInsensitive('users.type', $this->search)
                      ->orWhereHas('country', function ($countryQuery) {
                          $countryQuery->whereLikeAccentInsensitive('name', $this->search);
                      });
            });

        // Aplicar ordenación
        if ($this->sortColumn === 'country_name') {
            // Ordenar por relación (nacionalidad)
            $query->leftJoin('countries', 'users.country_id', '=', 'countries.id')
                  ->select('users.*')
                  ->orderBy('countries.name', $this->sortDirection);
        } else {
            // Ordenar por columnas de la tabla users
            $query->orderBy($this->sortColumn, $this->sortDirection);
        }

        return [
            'users' => $query->paginate(10)
        ];
    }
};
?>

<div>
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-4xl font-black uppercase font-display">Usuarios</h1>
    </div>

    @if(session('success'))
    <div class="mb-4 p-4 bg-green-100 border-2 border-green-600 text-green-700 font-bold uppercase text-sm">
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-4 p-4 bg-red-100 border-2 border-red-600 text-red-700 font-bold uppercase text-sm">
        {{ session('error') }}
    </div>
    @endif

    <!-- Filtros Búsqueda -->
    <div class="bg-white border-2 border-black p-4 mb-8 flex gap-4">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar usuario, email, rol, nacionalidad..."
            class="neo-input flex-1 bg-white">
    </div>

    <div class="bg-white border-2 border-black overflow-hidden mb-8">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-black text-white text-xs font-bold uppercase tracking-wider select-none">
                    <th class="p-4 border-b border-gray-800 cursor-pointer hover:bg-gray-900 group"
                        wire:click="sortBy('name')">
                        <div class="flex items-center gap-1">Usuario
                            @if($sortColumn === 'name')
                            <span>{!! $sortDirection === 'asc' ? '↑' : '↓' !!}</span>
                            @else
                            <span class="opacity-0 group-hover:opacity-50">↕</span>
                            @endif
                        </div>
                    </th>
                    <th class="p-4 border-b border-gray-800 cursor-pointer hover:bg-gray-900 group"
                        wire:click="sortBy('email')">
                        <div class="flex items-center gap-1">Email
                            @if($sortColumn === 'email')
                            <span>{!! $sortDirection === 'asc' ? '↑' : '↓' !!}</span>
                            @else
                            <span class="opacity-0 group-hover:opacity-50">↕</span>
                            @endif
                        </div>
                    </th>
                    <th class="p-4 border-b border-gray-800 cursor-pointer hover:bg-gray-900 group"
                        wire:click="sortBy('created_at')">
                        <div class="flex items-center gap-1">Registrado
                            @if($sortColumn === 'created_at')
                            <span>{!! $sortDirection === 'asc' ? '↑' : '↓' !!}</span>
                            @else
                            <span class="opacity-0 group-hover:opacity-50">↕</span>
                            @endif
                        </div>
                    </th>
                    <th class="p-4 border-b border-gray-800 cursor-pointer hover:bg-gray-900 group"
                        wire:click="sortBy('country_name')">
                        <div class="flex items-center gap-1">Nacionalidad
                            @if($sortColumn === 'country_name')
                            <span>{!! $sortDirection === 'asc' ? '↑' : '↓' !!}</span>
                            @else
                            <span class="opacity-0 group-hover:opacity-50">↕</span>
                            @endif
                        </div>
                    </th>
                    <th class="p-4 border-b border-gray-800 cursor-pointer hover:bg-gray-900 group"
                        wire:click="sortBy('type')">
                        <div class="flex items-center gap-1">Permisos
                            @if($sortColumn === 'type')
                            <span>{!! $sortDirection === 'asc' ? '↑' : '↓' !!}</span>
                            @else
                            <span class="opacity-0 group-hover:opacity-50">↕</span>
                            @endif
                        </div>
                    </th>
                    <th class="p-4 border-b border-gray-800 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-black/10">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50 transition-colors" wire:key="user-{{ $user->id }}">
                    <td class="p-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-8 h-8 bg-gray-200 rounded-full border border-black overflow-hidden flex-shrink-0">
                                <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=random' }}"
                                    class="w-full h-full object-cover">
                            </div>
                            <span class="font-bold">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td class="p-4 font-mono text-xs">{{ $user->email }}</td>
                    <td class="p-4 text-sm font-bold text-gray-500">{{ $user->created_at->format('M d, Y') }}</td>
                    <td class="p-4 text-sm font-medium">
                        {{ optional($user->country)->name ?? 'No especificada' }}
                    </td>
                    <td class="p-4">
                        @if($user->type === 'admin')
                        <span
                            class="text-xs font-bold uppercase bg-purple-100 text-purple-800 px-2 py-1 border border-purple-200">Administrador</span>
                        @elseif($user->type === 'worker')
                        <span
                            class="text-xs font-bold uppercase bg-blue-100 text-blue-800 px-2 py-1 border border-blue-200">Trabajador</span>
                        @else
                        <span
                            class="text-xs font-bold uppercase bg-gray-100 text-gray-800 px-2 py-1 border border-gray-200">Usuario</span>
                        @endif
                    </td>
                    <td class="p-4 text-right">
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('admin.users.show', $user->id) }}" wire:navigate
                                class="text-xs font-black uppercase text-brand-blue hover:underline">
                                Editar
                            </a>
                            <button
                                @click="$dispatch('open-delete-modal', { action: 'deleteUser', params: {{ $user->id }}, title: 'Eliminar Usuario', message: '¿Estás seguro de que deseas eliminar permanentemente a este usuario? Esta acción no se puede deshacer.' })"
                                class="text-xs font-black uppercase text-red-600 hover:underline">
                                Eliminar
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-8 text-center text-gray-500 font-bold uppercase">
                        No se encontraron usuarios.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación usando Componente Livewire Neo-Brutalista -->
    <div class="mt-4">
        {{ $users->links('livewire.components.modals.pagination') }}
    </div>

    <!-- Modal Neo-Brutalista de Eliminación -->
    @include('livewire.components.modals.delete-modal')
</div>
