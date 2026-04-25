<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserSubscription;
use App\Models\SubscriptionPlan;
use App\Models\Reports;
use App\Models\MarriageMatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', '30'); // 7, 30, 90 days

        $startDate = Carbon::now()->subDays((int)$period);

        // ── Total Stats ──
        $stats = [
            'total_users'       => User::where('role', 'user')->count(),
            'new_users'         => User::where('role', 'user')->where('created_at', '>=', $startDate)->count(),
            'active_users'      => User::where('role', 'user')->where('updated_at', '>=', Carbon::now()->subDays(7))->count(),
            'total_subscribers' => UserSubscription::where('status', 'active')->count(),
            'total_revenue'     => UserSubscription::where('status', 'active')->sum('amount_paid'),
            'total_reports'     => Reports::count(),
            'total_blocked'     => User::where('is_blocked', true)->count(),
            'total_matches'     => DB::table('marriage_matches')->count(),
        ];

        // ── New Registrations (daily for last N days) ──
        $registrations = User::where('role', 'user')
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Fill missing dates with 0
        $regLabels = [];
        $regData   = [];
        for ($i = (int)$period - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $regLabels[] = Carbon::now()->subDays($i)->format('d M');
            $regData[]   = $registrations->get($date)?->count ?? 0;
        }

        // ── Revenue (daily) ──
        $revenueData = UserSubscription::where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, SUM(amount_paid) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $revLabels = [];
        $revData   = [];
        for ($i = (int)$period - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $revLabels[] = Carbon::now()->subDays($i)->format('d M');
            $revData[]   = $revenueData->get($date)?->total ?? 0;
        }

        // ── Subscription Plan Breakdown ──
        $planStats = SubscriptionPlan::withCount([
            'subscriptions as total_subs',
            'activeSubscriptions as active_subs',
        ])->get();

        // ── Reports & Blocks (last 30 days) ──
        $reportStats = Reports::where('created_at', '>=', Carbon::now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $reportLabels = [];
        $reportData   = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $reportLabels[] = Carbon::now()->subDays($i)->format('d M');
            $reportData[]   = $reportStats->get($date)?->count ?? 0;
        }

        // ── Gender breakdown ──
        $genderStats = User::where('role', 'user')
            ->selectRaw('gender, COUNT(*) as count')
            ->groupBy('gender')
            ->pluck('count', 'gender');

        // ── Top countries ──
        $topCountries = User::where('role', 'user')
            ->whereNotNull('city')
            ->selectRaw('city, COUNT(*) as count')
            ->groupBy('city')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        return view('admin.analytics.index', compact(
            'stats', 'period',
            'regLabels', 'regData',
            'revLabels', 'revData',
            'planStats',
            'reportLabels', 'reportData',
            'genderStats', 'topCountries'
        ));
    }
}