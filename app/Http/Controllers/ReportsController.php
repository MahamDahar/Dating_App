<?php

namespace App\Http\Controllers;

use App\Models\Reports;
use App\Models\User;
use App\Models\AdminNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportsController extends Controller
{
public function problem()
{
    return view('user.report.problem');
}

    // User kisi ko report kare
//     public function store(Request $request)
//     {
//         $request->validate([
//             'reported_id' => 'required|exists:users,id',
//             'reason'      => 'required|string',
//             'description' => 'nullable|string|max:500',
//         ]);

//         // Apne aap ko report nahi kar sakte
//         if ($request->reported_id == Auth::id()) {
//             return back()->with('error', 'You cannot report yourself.');
//         }

//         // Already report kiya hua hai?
//         $exists = Report::where('reporter_id', Auth::id())
//                         ->where('reported_id', $request->reported_id)
//                         ->exists();

//         if ($exists) {
//             return back()->with('error', 'You have already reported this user.');
//         }

//         // Report save karo
//         Report::create([
//             'reporter_id' => Auth::id(),
//             'reported_id' => $request->reported_id,
//             'reason'      => $request->reason,
//             'description' => $request->description,
//         ]);

//         // Admin ko notification bhejo
//         $reportedUser = User::find($request->reported_id);
//         AdminNotification::newReport($reportedUser, $request->reason);

//         return back()->with('success', 'Report submitted. Our team will review it.');
//     }

//     // Show report a problem page
// public function problem()
// {
//     return view('user.report.problem');
// }
public function store(Request $request)
{
    $request->validate([
        'reported_id' => 'required|exists:users,id',
        'reason'      => 'required|string',
        'description' => 'nullable|string|max:500',
    ]);

    if ($request->reported_id == Auth::id()) {
        return back()->with('error', 'You cannot report yourself.');
    }

    // Check if this specific user already reported this person
    $exists = Reports::where('reporter_id', Auth::id())
                    ->where('reported_id', $request->reported_id)
                    ->exists();

    if ($exists) {
        return back()->with('error', 'You have already reported this user.');
    }

    // Save report
    Reports::create([
        'reporter_id' => Auth::id(),
        'reported_id' => $request->reported_id,
        'reason'      => $request->reason,
        'description' => $request->description,
        'status'      => 'pending',
    ]);

    // Count unique reporters against this user
    $reportCount = Reports::where('reported_id', $request->reported_id)
                         ->distinct('reporter_id')
                         ->count('reporter_id');

    $reportedUser = User::find($request->reported_id);

    // ── 3 reports → send warning ──
    if ($reportCount === 3 && !$reportedUser->warning_sent) {
        $reportedUser->update(['warning_sent' => true]);

        // Send warning notification
        \App\Models\Notification::create([
            'user_id' => $reportedUser->id,
            'type'    => 'warning',
            'title'   => '⚠️ Account Warning',
            'message' => 'Your account has received multiple reports. If you continue to violate our community guidelines, your account will be permanently banned.',
        ]);
    }

    // ── 5 reports → auto ban ──
    if ($reportCount >= 5 && !$reportedUser->is_blocked) {
        $reportedUser->update([
            'is_blocked'   => true,
            'blocked_at'   => now(),
            'block_reason' => 'Automatically banned due to multiple reports.',
        ]);

        // Send ban notification
        \App\Models\Notification::create([
            'user_id' => $reportedUser->id,
            'type'    => 'ban',
            'title'   => '🚫 Account Banned',
            'message' => 'Your account has been banned due to multiple violations of our community guidelines.',
        ]);

        // Mark all pending reports as reviewed
        Reports::where('reported_id', $reportedUser->id)
              ->where('status', 'pending')
              ->update(['status' => 'reviewed']);
    }

    return back()->with('success', 'Report submitted. Our team will review it.');
}
// Submit a problem report
public function storeProblem(Request $request)
{
    $request->validate([
        'subject'     => 'required|string|max:255',
        'description' => 'required|string|max:1000',
    ]);

    // Save to support tickets or send email
    // For now just return success
    return back()->with('success', 'Your problem has been reported. We will get back to you soon!');
}

}