<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileVisibilityController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $currentVisibility = $user->profile_visibility ?? 'everyone';
        return view('user.settings.profilevisibility', compact('currentVisibility'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'visibility' => 'required|in:everyone,liked_only,hidden',
        ]);

        Auth::user()->update([
            'profile_visibility' => $request->visibility,
        ]);

        return back()->with('success', 'Profile visibility updated successfully.');
    }
}