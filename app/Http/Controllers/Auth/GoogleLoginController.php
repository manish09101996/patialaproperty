<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleLoginController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle()
    {
        // If client ID is not configured, redirect to a simulator page
        if (!config('services.google.client_id')) {
            return redirect()->route('auth.google.simulator');
        }

        try {
            return Socialite::driver('google')->redirect();
        } catch (\Exception $e) {
            return redirect()->route('auth.google.simulator');
        }
    }

    /**
     * Obtain the user information from Google.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Google authentication failed.');
        }

        // Find or create user
        $user = User::where('google_id', $googleUser->id)
            ->orWhere('email', $googleUser->email)
            ->first();

        if ($user) {
            // Update Google ID if it was matched by email
            if (empty($user->google_id)) {
                $user->update(['google_id' => $googleUser->id]);
            }
            Auth::login($user);
            return redirect()->intended(route('dashboard'));
        }

        // It is a new user - redirect to role selection
        session()->put('google_new_user', [
            'name' => $googleUser->name,
            'email' => $googleUser->email,
            'google_id' => $googleUser->id,
            'avatar' => $googleUser->avatar,
        ]);

        return redirect()->route('auth.google.complete-profile');
    }

    /**
     * Show Google simulator for local testing.
     */
    public function showSimulator()
    {
        return view('auth.google-simulator');
    }

    /**
     * Handle simulation logins.
     */
    public function handleSimulation(Request $request)
    {
        $role = $request->input('role');
        $email = $request->input('email');
        
        if ($email) {
            $user = User::where('email', $email)->first();
            if ($user) {
                Auth::login($user);
                return redirect()->route('dashboard');
            }
        }

        if ($role === 'new_user') {
            $tempId = rand(1000, 9999);
            session()->put('google_new_user', [
                'name' => "Google User {$tempId}",
                'email' => "google_user_{$tempId}@gmail.com",
                'google_id' => "google_id_{$tempId}",
                'avatar' => null,
            ]);
            return redirect()->route('auth.google.complete-profile');
        }

        return redirect()->route('login')->with('error', 'Simulated login failed.');
    }

    /**
     * Complete profile for new Google user.
     */
    public function showCompleteProfile()
    {
        $googleData = session()->get('google_new_user');
        if (!$googleData) {
            return redirect()->route('login');
        }

        return view('auth.google-complete-profile', ['googleData' => $googleData]);
    }

    /**
     * Save new Google user profile.
     */
    public function saveCompleteProfile(Request $request)
    {
        $googleData = session()->get('google_new_user');
        if (!$googleData) {
            return redirect()->route('login');
        }

        $request->validate([
            'role' => ['required', 'in:user,owner,agent,service_provider'],
        ]);

        $user = User::create([
            'name' => $googleData['name'],
            'email' => $googleData['email'],
            'google_id' => $googleData['google_id'],
            'role' => $request->role,
            'profile_picture' => $googleData['avatar'],
            'email_verified_at' => now(),
            'is_active' => true,
        ]);

        session()->forget('google_new_user');

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
