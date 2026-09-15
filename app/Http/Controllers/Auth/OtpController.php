<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OtpVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OtpController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.otp-login');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'mobile' => ['required', 'regex:/^[6-9]\d{9}$/'], // Indian 10-digit mobile number format
        ]);

        $mobile = $request->mobile;
        
        // In local development, we can generate a static OTP or a random one. Let's make it random but log it.
        $otp = rand(100000, 999999);
        $expiresAt = now()->addMinutes(5);

        // Store OTP in database
        OtpVerification::create([
            'mobile' => $mobile,
            'otp' => $otp,
            'expires_at' => $expiresAt,
            'is_verified' => false,
        ]);

        // Simulating SMS sending
        Log::info("OTP for Patiala Property login for mobile {$mobile} is: {$otp}");

        // For convenience in testing/local development, store OTP in session so we can display it as a toast/helper
        session()->put('last_otp', $otp);

        return redirect()->route('otp.verify.form', ['mobile' => $mobile])
            ->with('status', 'OTP has been sent to your mobile number. Check log or screen.');
    }

    public function showVerifyForm(Request $request)
    {
        $mobile = $request->query('mobile');
        if (!$mobile) {
            return redirect()->route('otp.login.form');
        }

        return view('auth.otp-verify', ['mobile' => $mobile]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'mobile' => ['required', 'regex:/^[6-9]\d{9}$/'],
            'otp' => ['required', 'digits:6'],
        ]);

        $mobile = $request->mobile;
        $otp = $request->otp;

        $verification = OtpVerification::where('mobile', $mobile)
            ->where('otp', $otp)
            ->where('is_verified', false)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (!$verification) {
            return back()->withErrors(['otp' => 'The provided OTP is invalid or expired.']);
        }

        // Mark OTP as verified
        $verification->update(['is_verified' => true]);

        // Find or redirect to profile completion
        $user = User::where('mobile', $mobile)->first();

        if ($user) {
            if (!$user->is_active) {
                return redirect()->route('otp.login.form')
                    ->with('error', 'Your account has been deactivated by administrator.');
            }
            $user->update(['mobile_verified_at' => now()]);
            Auth::login($user);
            return redirect()->intended(route('dashboard', absolute: false));
        }

        // Save mobile to session for profile completion step
        session()->put('otp_verified_mobile', $mobile);

        return redirect()->route('otp.complete-profile.form');
    }

    public function showCompleteProfileForm()
    {
        $mobile = session()->get('otp_verified_mobile');
        if (!$mobile) {
            return redirect()->route('otp.login.form');
        }

        return view('auth.complete-profile', ['mobile' => $mobile]);
    }

    public function completeProfile(Request $request)
    {
        $mobile = session()->get('otp_verified_mobile');
        if (!$mobile) {
            return redirect()->route('otp.login.form');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'in:user,owner,agent,service_provider'],
        ]);

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $mobile,
            'role' => $request->role,
            'mobile_verified_at' => now(),
            'is_active' => true,
        ]);

        session()->forget('otp_verified_mobile');

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
