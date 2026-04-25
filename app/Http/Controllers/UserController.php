<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Userprofile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function dashboard()
    {
        // Dashboard has been removed from user side.
        return redirect()->route('user.discover');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }

    // ✅ FIXED — $profile ab view ko pass ho raha hai
    public function userprofile()
    {
        $profile = Userprofile::where('user_id', auth()->id())->first();
        return view('user.userprofile', compact('profile'));
    }
}