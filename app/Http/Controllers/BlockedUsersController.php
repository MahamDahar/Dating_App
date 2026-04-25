<?php

namespace App\Http\Controllers;

use App\Models\BlockedUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlockedUsersController extends Controller
{
    public function block(Request $request, $id)
    {
        $request->validate([
            'reason' => 'nullable|string|max:255',
        ]);

        if ((int) $id === (int) Auth::id()) {
            return back()->with('error', 'You cannot block yourself.');
        }

        BlockedUser::firstOrCreate(
            [
                'blocker_id' => Auth::id(),
                'blocked_id' => $id,
            ],
            [
                'reason' => $request->input('reason'),
            ]
        );

        return back()->with('success', 'User has been blocked successfully.');
    }

    public function index()
    {
        $blockedUsers = BlockedUser::with('blocked')
            ->where('blocker_id', Auth::id())
            ->latest()
            ->get();

        return view('user.blockedusers', compact('blockedUsers'));
    }

    public function unblock($id)
    {
        BlockedUser::where('blocker_id', Auth::id())
            ->where('blocked_id', $id)
            ->delete();

        return back()->with('success', 'User has been unblocked successfully.');
    }
}
