<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use Carbon\Carbon; // ✅ Correct place (outside class)

class SettingsController extends Controller
{
    // ------------------------------
    // LOGOUT PAGE
    // ------------------------------
    public function logout()
    {
        return view('user.setting.logout');
    }

    // ------------------------------
    // DELETE CONFIRM PAGE
    // ------------------------------
    public function delete()
    {
        return view('user.settings.delete');
    }

    // ------------------------------
    // PERMANENT DELETE
    // ------------------------------
    public function destroy()
    {
        $user = auth()->user();

        auth()->logout();

        $user->delete();

        return redirect('/')
            ->with('success', 'Your account has been deleted.');
    }

    // ------------------------------
    // DEACTIVATE CONFIRM PAGE
    // ------------------------------
    public function confirmDeactivate()
{
    return view('user.settings.confirmdeactivate');
}

public function deactivate()
{
    $user = auth()->user();

    $user->update([
        'is_deactivated' => true,
        'deactivated_at' => now(),
        'scheduled_delete_at' => now()->addDays(15),
    ]);

    auth()->logout();

    return redirect('/')
        ->with('success', 'Your account has been deactivated.');
}
}