<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('index');
// });

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;

Route::get('/login', [AuthController::class, 'loginForm'])->name('frontend.login');
Route::post('/login', [AuthController::class, 'login']);


Route::get('/register', [AuthController::class, 'show'])->name('frontend.register.show');
Route::post('/register', [AuthController::class, 'register'])->name('frontend.register');

// Admin routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
});

// User routes 
Route::prefix('user')->name('user.')->middleware(['auth', 'user'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
});

Route::get('/', [HomeController::class, 'index'])->name('frontend.index');
Route::get('/membership', [HomeController::class, 'membership'])->name('frontend.membership');
Route::get('/community', [HomeController::class, 'community'])->name('frontend.community');
Route::get('/blogs', [HomeController::class, 'blogs'])->name('frontend.blogs');
Route::get('/contact', [HomeController::class, 'contact'])->name('frontend.contact');
Route::get('/about', [HomeController::class, 'about'])->name('frontend.about');

