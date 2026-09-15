@extends('layouts.public')

@section('title', 'OTP Sign In | Patiala Property')

@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm space-y-6">
        <div class="text-center">
            <h1 class="text-2xl font-black text-gray-900">Login with Mobile</h1>
            <p class="text-xs text-gray-400 mt-1">We will send a 6-digit OTP verification code to your number.</p>
        </div>

        <form action="{{ route('otp.send') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Mobile Number</label>
                <div class="relative flex items-center">
                    <span class="absolute left-3 text-sm text-gray-400 font-semibold">+91</span>
                    <input type="text" name="mobile" class="w-full rounded-lg border-gray-200 pl-12 py-2.5 text-sm font-semibold tracking-wider" placeholder="9876543210" required autofocus>
                </div>
                @error('mobile')
                    <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 rounded-lg text-sm shadow transition-colors">
                Get Verification Code
            </button>
        </form>

        <div class="relative flex items-center justify-center my-6 border-b">
            <span class="absolute bg-white px-3 text-xs text-gray-400 font-bold uppercase tracking-wider">Or</span>
        </div>

        <div class="space-y-2">
            <a href="{{ route('auth.google') }}" class="w-full border border-gray-200 hover:bg-gray-50 font-bold py-2.5 rounded-lg text-sm transition-colors flex items-center justify-center space-x-2 text-gray-700">
                <span>Continue with Google</span>
            </a>
            <a href="{{ route('login') }}" class="w-full text-center text-xs text-gray-400 hover:text-red-600 font-bold block transition-colors">
                Login with Email & Password
            </a>
        </div>
    </div>
</div>
@endsection
