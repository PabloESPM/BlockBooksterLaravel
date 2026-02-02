@extends('layouts.auth')

@section('title', 'Register')

@section('content')
    <div class="mb-6 text-center">
        <h1 class="text-2xl font-black uppercase mb-2">Join BlockBookster</h1>
        <p class="text-xs font-bold uppercase tracking-widest text-gray-500">Create your account</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-xs font-bold uppercase mb-2">Full Name</label>
            <input id="name" type="text" name="name" class="neo-input" placeholder="Jane Doe" value="{{ old('name') }}"
                required autofocus>
            @error('name') <span class="text-red-600 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-bold uppercase mb-2">Email Address</label>
                <input id="email" type="email" name="email" class="neo-input" placeholder="jane@example.com"
                    value="{{ old('email') }}" required>
                @error('email') <span class="text-red-600 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
            </div>
            <!-- Repeat Email -->
            <div>
                <label for="email_confirmation" class="block text-xs font-bold uppercase mb-2">Repeat Email</label>
                <input id="email_confirmation" type="email" name="email_confirmation" class="neo-input"
                    placeholder="jane@example.com" required>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Password -->
            <div class="md:col-span-2">
                <label for="password" class="block text-xs font-bold uppercase mb-2">Password</label>
                <input id="password" type="password" name="password" class="neo-input" placeholder="••••••••" required>
                @error('password') <span class="text-red-600 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Date of Birth -->
            <div>
                <label for="date_of_birth" class="block text-xs font-bold uppercase mb-2">Date of Birth</label>
                <input id="date_of_birth" type="date" name="date_of_birth" class="neo-input"
                    value="{{ old('date_of_birth') }}" required>
                @error('date_of_birth') <span class="text-red-600 text-xs font-bold mt-1 block">{{ $message }}</span>
                @enderror
            </div>
            <!-- Gender -->
            <div>
                <label for="gender" class="block text-xs font-bold uppercase mb-2">Gender</label>
                <select id="gender" name="gender" class="neo-input bg-white appearance-none" required>
                    <option value="" disabled selected>Select Gender</option>
                    <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                    <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
        </div>

        <!-- Country & Phone -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-2">
                <label for="country_id" class="block text-xs font-bold uppercase mb-2">Country</label>
                <select id="country_id" name="country_id" class="neo-input bg-white appearance-none" required>
                    <option value="" disabled selected>Select Country</option>
                    <!-- Expecting $countries variable populated from DB -->
                    @if(isset($countries))
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}" data-phone-code="{{ $country->phone_code }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>
                                {{ $country->name }} (+{{ $country->phone_code }})
                            </option>
                        @endforeach
                    @else
                        <option value="1">Spain (+34)</option>
                        <option value="2">United States (+1)</option>
                        <option value="3">United Kingdom (+44)</option>
                    @endif
                </select>
                @error('country_id') <span class="text-red-600 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="telephone" class="block text-xs font-bold uppercase mb-2">Phone</label>
                <input id="telephone" type="tel" name="telephone" class="neo-input" placeholder="123456789"
                    value="{{ old('telephone') }}" required>
            </div>
        </div>

        <button type="submit" class="w-full neo-btn-primary mt-6">
            Register
        </button>
    </form>
@endsection

@section('footer-link')
    <span class="text-gray-600">Already have an account?</span>
    <a href="{{ route('login') }}" class="ml-1 text-black font-black uppercase hover:text-brand-blue hover:underline">Log
        In</a>
@endsection