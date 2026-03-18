<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminNotificationsController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlockedUsersController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DiscoverController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MarriageController;
use App\Http\Controllers\NotificationSettingsController;
use App\Http\Controllers\PremiumController;
use App\Http\Controllers\ProfileVisibilityController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\WhoViewedMeController;
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
// Return to Admin — middleware ke BAHAR (auth only)
// Kyunki jab admin user ke account mein hota hai
// toh 'admin' middleware block kar deta tha
// -----------------------------------------------
Route::get('/admin/return-to-admin', [AdminController::class, 'returnToAdmin'])
    ->middleware('auth')
    ->name('admin.return');

// -----------------------------------------------
// Google OAuth
// -----------------------------------------------
Route::get('/auth/google', [GoogleController::class, 'redirect']);
Route::get('/auth/google/callback', [GoogleController::class, 'callback']);

// -----------------------------------------------
// Admin Routes
// -----------------------------------------------
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');

    Route::get('/profile/edit', [AdminController::class, 'editOwn'])->name('edit');
    Route::put('/profile/update', [AdminController::class, 'updateOwn'])->name('update');

    // User list & delete
    Route::get('/userdetail', [AdminController::class, 'userList'])->name('index');
    Route::delete('/users/{id}', [AdminController::class, 'destroy'])->name('users.destroy');

    // Login as user (return route ab bahar hai)
    Route::get('/users/{id}/login-as', [AdminController::class, 'loginAsUser'])->name('users.login-as');

    // All user profiles list
    Route::get('/profiles', [UserProfileController::class, 'index'])->name('profiles.index');

});

// -----------------------------------------------
// User Routes
// -----------------------------------------------
Route::prefix('user')->name('user.')->middleware(['auth', 'user'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');

    // Profile
    Route::get('/profile',            [UserProfileController::class, 'show'])->name('profile');
    Route::post('/profile/store',     [UserProfileController::class, 'store'])->name('profile.store');
    Route::patch('/profile/update',   [UserProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/destroy', [UserProfileController::class, 'destroy'])->name('profile.destroy');

    // Photo routes
    Route::post('/profile/photo/upload', [UserProfileController::class, 'uploadPhoto'])->name('profile.photo.upload');
    Route::post('/profile/photo/main',   [UserProfileController::class, 'setMainPhoto'])->name('profile.photo.main');
    Route::delete('/profile/photo',      [UserProfileController::class, 'deletePhoto'])->name('profile.photo.delete');

    // Discover
    Route::get('/discover', [DiscoverController::class, 'index'])->name('discover');

    // Chat
    Route::get('/chat',  [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat', [ChatController::class, 'send'])->name('chat.send');

    // Premium
    Route::get('/premium/plans',       [PremiumController::class, 'index'])->name('premium.plans');
    Route::post('/premium/checkout',   [PremiumController::class, 'checkout'])->name('premium.checkout');
    Route::get('/premium/success',     [PremiumController::class, 'success'])->name('premium.success');
    Route::get('/premium/cancel',      [PremiumController::class, 'cancel'])->name('premium.cancel');
    Route::post('/stripe/webhook',     [PremiumController::class, 'webhook'])->name('stripe.webhook');
});

// -----------------------------------------------
// Settings Routes
// -----------------------------------------------
Route::prefix('setting')->name('user.')->middleware(['auth', 'user'])->group(function () {
    Route::get('/profile',       [SettingsController::class, 'profile'])->name('settings.profile');
    Route::get('/privacy',       [SettingsController::class, 'privacy'])->name('settings.privacy');
    Route::get('/notifications', [SettingsController::class, 'notifications'])->name('settings.notifications');
    Route::get('/password',      [SettingsController::class, 'password'])->name('settings.password');
    Route::get('/deactivate',    [SettingsController::class, 'confirmdeactivate'])->name('settings.confirmdeactivate');
    Route::get('/deactivate',    [SettingsController::class, 'deactivate'])->name('settings.deactivate');
    Route::get('/delete',        [SettingsController::class, 'delete'])->name('settings.delete');
    Route::post('/destroy',      [SettingsController::class, 'destroy'])->name('settings.destroy');
    Route::get('/logout',        [SettingsController::class, 'logout'])->name('settings.logout');
});

// -----------------------------------------------
// Who Viewed Me
// -----------------------------------------------
Route::get('/who-viewed-me', [WhoViewedMeController::class, 'index'])->name('user.who-viewed-me');

// -----------------------------------------------
// Notification Settings
// -----------------------------------------------
Route::middleware(['auth'])->prefix('user/settings')->name('user.settings.')->group(function () {

    Route::get('/notifications', [NotificationSettingsController::class, 'index'])
        ->name('notifications');

    Route::get('/notifications/push',    [NotificationSettingsController::class, 'push'])
        ->name('notifications.push');
    Route::put('/notifications/push',    [NotificationSettingsController::class, 'updatePush'])
        ->name('notifications.push.update');

    Route::get('/notifications/in-app',  [NotificationSettingsController::class, 'inApp'])
        ->name('notifications.inapp');
    Route::put('/notifications/in-app',  [NotificationSettingsController::class, 'updateInApp'])
        ->name('notifications.inapp.update');

    Route::get('/notifications/email',   [NotificationSettingsController::class, 'email'])
        ->name('notifications.email');
    Route::put('/notifications/email',   [NotificationSettingsController::class, 'updateEmail'])
        ->name('notifications.email.update');

    Route::get('/notifications/security', [NotificationSettingsController::class, 'security'])
        ->name('notifications.security');
    Route::put('/notifications/security', [NotificationSettingsController::class, 'updateSecurity'])
        ->name('notifications.security.update');
});

// -----------------------------------------------
// Profile Visibility
// -----------------------------------------------
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::get('/settings/visibility', [ProfileVisibilityController::class, 'show'])
        ->name('settings.visibility');
    Route::put('/settings/visibility', [ProfileVisibilityController::class, 'update'])
        ->name('settings.visibility.update');
});

// -----------------------------------------------
// Activity
// -----------------------------------------------
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::get('/activity', [ActivityController::class, 'index'])->name('activity');
});

// -----------------------------------------------
// Blocked Users
// -----------------------------------------------
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::get('/blocked',         [BlockedUsersController::class, 'index'])->name('blocked.index');
    Route::delete('/blocked/{id}', [BlockedUsersController::class, 'unblock'])->name('blocked.unblock');
});

// -----------------------------------------------
// Marriage
// -----------------------------------------------
Route::prefix('user/marriage')->name('user.marriage.')->group(function () {
    Route::get('/',           [MarriageController::class, 'index'])->name('index');
    Route::get('/nearby',     [MarriageController::class, 'nearbyProfiles'])->name('nearby');
    Route::post('/location',  [MarriageController::class, 'updateLocation'])->name('location');
    Route::post('/like',      [MarriageController::class, 'like'])->name('like');
    Route::post('/pass',      [MarriageController::class, 'pass'])->name('pass');
    Route::post('/favourite', [MarriageController::class, 'toggleFavourite'])->name('favourite');
});

Route::prefix('admin/')->name('admin.reports.')->group(function () {
    Route::get('/',   [ReportsController::class, 'index'])->name('index');
});
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
 
    // ... existing routes ...
 
    // Notifications
    Route::get('/notifications',            [AdminNotificationsController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-all',  [AdminNotificationsController::class, 'markAllRead'])->name('notifications.mark-all-read');
    Route::post('/notifications/{id}/read', [AdminNotificationsController::class, 'markRead'])->name('notifications.mark-read');
    Route::delete('/notifications/{id}',    [AdminNotificationsController::class, 'destroy'])->name('notifications.destroy');
    Route::delete('/notifications',         [AdminNotificationsController::class, 'clearAll'])->name('notifications.clear');
 
});
  