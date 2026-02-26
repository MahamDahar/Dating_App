<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DiscoverController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PremiumController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Route;

// -----------------------------------------------
// Auth Routes (Guest only)
// -----------------------------------------------
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'show'])->name('frontend.register.show');
Route::post('/register', [AuthController::class, 'register'])->name('register');

// -----------------------------------------------
// Frontend / Public Routes
// -----------------------------------------------
Route::get('/', [HomeController::class, 'index'])->name('frontend.index');
Route::get('/membership', [HomeController::class, 'membership'])->name('frontend.membership');
Route::get('/community', [HomeController::class, 'community'])->name('frontend.community');
Route::get('/blogs', [HomeController::class, 'blogs'])->name('frontend.blogs');
Route::get('/contact', [HomeController::class, 'contact'])->name('frontend.contact');
Route::get('/about', [HomeController::class, 'about'])->name('frontend.about');

// -----------------------------------------------
// Logout (shared)
// -----------------------------------------------
Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

// -----------------------------------------------
// Admin Routes
// -----------------------------------------------
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');

    // Admin own profile edit
    Route::get('/profile/edit', [AdminController::class, 'editOwn'])->name('edit');
    Route::put('/profile/update', [AdminController::class, 'updateOwn'])->name('update');

    // User list & delete
    Route::get('/userdetail', [AdminController::class, 'userList'])->name('index');
    Route::delete('/users/{id}', [AdminController::class, 'destroy'])->name('users.destroy');

    // ✅ All user profiles list (fixed: moved inside admin group, correct middleware)
    Route::get('/profiles', [UserProfileController::class, 'index'])->name('profiles.index');

});

// -----------------------------------------------
// User Routes
// -----------------------------------------------
Route::prefix('user')->name('user.')->middleware(['auth', 'user'])->group(function () {

    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/userprofile', [UserController::class, 'userprofile'])->name('userprofile');
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat', [ChatController::class, 'send'])->name('chat.send');
    Route::get('/discover', [DiscoverController::class, 'index'])->name('discover');

     Route::get('/profile', [UserProfileController::class, 'show'])->name('profile.show');

    Route::get('/profile/edit', [UserProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::post('/profile', [UserProfileController::class, 'store'])
        ->name('profile.store');

    Route::patch('/profile', [UserProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [UserProfileController::class, 'destroy'])
        ->name('profile.destroy');



});


Route::get('/auth/google', [GoogleController::class ,'redirect']);
Route::get('/auth/google/callback', [GoogleController::class ,'callback']);



Route::middleware('auth')->group(function () {
    Route::get('/premium/plans', [PremiumController::class, 'index'])->name('premium.plans');
    Route::post('/premium/checkout', [PremiumController::class, 'checkout'])->name('premium.checkout');
    Route::get('/premium/success', [PremiumController::class, 'success'])->name('premium.success');
    Route::get('/premium/cancel', [PremiumController::class, 'cancel'])->name('premium.cancel');
});


