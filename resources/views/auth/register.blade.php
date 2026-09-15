<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-xl font-black text-gray-900">Create Account</h2>
        <p class="text-xs text-gray-400 mt-1">Get started with Patiala Property portal</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Role selection -->
        <div>
            <x-input-label for="role" :value="__('I am a')" />
            <select id="role" name="role" class="block mt-1 w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm text-sm" required>
                <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>Buyer / Tenant</option>
                <option value="owner" {{ old('role') === 'owner' ? 'selected' : '' }}>Property Owner</option>
                <option value="agent" {{ old('role') === 'agent' ? 'selected' : '' }}>Real Estate Agent</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 rounded-lg text-sm shadow transition-colors">
            {{ __('Register') }}
        </button>
    </form>

    <div class="relative flex items-center justify-center my-6 border-b">
        <span class="absolute bg-white px-3 text-xs text-gray-400 font-bold uppercase tracking-wider">Or</span>
    </div>

    <!-- Alternatives -->
    <div class="space-y-2">
        <a href="{{ route('otp.login.form') }}" class="w-full text-center border border-gray-200 hover:bg-gray-50 text-gray-700 font-bold py-2.5 rounded-lg text-sm transition-colors flex items-center justify-center space-x-2">
            <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            <span>Register with Mobile (OTP)</span>
        </a>
        <a href="{{ route('auth.google') }}" class="w-full text-center border border-gray-200 hover:bg-gray-50 text-gray-700 font-bold py-2.5 rounded-lg text-sm transition-colors flex items-center justify-center space-x-2">
            <span>Continue with Google</span>
        </a>
    </div>

    <div class="text-center text-xs text-gray-400 mt-6">
        Already registered? <a href="{{ route('login') }}" class="text-red-600 font-bold hover:underline">Log In</a>
    </div>
</x-guest-layout>
