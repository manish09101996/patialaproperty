<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="text-center mb-6">
        <h2 class="text-xl font-black text-gray-900">Sign In</h2>
        <p class="text-xs text-gray-400 mt-1">Access your Patiala Property dashboard</p>
    </div>

    <!-- Login with credentials -->
    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between mt-2">
            <label for="remember_me" class="inline-flex items-center text-xs text-gray-600">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500" name="remember">
                <span class="ms-2">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="underline text-xs text-gray-600 hover:text-red-600 font-semibold" href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 rounded-lg text-sm shadow transition-colors">
            {{ __('Log in') }}
        </button>
    </form>

    <div class="relative flex items-center justify-center my-6 border-b">
        <span class="absolute bg-white px-3 text-xs text-gray-400 font-bold uppercase tracking-wider">Or</span>
    </div>

    <!-- Alternative Login Links -->
    <div class="space-y-2">
        <a href="{{ route('otp.login.form') }}" class="w-full text-center border border-gray-200 hover:bg-gray-50 text-gray-700 font-bold py-2.5 rounded-lg text-sm transition-colors flex items-center justify-center space-x-2">
            <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            <span>Login with Mobile (OTP)</span>
        </a>
        <a href="{{ route('auth.google') }}" class="w-full text-center border border-gray-200 hover:bg-gray-50 text-gray-700 font-bold py-2.5 rounded-lg text-sm transition-colors flex items-center justify-center space-x-2">
            <span>Continue with Google</span>
        </a>
    </div>

    <div class="text-center text-xs text-gray-400 mt-6">
        Don't have an account? <a href="{{ route('register') }}" class="text-red-600 font-bold hover:underline">Register</a>
    </div>
</x-guest-layout>
