@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <div class="mb-6 text-center">
        <h1 class="text-2xl font-black uppercase mb-2">Welcome Back</h1>
        <p class="text-xs font-bold uppercase tracking-widest text-gray-500">Enter your credentials</p>
    </div>

    <!-- Social Login -->
    <div class="space-y-3 mb-8">
        <button class="w-full neo-btn-secondary flex items-center justify-center gap-3">
            <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-5 h-5" alt="Google">
            Continue with Google
        </button>
        <button class="w-full neo-btn-secondary flex items-center justify-center gap-3">
            <img src="https://www.svgrepo.com/show/452062/microsoft.svg" class="w-5 h-5" alt="Microsoft">
            Continue with Microsoft
        </button>
        <button class="w-full neo-btn-secondary flex items-center justify-center gap-3">
            <img src="https://www.svgrepo.com/show/511330/apple-173.svg" class="w-5 h-5" alt="Apple">
            Continue with Apple
        </button>
    </div>

    <div class="relative flex items-center py-5">
        <div class="flex-grow border-t-2 border-black"></div>
        <span class="flex-shrink-0 mx-4 text-xs font-black uppercase">Or login with email</span>
        <div class="flex-grow border-t-2 border-black"></div>
    </div>

    <form method="POST" action="login" class="space-y-6">
        @csrf

        <div>
            <label for="email" class="block text-xs font-bold uppercase mb-2">Email Address</label>
            <input id="email" type="email" name="email" class="neo-input" placeholder="john.doe@example.com" required
                autofocus>
        </div>

        <div>
            <div class="flex justify-between items-center mb-2">
                <label for="password" class="block text-xs font-bold uppercase">Password</label>
                <a href="{{ route('password.request') }}"
                    class="text-xs font-bold uppercase text-brand-blue hover:underline">Forgot password?</a>
            </div>
            <input id="password" type="password" name="password" class="neo-input" placeholder="••••••••" required>
        </div>

        <button type="submit" class="w-full neo-btn-primary">
            Log In
        </button>
    </form>
@endsection

@section('footer-link')
    <span class="text-gray-600">New to BlockBookster?</span>
    <a href="{{ route('register') }}"
        class="ml-1 text-black font-black uppercase hover:text-brand-blue hover:underline">Create Account</a>
@endsection
