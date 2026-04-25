<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\BlockedUser;
use App\Models\Notification;
use Illuminate\Http\Request;

class AdminBlockedUserController extends Controller
{
    public function index(Request $request)
    {
        // ── Tab 1: Admin Banned Users (is_blocked = true) ──
        $adminBannedQuery = User::where('is_blocked', true);

        if ($request->search) {
            $adminBannedQuery->where(function($q) use ($request) {
                $q->where('name',  'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $adminBannedUsers = $adminBannedQuery->latest('blocked_at')->paginate(15, ['*'], 'banned_page');

        // ── Tab 2: User-to-User Blocks ──
        $userBlocksQuery = BlockedUser::with(['user', 'blocked']);

        if ($request->search) {
            $userIds = User::where('name',  'like', '%' . $request->search . '%')
                          ->orWhere('email', 'like', '%' . $request->search . '%')
                          ->pluck('id');
            $userBlocksQuery->where(function($q) use ($userIds) {
                $q->whereIn('user_id',    $userIds)
                  ->orWhereIn('blocked_id', $userIds);
            });
        }

        $userBlocks = $userBlocksQuery->latest()->paginate(15, ['*'], 'blocks_page');

        // Stats
        $totalAdminBanned  = User::where('is_blocked', true)->count();
        $totalUserBlocks   = BlockedUser::count();
        $totalBlocked      = $totalAdminBanned + $totalUserBlocks;

        return view('admin.blocked_users.index', compact(
            'adminBannedUsers', 'userBlocks',
            'totalAdminBanned', 'totalUserBlocks', 'totalBlocked'
        ));
    }

    // Manually block a user
    public function block(Request $request, User $user)
    {
        $request->validate([
            'reason' => 'nullable|string|max:255',
        ]);

        $user->update([
            'is_blocked'   => true,
            'blocked_at'   => now(),
            'block_reason' => $request->reason ?? 'Manually blocked by admin.',
        ]);

        // Send ban notification
        Notification::create([
            'user_id' => $user->id,
            'type'    => 'ban',
            'title'   => '🚫 Account Banned',
            'message' => 'Your account has been banned by our admin team.',
        ]);

        return back()->with('success', "{$user->name} has been blocked.");
    }

    // Unblock a user
    public function unblock(User $user)
    {
        $user->update([
            'is_blocked'   => false,
            'blocked_at'   => null,
            'block_reason' => null,
            'warning_sent' => false,
        ]);

        // Send unban notification
        Notification::create([
            'user_id' => $user->id,
            'type'    => 'info',
            'title'   => '✅ Account Reinstated',
            'message' => 'Your account has been reinstated. Please follow our community guidelines.',
        ]);

        return back()->with('success', "{$user->name} has been unblocked.");
    }
}