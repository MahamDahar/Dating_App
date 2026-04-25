<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class SettingsController extends Controller
{
    // ── Profile Settings ──
    public function profile()
    {
        $user = auth()->user();
        return view('user.settings.profile', compact('user'));
    }

    // ── Privacy Settings ──
    public function privacy()
    {
        $user = auth()->user();
        return view('user.settings.privacy', compact('user'));
    }

    // ── Notifications ──
    public function notifications()
    {
        $user = auth()->user();
        return view('user.settings.notifications', compact('user'));
    }

    // ── Change Password Page ──
    public function password()
    {
        return view('user.settings.password');
    }

    // ── Update Password ──
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:6|confirmed',
        ]);

        $user = auth()->user();

        // Current password check
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', '❌ Current password is incorrect.');
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', '✅ Password updated successfully!');
    }

    // ── Logout Page ──
    public function logout()
    {
        return view('user.setting.logout');
    }

    // ── Delete Confirm Page ──
    public function delete()
    {
        return view('user.settings.delete');
    }

    // ── Permanent Delete ──
    public function destroy()
    {
        $user = auth()->user();

        auth()->logout();

        $user->delete();

        return redirect('/')
            ->with('success', 'Your account has been deleted.');
    }

    // ── Deactivate Confirm Page ──
    public function confirmDeactivate()
    {
        return view('user.settings.confirmdeactivate');
    }

    // ── Deactivate Account ──
    public function deactivate()
    {
        $user = auth()->user();

        $user->update([
            'is_deactivated'      => true,
            'deactivated_at'      => now(),
            'scheduled_delete_at' => now()->addDays(15),
        ]);

        auth()->logout();

        return redirect('/')
            ->with('success', 'Your account has been deactivated.');
    }
}