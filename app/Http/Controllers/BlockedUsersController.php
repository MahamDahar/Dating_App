<?php

namespace App\Http\Controllers;

use App\Models\UserBlock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlockedUsersController extends Controller
{
    public function index()
    {
        $blockedUsers = UserBlock::with('blocked')
            ->where('blocker_id', Auth::id())
            ->latest()
            ->get();

        return view('user.blockedusers', compact('blockedUsers'));
    }

    public function unblock($id)
    {
        UserBlock::where('blocker_id', Auth::id())
            ->where('blocked_id', $id)
            ->delete();

        return back()->with('success', 'User has been unblocked successfully.');
    }
}
