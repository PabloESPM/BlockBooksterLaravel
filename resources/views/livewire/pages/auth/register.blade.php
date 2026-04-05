<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\User;
use App\Models\Country;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

new #[Layout('layouts.auth')] #[Title('Registrarse')] class extends Component {
    public $name = '';
    public $email = '';
    public $email_confirmation = '';
    public $password = '';
    public $date_of_birth = '';
    public $gender = '';
    public $country_id = '';
    public $telephone = '';

    public function register()
    {
        $validatedData = $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users|confirmed',
            'password' => 'required|string|min:8',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:Male,Female,Other',
            'country_id' => 'required|exists:countries,id',
            'telephone' => 'required|string|max:20',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'date_of_birth' => $validatedData['date_of_birth'],
            'gender' => $validatedData['gender'],
            'country_id' => $validatedData['country_id'],
            'telephone' => $validatedData['telephone'],
            'type' => 'user',
            'avatar' => null,
        ]);

        Auth::login($user);

        return $this->redirect(route('home'), navigate: true);
    }

    public function with()
    {
        return [
            'countries' => Country::all(),
        ];
    }
}; ?>

<div>
    <div class="mb-6 text-center">
        <h1 class="text-2xl font-black uppercase mb-2">Únete a BlockBookster</h1>
        <p class="text-xs font-bold uppercase tracking-widest text-gray-500">Crea tu cuenta</p>
    </div>

    <form wire:submit="register" class="space-y-4">
        <!-- Nombre Completo -->
        <div>
            <label for="name" class="block text-xs font-bold uppercase mb-2">Nombre Completo</label>
            <input wire:model="name" id="name" type="text" class="neo-input" placeholder="Jane Doe" required autofocus>
            @error('name') <span class="text-red-600 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Correo Electrónico -->
            <div>
                <label for="email" class="block text-xs font-bold uppercase mb-2">Correo Electrónico</label>
                <input wire:model="email" id="email" type="email" class="neo-input" placeholder="jane@ejemplo.com" required>
                @error('email') <span class="text-red-600 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
            </div>
            <!-- Repetir Correo -->
            <div>
                <label for="email_confirmation" class="block text-xs font-bold uppercase mb-2">Repetir Correo</label>
                <input wire:model="email_confirmation" id="email_confirmation" type="email" class="neo-input" placeholder="jane@ejemplo.com" required>
            </div>
        </div>

        <div>
            <label for="password" class="block text-xs font-bold uppercase mb-2">Contraseña</label>
            <input wire:model="password" id="password" type="password" class="neo-input" placeholder="••••••••" required>
            @error('password') <span class="text-red-600 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Fecha de Nacimiento -->
            <div>
                <label for="date_of_birth" class="block text-xs font-bold uppercase mb-2">Fecha de Nacimiento</label>
                <input wire:model="date_of_birth" id="date_of_birth" type="date" class="neo-input" required>
                @error('date_of_birth') <span class="text-red-600 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
            </div>
            <!-- Género -->
            <div>
                <label for="gender" class="block text-xs font-bold uppercase mb-2">Género</label>
                <select wire:model="gender" id="gender" class="neo-input bg-white appearance-none" required>
                    <option value="" disabled selected>Selecciona Género</option>
                    <option value="Male">Masculino</option>
                    <option value="Female">Femenino</option>
                    <option value="Other">Otro</option>
                </select>
                @error('gender') <span class="text-red-600 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- País y Teléfono -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-2">
                <label for="country_id" class="block text-xs font-bold uppercase mb-2">País</label>
                <select wire:model="country_id" id="country_id" class="neo-input bg-white appearance-none" required>
                    <option value="" disabled selected>Selecciona País</option>
                    @foreach($countries as $country)
                        <option value="{{ $country->id }}">
                            {{ $country->name }} (+{{ $country->phone_code }})
                        </option>
                    @endforeach
                </select>
                @error('country_id') <span class="text-red-600 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="telephone" class="block text-xs font-bold uppercase mb-2">Teléfono</label>
                <input wire:model="telephone" id="telephone" type="tel" class="neo-input" placeholder="123456789" required>
                @error('telephone') <span class="text-red-600 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <button type="submit" class="w-full neo-btn-primary mt-6" wire:loading.attr="disabled">
            <span wire:loading.remove>Registrarse</span>
            <span wire:loading>Creando cuenta...</span>
        </button>
    </form>

    <x-slot:footer>
        <span class="text-gray-600">¿Ya tienes una cuenta?</span>
        <a href="{{ route('login') }}" wire:navigate
           class="ml-1 text-black font-black uppercase hover:text-brand-blue hover:underline">Iniciar Sesión</a>
    </x-slot:footer>
</div>
