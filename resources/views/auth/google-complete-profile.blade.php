@extends('layouts.public')

@section('title', 'Select Role | Patiala Property')

@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm space-y-6">
        <div class="text-center">
            <h1 class="text-2xl font-black text-gray-900">Select Platform Role</h1>
            <p class="text-xs text-gray-400 mt-1">Hello <strong>{{ $googleData['name'] }}</strong>, please select your user type to continue.</p>
        </div>

        <form action="{{ route('auth.google.complete-profile.save') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 font-black">Choose Your Role</label>
                <div class="space-y-2">
                    <label class="border rounded-lg p-3 flex items-center space-x-3 cursor-pointer hover:bg-gray-50 transition-colors">
                        <input type="radio" name="role" value="user" class="text-red-600 focus:ring-red-500 border-gray-300" checked>
                        <div>
                            <span class="text-sm font-bold block text-gray-900">Buyer / Tenant</span>
                            <span class="text-xs text-gray-400 block">I want to browse and inquire properties in Patiala.</span>
                        </div>
                    </label>
                    <label class="border rounded-lg p-3 flex items-center space-x-3 cursor-pointer hover:bg-gray-50 transition-colors">
                        <input type="radio" name="role" value="owner" class="text-red-600 focus:ring-red-500 border-gray-300">
                        <div>
                            <span class="text-sm font-bold block text-gray-900">Property Owner</span>
                            <span class="text-xs text-gray-400 block">I want to list my house, flat, or plots for sale/rent.</span>
                        </div>
                    </label>
                    <label class="border rounded-lg p-3 flex items-center space-x-3 cursor-pointer hover:bg-gray-50 transition-colors">
                        <input type="radio" name="role" value="agent" class="text-red-600 focus:ring-red-500 border-gray-300">
                        <div>
                            <span class="text-sm font-bold block text-gray-900">Property Agent</span>
                            <span class="text-xs text-gray-400 block">I am a dealer managing multiple properties for my clients.</span>
                        </div>
                    </label>
                </div>
                @error('role')
                    <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 rounded-lg text-sm shadow transition-colors">
                Finalize Signup
            </button>
        </form>
    </div>
</div>
@endsection
