<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * User dashboard – user login ke baad yahan redirect hota hai.
     */
    public function dashboard()
    {
        return view('user.dashboard');
    }

    /**
     * User logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('frontend.login')->with('success', 'Logged out successfully.');
    }
}
