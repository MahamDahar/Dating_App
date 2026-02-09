<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Admin dashboard – admin login ke baad yahan redirect hota hai.
     */
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    /**
     * Admin logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('frontend.login')->with('success', 'Logged out successfully.');
    }
}
