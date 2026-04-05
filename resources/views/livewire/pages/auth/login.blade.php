<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

new #[Layout('layouts.auth')] #[Title('Iniciar Sesión')] class extends Component {
    public $email = '';
    public $password = '';

    public function login()
    {
        $credentials = $this->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            session()->regenerate();

            // Intercepción de moderación
            if (Auth::user()->is_blocked) {
                Auth::logout();
                session()->invalidate();
                session()->regenerateToken();

                throw ValidationException::withMessages([
                    'email' => 'Tu cuenta ha sido bloqueada. Por favor, contacta con soporte para obtener más información.',
                ]);
            }

            // Redirige al panel de administración si es admin o trabajador, de lo contrario a inicio
            if (Auth::user()->type === 'admin' || Auth::user()->type === 'worker') {
                return $this->redirectIntended(route('admin.dashboard'), navigate: true);
            }

            return $this->redirectIntended(route('home'), navigate: true);
        }

        throw ValidationException::withMessages([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ]);
    }
}; ?>

<div>
    <div class="mb-6 text-center">
        <h1 class="text-2xl font-black uppercase mb-2">Bienvenido de Nuevo</h1>
        <p class="text-xs font-bold uppercase tracking-widest text-gray-500">Ingresa tus credenciales</p>
    </div>

    <!-- Inicio de Sesión Social -->
    <div class="space-y-3 mb-8">
        <button class="w-full neo-btn-secondary flex items-center justify-center gap-3">
            <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-5 h-5" alt="Google">
            Continuar con Google
        </button>
        <button class="w-full neo-btn-secondary flex items-center justify-center gap-3">
            <img src="https://www.svgrepo.com/show/452062/microsoft.svg" class="w-5 h-5" alt="Microsoft">
            Continuar con Microsoft
        </button>
        <button class="w-full neo-btn-secondary flex items-center justify-center gap-3">
            <img src="https://www.svgrepo.com/show/511330/apple-173.svg" class="w-5 h-5" alt="Apple">
            Continuar con Apple
        </button>
    </div>

    <div class="relative flex items-center py-5">
        <div class="flex-grow border-t-2 border-black"></div>
        <span class="flex-shrink-0 mx-4 text-xs font-black uppercase">O iniciar sesión con correo</span>
        <div class="flex-grow border-t-2 border-black"></div>
    </div>

    <form wire:submit="login" class="space-y-6">
        <div>
            <label for="email" class="block text-xs font-bold uppercase mb-2">Correo Electrónico</label>
            <input wire:model="email" id="email" type="email" class="neo-input" placeholder="juan.perez@ejemplo.com" required
                   autofocus>
            @error('email') <span class="text-red-600 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <div class="flex justify-between items-center mb-2">
                <label for="password" class="block text-xs font-bold uppercase">Contraseña</label>
                <a href="{{ route('password.request') }}" wire:navigate
                   class="text-xs font-bold uppercase text-brand-blue hover:underline">¿Olvidaste tu contraseña?</a>
            </div>
            <input wire:model="password" id="password" type="password" class="neo-input" placeholder="••••••••" required>
            @error('password') <span class="text-red-600 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="w-full neo-btn-primary" wire:loading.attr="disabled">
            <span wire:loading.remove>Iniciar Sesión</span>
            <span wire:loading>Iniciando sesión...</span>
        </button>
    </form>

    <x-slot:footer>
        <span class="text-gray-600">¿Nuevo en BlockBookster?</span>
        <a href="{{ route('register') }}" wire:navigate
           class="ml-1 text-black font-black uppercase hover:text-brand-blue hover:underline">Crear Cuenta</a>
    </x-slot:footer>
</div>
