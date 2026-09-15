@extends('layouts.public')

@section('title', 'Google Auth Simulator | Patiala Property')

@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm space-y-6">
        <div class="text-center">
            <span class="bg-amber-100 text-amber-800 text-xs px-2.5 py-1 rounded-full font-bold uppercase tracking-wider">Developer Sandbox</span>
            <h1 class="text-2xl font-black text-gray-900 mt-3">Google Login Simulator</h1>
            <p class="text-xs text-gray-400 mt-1">Google OAuth client ID is not configured in your `.env` file. Choose an option below to simulate Google login behavior.</p>
        </div>

        <form action="{{ route('auth.google.simulate-submit') }}" method="POST" class="space-y-4">
            @csrf
            
            <div class="space-y-2">
                <button type="submit" name="role" value="new_user" class="w-full text-left p-3.5 border rounded-lg hover:bg-red-50 hover:border-red-500 transition-colors flex flex-col">
                    <span class="text-sm font-bold text-gray-900">Simulate a NEW Google User</span>
                    <span class="text-xs text-gray-400">Routes to Google callback profile page to select role (Buyer, Owner, Agent)</span>
                </button>

                <div class="relative flex items-center justify-center my-6">
                    <span class="text-xs text-gray-400 uppercase tracking-widest font-bold">Or log in as existing demo user</span>
                </div>

                <button type="submit" name="email" value="admin@patialaproperty.com" class="w-full text-left p-3 border rounded-lg hover:bg-gray-50 transition-colors flex justify-between items-center">
                    <div>
                        <span class="text-sm font-bold block text-gray-900">Patiala Property Admin</span>
                        <span class="text-xs text-gray-400 block">admin@patialaproperty.com</span>
                    </div>
                    <span class="bg-red-100 text-red-800 text-xs px-2 py-0.5 rounded font-bold uppercase">Admin</span>
                </button>

                <button type="submit" name="email" value="owner@patialaproperty.com" class="w-full text-left p-3 border rounded-lg hover:bg-gray-50 transition-colors flex justify-between items-center">
                    <div>
                        <span class="text-sm font-bold block text-gray-900">Ramesh Kumar (Owner)</span>
                        <span class="text-xs text-gray-400 block">owner@patialaproperty.com</span>
                    </div>
                    <span class="bg-amber-100 text-amber-800 text-xs px-2 py-0.5 rounded font-bold uppercase">Owner</span>
                </button>

                <button type="submit" name="email" value="agent@patialaproperty.com" class="w-full text-left p-3 border rounded-lg hover:bg-gray-50 transition-colors flex justify-between items-center">
                    <div>
                        <span class="text-sm font-bold block text-gray-900">Sukhwinder Singh (Agent)</span>
                        <span class="text-xs text-gray-400 block">agent@patialaproperty.com</span>
                    </div>
                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded font-bold uppercase">Agent</span>
                </button>

                <button type="submit" name="email" value="tenant@patialaproperty.com" class="w-full text-left p-3 border rounded-lg hover:bg-gray-50 transition-colors flex justify-between items-center">
                    <div>
                        <span class="text-sm font-bold block text-gray-900">Gaurav Sharma (Buyer/Tenant)</span>
                        <span class="text-xs text-gray-400 block">tenant@patialaproperty.com</span>
                    </div>
                    <span class="bg-green-100 text-green-800 text-xs px-2 py-0.5 rounded font-bold uppercase">Buyer</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
