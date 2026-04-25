<?php

namespace App\Http\Controllers;

use App\Models\SavedProfile;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavedProfileController extends Controller
{
    // ── Saved Profiles List Page ──
    public function index()
    {
        $savedProfiles = SavedProfile::where('user_id', Auth::id())
            ->with(['savedUser', 'savedUser.profile', 'savedUser.profile.photos'])
            ->latest()
            ->paginate(12);

        return view('user.saved-profiles', compact('savedProfiles'));
    }

    // ── Save a profile (AJAX) ──
    public function save(Request $request)
    {
        $request->validate([
            'saved_user_id' => 'required|exists:users,id',
        ]);

        // Apne aap ko save nahi kar sakte
        if ($request->saved_user_id == Auth::id()) {
            return response()->json(['error' => 'You cannot save your own profile.'], 400);
        }

        // Already saved?
        $exists = SavedProfile::where('user_id', Auth::id())
            ->where('saved_user_id', $request->saved_user_id)
            ->exists();

        if ($exists) {
            return response()->json(['error' => 'Already saved.'], 400);
        }

        SavedProfile::create([
            'user_id'       => Auth::id(),
            'saved_user_id' => $request->saved_user_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => '✅ Profile saved!',
            'saved'   => true,
        ]);
    }

    // ── Unsave a profile (AJAX) ──
    public function unsave(Request $request)
    {
        $request->validate([
            'saved_user_id' => 'required|exists:users,id',
        ]);

        SavedProfile::where('user_id', Auth::id())
            ->where('saved_user_id', $request->saved_user_id)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Profile removed from saved.',
            'saved'   => false,
        ]);
    }

    // ── Toggle save/unsave (AJAX) ──
    public function toggle(Request $request)
    {
        $request->validate([
            'saved_user_id' => 'required|exists:users,id',
        ]);

        if ($request->saved_user_id == Auth::id()) {
            return response()->json(['error' => 'Cannot save your own profile.'], 400);
        }

        $existing = SavedProfile::where('user_id', Auth::id())
            ->where('saved_user_id', $request->saved_user_id)
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json([
                'success' => true,
                'saved'   => false,
                'message' => 'Profile removed from saved.',
            ]);
        } else {
            SavedProfile::create([
                'user_id'       => Auth::id(),
                'saved_user_id' => $request->saved_user_id,
            ]);
            return response()->json([
                'success' => true,
                'saved'   => true,
                'message' => '✅ Profile saved!',
            ]);
        }
    }
}