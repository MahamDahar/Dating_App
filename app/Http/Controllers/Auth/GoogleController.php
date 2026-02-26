<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')
            ->stateless()
            ->user();

        $user = User::updateOrCreate(
            ['email' => $googleUser->email],
            [
                'name'      => $googleUser->name,
                'google_id' => $googleUser->id,
                'password'  => bcrypt('password'),
            ]
        );

        Auth::login($user);

        return redirect('user/dashboard');
    }
}