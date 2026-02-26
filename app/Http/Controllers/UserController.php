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
        $totalRevenue    = 0;
        $totalCustomers  = \App\Models\User::count();
        $totalOrders     = 0;
        $conversionRate  = 0;

        return view('user.dashboard', compact(
            'totalRevenue',
            'totalCustomers',
            'totalOrders',
            'conversionRate'
        ));
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