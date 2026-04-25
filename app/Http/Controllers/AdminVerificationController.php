<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminVerificationController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Filter by status
        if ($request->status === 'verified') {
            $query->whereNotNull('email_verified_at');
        } elseif ($request->status === 'unverified') {
            $query->whereNull('email_verified_at');
        }

        // Search by name or email
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name',  'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->latest()->paginate(20);

        // ── Recalculate fresh counts every time ──
        $totalUsers      = User::count();
        $verifiedUsers   = User::whereNotNull('email_verified_at')->count();
        $unverifiedUsers = User::whereNull('email_verified_at')->count();

        return view('admin.verification.index', compact(
            'users', 'totalUsers', 'verifiedUsers', 'unverifiedUsers'
        ));
    }

    // Manually verify a user
    public function verify(User $user)
    {
        // Use markEmailAsVerified() — the proper Laravel method
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        } else {
            // Already verified — just update timestamp
            $user->forceFill(['email_verified_at' => now()])->save();
        }

        return redirect()->route('admin.verification.index')
                         ->with('success', "✅ {$user->name} has been verified successfully.");
    }

    // Manually unverify a user
    public function unverify(User $user)
    {
        $user->forceFill(['email_verified_at' => null])->save();

        return redirect()->route('admin.verification.index')
                         ->with('success', "❌ {$user->name} has been unverified.");
    }

    // Send verification reminder email
    public function sendReminder(Request $request)
    {
        $request->validate(['user_id' => 'required|exists:users,id']);
        $user = User::find($request->user_id);

        if (!$user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
            return redirect()->route('admin.verification.index')
                             ->with('success', "📧 Verification email sent to {$user->name}.");
        }

        return redirect()->route('admin.verification.index')
                         ->with('error', "{$user->name} is already verified.");
    }
}