<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Reports;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;

class AdminReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Reports::with(['reporter', 'reported']);

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter by reason
        if ($request->reason) {
            $query->where('reason', $request->reason);
        }

        // Search by user name
        if ($request->search) {
            $userIds = User::where('name', 'like', '%' . $request->search . '%')
                          ->pluck('id');
            $query->where(function($q) use ($userIds) {
                $q->whereIn('reporter_id', $userIds)
                  ->orWhereIn('reported_id', $userIds);
            });
        }

        $reports = $query->latest()->paginate(20);

        // Most reported users
        $mostReported = User::withCount('reportsReceived')
            ->having('reports_received_count', '>=', 1)
            ->orderByDesc('reports_received_count')
            ->take(10)
            ->get();

        // Stats
        $totalReports    = Reports::count();
        $pendingReports  = Reports::where('status', 'pending')->count();
        $bannedUsers     = User::where('is_blocked', true)->count();
        $warnedUsers     = User::where('warning_sent', true)
                               ->where('is_blocked', false)->count();

        return view('admin.reports.index', compact(
            'reports', 'mostReported',
            'totalReports', 'pendingReports', 'bannedUsers', 'warnedUsers'
        ));
    }

    // View single report detail
    public function show(Reports $report)
    {
        $report->load(['reporter', 'reported']);

        // All reports against this user
        $allReportsAgainst = Reports::with('reporter')
            ->where('reported_id', $report->reported_id)
            ->latest()
            ->get();

        $uniqueReporters = $allReportsAgainst->unique('reporter_id')->count();

        return view('admin.problem_reports.show', compact(
            'report', 'allReportsAgainst', 'uniqueReporters'
        ));
    }

    // Dismiss a report
    public function dismiss(Reports $report)
    {
        $report->update(['status' => 'dismissed']);
        return back()->with('success', 'Report dismissed.');
    }

    // Manually block a user
    public function blockUser(User $user)
    {
        $user->update([
            'is_blocked'   => true,
            'blocked_at'   => now(),
            'block_reason' => request('reason') ?? 'Manually blocked by admin.',
        ]);

        // Send ban notification to user
        Notification::create([
            'user_id' => $user->id,
            'type'    => 'ban',
            'title'   => '🚫 Account Banned',
            'message' => 'Your account has been banned by our admin team due to violations of community guidelines.',
        ]);

        // Mark all pending reports as reviewed
        Reports::where('reported_id', $user->id)
              ->where('status', 'pending')
              ->update(['status' => 'reviewed']);

        return back()->with('success', "{$user->name} has been blocked.");
    }

    // Manually unblock a user
    public function unblockUser(User $user)
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

    // Send manual warning to user
    public function sendWarning(User $user)
    {
        Notification::create([
            'user_id' => $user->id,
            'type'    => 'warning',
            'title'   => '⚠️ Account Warning',
            'message' => 'Our admin team has reviewed reports against your account. Please follow community guidelines or your account may be banned.',
        ]);

        $user->update(['warning_sent' => true]);

        return back()->with('success', "Warning sent to {$user->name}.");
    }
}