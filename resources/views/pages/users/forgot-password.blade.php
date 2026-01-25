@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
    <div class="mb-6 text-center">
        <h1 class="text-2xl font-black uppercase mb-2">Forgot Password?</h1>
        <p class="text-xs font-bold uppercase tracking-widest text-gray-500">We'll email you a recovery link</p>
    </div>

    @if (session('status'))
        <div
            class="mb-4 text-xs font-bold text-green-600 uppercase border-2 border-green-600 p-2 bg-green-50 shadow-[2px_2px_0px_#166534]">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <div>
            <label for="email" class="block text-xs font-bold uppercase mb-2">Email Address</label>
            <input id="email" type="email" name="email" class="neo-input" placeholder="john.doe@example.com"
                value="{{ old('email') }}" required autofocus>
            @error('email') <span class="text-red-600 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="w-full neo-btn-primary">
            Email Password Reset Link
        </button>
    </form>
@endsection

@section('footer-link')
    <a href="{{ route('login') }}" class="text-black font-black uppercase hover:text-brand-blue hover:underline text-sm">
        < Back to Login</a>
@endsection