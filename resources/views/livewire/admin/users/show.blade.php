<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\User;
use App\Models\Country;
use Illuminate\Support\Facades\Hash;

new #[Layout('layouts.admin')] #[Title('Editar Usuario')] class extends Component {
    public User $user;
    
    // Propiedades del formulario
    public $name;
    public $email;
    public $telephone;
    public $country_id;
    public $type;
    public $password;
    public $password_confirmation;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->telephone = $user->telephone;
        $this->country_id = $user->country_id;
        $this->type = $user->type;
    }

    public function updateProfile()
    {
        // Solo administradores pueden cambiar el rol (type)
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->user->id,
            'telephone' => 'nullable|string|max:20',
            'country_id' => 'nullable|exists:countries,id',
        ];

        if (auth()->user()->type === 'admin') {
            $rules['type'] = 'required|in:admin,worker,user';
        }

        if ($this->password) {
            $rules['password'] = 'required|min:8|confirmed';
        }

        $validated = $this->validate($rules);

        $this->user->name = $this->name;
        $this->user->email = $this->email;
        $this->user->telephone = $this->telephone;
        $this->user->country_id = $this->country_id;
        
        if (auth()->user()->type === 'admin') {
            $this->user->type = $this->type;
        }

        if ($this->password) {
            $this->user->password = Hash::make($this->password);
        }

        $this->user->save();

        // Limpiar campos de contraseña por seguridad
        $this->password = '';
        $this->password_confirmation = '';

        session()->flash('success', '¡Perfil de usuario actualizado correctamente!');
    }

    #[On('deleteProfile')]
    public function deleteProfile($id = null)
    {
        // Seguridad: evitamos auto-eliminación
        if ($this->user->id === auth()->id()) {
            session()->flash('error', 'No puedes eliminar tu propia cuenta desde este panel.');
            return;
        }

        $this->user->delete();
        
        session()->flash('success', 'El usuario ha sido eliminado de la plataforma.');
        return $this->redirectRoute('admin.users.index', navigate: true);
    }

    public function with()
    {
        return [
            'countries' => Country::all()
        ];
    }
};
?>

<div>
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.users.index') }}" wire:navigate
           class="w-10 h-10 border-2 border-black flex items-center justify-center hover:bg-black hover:text-white transition-colors bg-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
            </svg>
        </a>
        <h1 class="text-3xl font-black uppercase font-display">
            Editar: <span class="text-brand-blue">{{ $user->name }}</span>
        </h1>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Contenido Principal -->
        <div class="flex-1 space-y-8">
            <!-- Datos Básicos y Permisos -->
            <div class="bg-white border-2 border-black p-6 shadow-[4px_4px_0px_#000]">
                <h3 class="font-black text-lg uppercase mb-6 flex items-center gap-2 border-b-2 border-black pb-2">
                    <span class="w-3 h-3 bg-brand-yellow border border-black block"></span>
                    Información General
                </h3>

                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-100 border-2 border-green-600 text-green-700 font-bold uppercase text-sm">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-100 border-2 border-red-600 text-red-700 font-bold uppercase text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                <form wire:submit="updateProfile" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Campo: Nombre Visible --}}
                        <div>
                            <label class="block text-xs font-bold uppercase mb-2">Nombre Visible</label>
                            <input type="text" wire:model="name" class="neo-input w-full">
                            @error('name') <p class="text-xs text-red-600 font-bold mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Campo: Correo Electrónico --}}
                        <div>
                            <label class="block text-xs font-bold uppercase mb-2">Correo Electrónico</label>
                            <input type="email" wire:model="email" class="neo-input w-full">
                            @error('email') <p class="text-xs text-red-600 font-bold mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Campo: Número de Teléfono --}}
                        <div>
                            <label class="block text-xs font-bold uppercase mb-2">Teléfono</label>
                            <input type="tel" wire:model="telephone" class="neo-input w-full" placeholder="+34 600 000 000">
                            @error('telephone') <p class="text-xs text-red-600 font-bold mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Campo: País --}}
                        <div>
                            <label class="block text-xs font-bold uppercase mb-2">Nacionalidad</label>
                            <select wire:model="country_id" class="neo-input w-full bg-white">
                                <option value="">— Sin especificar —</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                            @error('country_id') <p class="text-xs text-red-600 font-bold mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Gestión de Permisos (Solo Admins) --}}
                    @if(auth()->user()->type === 'admin')
                    <div class="pt-6 border-t-2 border-dashed border-gray-300">
                        <label class="block text-xs font-bold uppercase mb-2 text-brand-blue">Permisos de Cuenta</label>
                        <select wire:model="type" class="neo-input w-full max-w-md bg-white border-brand-blue border-2">
                            <option value="user">Usuario (Lectura/Comunidad)</option>
                            <option value="worker">Trabajador (Gestión de libros, sin cambiar roles)</option>
                            <option value="admin">Administrador (Acceso total)</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-2 italic">* Cuidado: Dar permisos de Administrador otorga control total sobre la plataforma.</p>
                        @error('type') <p class="text-xs text-red-600 font-bold mt-1">{{ $message }}</p> @enderror
                    </div>
                    @else
                    <div class="pt-6 border-t-2 border-dashed border-gray-300">
                        <label class="block text-xs font-bold uppercase mb-2 text-gray-500">Permisos de Cuenta</label>
                        <div class="px-4 py-2 border-2 border-gray-200 bg-gray-50 text-sm font-bold uppercase text-gray-600 inline-block">
                            {{ $type === 'admin' ? 'Administrador' : ($type === 'worker' ? 'Trabajador' : 'Usuario') }}
                        </div>
                        <p class="text-xs text-gray-500 mt-2 italic">* No tienes permisos suficientes para modificar el rol de este usuario.</p>
                    </div>
                    @endif

                    {{-- Cambio de Contraseña Forzado --}}
                    <div class="pt-6 border-t-2 border-black">
                        <h4 class="font-bold uppercase text-sm mb-4">Reestablecer Contraseña</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 p-4 border border-gray-200">
                            <div>
                                <label class="block text-xs font-bold uppercase mb-2">Nueva Contraseña</label>
                                <input type="password" wire:model="password" class="neo-input w-full bg-white" placeholder="Dejar en blanco para no cambiar">
                                @error('password') <p class="text-xs text-red-600 font-bold mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase mb-2">Confirmar Contraseña</label>
                                <input type="password" wire:model="password_confirmation" class="neo-input w-full bg-white">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" class="neo-btn-primary px-8 py-3 text-lg flex items-center gap-2">
                            <span wire:loading.remove wire:target="updateProfile">Guardar Cambios</span>
                            <span wire:loading wire:target="updateProfile">Guardando...</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Zona de Peligro -->
            <div class="border-2 border-red-600 p-6 bg-red-50 shadow-[4px_4px_0px_#dc2626]">
                <h3 class="font-black text-lg uppercase mb-4 text-red-600">Eliminación de Cuenta</h3>
                <p class="text-sm font-bold text-gray-800 mb-6">Al eliminar esta cuenta, se borrará toda su información asociada permanentemente. Esta acción no se puede deshacer.</p>
                <div class="flex justify-start">
                    <button @click="$dispatch('open-delete-modal', { action: 'deleteProfile', title: 'Eliminar Usuario', message: '¿Estás seguro de que deseas eliminar permanentemente a este usuario? Esta acción borrará todas sus listas, reviews y likes.' })"
                            class="bg-red-600 text-white font-black uppercase px-6 py-2 border-2 border-black shadow-[2px_2px_0px_#000] hover:translate-y-[-1px] hover:shadow-[4px_4px_0px_#000] transition-all">
                        Eliminar {{ $user->name }}
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Barra Lateral Informativa -->
        <div class="w-full lg:w-80 space-y-6">
            <div class="bg-white border-2 border-black p-6 shadow-[4px_4px_0px_#000] text-center">
                <div class="w-32 h-32 mx-auto bg-gray-200 rounded-full border-4 border-black mb-4 overflow-hidden">
                    <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=random' }}" class="w-full h-full object-cover">
                </div>
                <h3 class="font-black text-xl uppercase">{{ $user->name }}</h3>
                <p class="text-sm font-mono text-gray-500 mb-4">{{ $user->email }}</p>
                
                <div class="text-left border-t-2 border-gray-200 pt-4 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="font-bold text-gray-500">Registrado:</span>
                        <span class="font-medium">{{ $user->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="font-bold text-gray-500">Rol:</span>
                        <span class="font-medium uppercase">{{ $user->type }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Neo-Brutalista de Eliminación -->
    @include('livewire.components.modals.delete-modal')
</div>
