<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\LocalityManagementController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Auth\GoogleLoginController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Public Homepage
Route::get('/', HomeController::class)->name('home');

// OTP Authentication Routes (Guests)
Route::middleware('guest')->group(function () {
    Route::get('/otp-login', [OtpController::class, 'showLoginForm'])->name('otp.login.form');
    Route::post('/otp-login', [OtpController::class, 'sendOtp'])->name('otp.send');
    Route::get('/otp-verify', [OtpController::class, 'showVerifyForm'])->name('otp.verify.form');
    Route::post('/otp-verify', [OtpController::class, 'verifyOtp'])->name('otp.verify');
    Route::get('/complete-profile', [OtpController::class, 'showCompleteProfileForm'])->name('otp.complete-profile.form');
    Route::post('/complete-profile', [OtpController::class, 'completeProfile'])->name('otp.complete-profile');
    
    // Google OAuth Simulated / Actual routes
    Route::get('/auth/google', [GoogleLoginController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [GoogleLoginController::class, 'handleGoogleCallback'])->name('auth.google.callback');
    Route::get('/auth/google-simulator', [GoogleLoginController::class, 'showSimulator'])->name('auth.google.simulator');
    Route::post('/auth/google-simulator', [GoogleLoginController::class, 'handleSimulation'])->name('auth.google.simulate-submit');
    Route::get('/auth/google/complete-profile', [GoogleLoginController::class, 'showCompleteProfile'])->name('auth.google.complete-profile');
    Route::post('/auth/google/complete-profile', [GoogleLoginController::class, 'saveCompleteProfile'])->name('auth.google.complete-profile.save');
});

// Property Public Catalog
Route::get('/properties', [PropertyController::class, 'index'])->name('properties.index');
Route::get('/properties/{slug}', [PropertyController::class, 'show'])->name('properties.show');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Role-based Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Property Management (Creation & Editing)
    Route::get('/property/create', [PropertyController::class, 'create'])->name('properties.create');
    Route::post('/properties', [PropertyController::class, 'store'])->name('properties.store');
    Route::get('/properties/{property}/edit', [PropertyController::class, 'edit'])->name('properties.edit');
    Route::put('/properties/{property}', [PropertyController::class, 'update'])->name('properties.update');
    Route::delete('/properties/{property}', [PropertyController::class, 'destroy'])->name('properties.destroy');
    Route::post('/properties/{property}/save', [PropertyController::class, 'toggleSaved'])->name('properties.save');

    // Leads & Inquiries
    Route::post('/properties/{property}/lead', [LeadController::class, 'store'])->name('leads.store');
    Route::post('/leads/{lead}/status', [LeadController::class, 'updateStatus'])->name('leads.status');

    // Property Visit Scheduling
    Route::post('/properties/{property}/visit', [VisitController::class, 'store'])->name('visits.store');
    Route::post('/visits/{visit}/status', [VisitController::class, 'updateStatus'])->name('visits.status');

    // Admin Specific Workflows
    Route::middleware('can:approve,App\Models\Property')->group(function () {
        Route::post('/admin/properties/{property}/approve', [DashboardController::class, 'approveProperty'])->name('admin.properties.approve');
        
        // Locality Management
        Route::post('/admin/areas', [LocalityManagementController::class, 'store'])->name('admin.areas.store');
        Route::put('/admin/areas/{area}', [LocalityManagementController::class, 'update'])->name('admin.areas.update');
        Route::post('/admin/areas/{area}/toggle', [LocalityManagementController::class, 'toggle'])->name('admin.areas.toggle');
        Route::delete('/admin/areas/{area}', [LocalityManagementController::class, 'destroy'])->name('admin.areas.destroy');
    });
});

require __DIR__.'/auth.php';
