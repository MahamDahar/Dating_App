<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminPaymentController extends Controller
{
    // ── Payments Overview ──
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $period = $request->get('period', 'monthly');
        $search = $request->get('search', '');

        // ── Transactions List ──
        $query = Payment::with(['user', 'plan'])->latest();

        if ($filter !== 'all') {
            $query->where('status', $filter);
        }

        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            })->orWhere('transaction_id', 'like', "%$search%");
        }

        $payments = $query->paginate(15)->withQueryString();

        // ── Revenue Stats ──
        $stats = [
            'total_revenue'   => Payment::where('status', 'paid')->sum('amount'),
            'today_revenue'   => Payment::where('status', 'paid')->whereDate('paid_at', today())->sum('amount'),
            'month_revenue'   => Payment::where('status', 'paid')->whereMonth('paid_at', now()->month)->sum('amount'),
            'year_revenue'    => Payment::where('status', 'paid')->whereYear('paid_at', now()->year)->sum('amount'),
            'total_paid'      => Payment::where('status', 'paid')->count(),
            'total_pending'   => Payment::where('status', 'pending')->count(),
            'total_failed'    => Payment::where('status', 'failed')->count(),
            'total_refunded'  => Payment::where('status', 'refunded')->count(),
        ];

        // ── Revenue Chart Data ──
        if ($period === 'daily') {
            $revenueData = Payment::where('status', 'paid')
                ->where('paid_at', '>=', Carbon::now()->subDays(30))
                ->selectRaw('DATE(paid_at) as date, SUM(amount) as total')
                ->groupBy('date')->orderBy('date')->get();
            $labels = $revenueData->pluck('date')->map(fn($d) => Carbon::parse($d)->format('d M'));
            $data   = $revenueData->pluck('total');
        } elseif ($period === 'monthly') {
            $revenueData = Payment::where('status', 'paid')
                ->where('paid_at', '>=', Carbon::now()->subMonths(12))
                ->selectRaw('YEAR(paid_at) as year, MONTH(paid_at) as month, SUM(amount) as total')
                ->groupBy('year', 'month')->orderBy('year')->orderBy('month')->get();
            $labels = $revenueData->map(fn($r) => Carbon::createFromDate($r->year, $r->month, 1)->format('M Y'));
            $data   = $revenueData->pluck('total');
        } else { // yearly
            $revenueData = Payment::where('status', 'paid')
                ->selectRaw('YEAR(paid_at) as year, SUM(amount) as total')
                ->groupBy('year')->orderBy('year')->get();
            $labels = $revenueData->pluck('year');
            $data   = $revenueData->pluck('total');
        }

        // ── Plan Revenue Breakdown ──
        $planRevenue = SubscriptionPlan::withSum(['subscriptions as revenue' => function($q) {
            $q->where('status', 'active');
        }], 'amount_paid')->get();

        $plans = SubscriptionPlan::all();

        return view('admin.payments.index', compact(
            'payments', 'stats', 'filter', 'period',
            'search', 'labels', 'data', 'planRevenue', 'plans'
        ));
    }

    // ── Payment Detail ──
    public function show($id)
    {
        $payment = Payment::with(['user', 'plan'])->findOrFail($id);
        return view('admin.payments.show', compact('payment'));
    }

    // ── Add Manual Payment ──
    public function storeManual(Request $request)
    {
        $request->validate([
            'user_id'  => 'required|exists:users,id',
            'plan_id'  => 'required|exists:subscription_plans,id',
            'amount'   => 'required|numeric|min:0',
            'notes'    => 'nullable|string',
            'paid_at'  => 'nullable|date',
        ]);

        $plan = SubscriptionPlan::findOrFail($request->plan_id);
        $user = User::findOrFail($request->user_id);

        // Create payment record
        Payment::create([
            'user_id'        => $user->id,
            'plan_id'        => $plan->id,
            'transaction_id' => 'MANUAL-' . strtoupper(uniqid()),
            'amount'         => $request->amount,
            'currency'       => 'USD',
            'payment_method' => 'manual',
            'status'         => 'paid',
            'notes'          => $request->notes,
            'paid_at'        => $request->paid_at ?? now(),
        ]);

        // Activate subscription
        $user->update([
            'is_premium'         => true,
            'premium_expires_at' => now()->addDays($plan->duration_days),
        ]);

        UserSubscription::create([
            'user_id'        => $user->id,
            'plan_id'        => $plan->id,
            'amount_paid'    => $request->amount,
            'payment_method' => 'manual',
            'status'         => 'active',
            'starts_at'      => now(),
            'expires_at'     => $plan->duration_days > 0 ? now()->addDays($plan->duration_days) : null,
        ]);

        return back()->with('success', '✅ Manual payment added for ' . $user->name);
    }

    // ── Export to CSV ──
    public function export(Request $request)
    {
        $filter = $request->get('filter', 'all');

        $query = Payment::with(['user', 'plan']);
        if ($filter !== 'all') {
            $query->where('status', $filter);
        }
        $payments = $query->latest()->get();

        $filename = 'payments_' . now()->format('Y_m_d') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($payments) {
            $file = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($file, [
                'ID', 'User Name', 'User Email', 'Plan',
                'Amount', 'Currency', 'Method', 'Status',
                'Transaction ID', 'Paid At', 'Notes'
            ]);

            foreach ($payments as $p) {
                fputcsv($file, [
                    $p->id,
                    $p->user?->name ?? 'Deleted',
                    $p->user?->email ?? '',
                    $p->plan?->name ?? 'N/A',
                    $p->amount,
                    $p->currency,
                    $p->payment_method,
                    $p->status,
                    $p->transaction_id ?? '',
                    $p->paid_at?->format('Y-m-d H:i:s') ?? '',
                    $p->notes ?? '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}