<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;

Route::get('/login', [AuthController::class, 'loginForm'])->name('frontend.login');
Route::post('/login', [AuthController::class, 'login']);


Route::get('/register', [AuthController::class, 'show'])->name('frontend.register.show');
Route::post('/register', [AuthController::class, 'register'])->name('frontend.register');

Route::get('/index', [HomeController::class, 'index'])->name('frontend.index');
Route::get('/membership', [HomeController::class, 'membership'])->name('frontend.membership');
Route::get('/community', [HomeController::class, 'community'])->name('frontend.community');
Route::get('/blogs', [HomeController::class, 'blogs'])->name('frontend.blogs');
Route::get('/contact', [HomeController::class, 'contact'])->name('frontend.contact');
Route::get('/about', [HomeController::class, 'about'])->name('frontend.about');

