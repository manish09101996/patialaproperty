@extends('layouts.public')

@section('title', 'Verify OTP | Patiala Property')

@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm space-y-6">
        
        <!-- Dev Help Toast -->
        @if(session()->has('last_otp'))
            <div class="bg-amber-50 border border-amber-200 text-amber-800 p-3 rounded-lg text-xs font-semibold mb-4 text-center">
                [Local Test Mode] OTP generated in system logs is: <strong>{{ session()->get('last_otp') }}</strong>
            </div>
        @endif

        <div class="text-center">
            <h1 class="text-2xl font-black text-gray-900">Verify Code</h1>
            <p class="text-xs text-gray-400 mt-1">Enter the 6-digit code sent to +91 {{ $mobile }}</p>
        </div>

        <form action="{{ route('otp.verify') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="mobile" value="{{ $mobile }}">

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Verification Code</label>
                <input type="text" name="otp" class="w-full rounded-lg border-gray-200 py-2.5 text-center text-lg font-black tracking-[0.75em]" placeholder="000000" maxlength="6" required autofocus>
                @error('otp')
                    <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 rounded-lg text-sm shadow transition-colors">
                Verify & Continue
            </button>
        </form>

        <div class="text-center text-xs text-gray-400">
            Didn't receive code? <a href="{{ route('otp.login.form') }}" class="text-red-600 font-bold hover:underline">Resend OTP</a>
        </div>
    </div>
</div>
@endsection
