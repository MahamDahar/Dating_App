<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\UserSubscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminSubscriptionController extends Controller
{
    // ── Subscriptions Overview (admin) ──
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $search = trim((string) $request->get('search', ''));

        $query = UserSubscription::with(['user', 'plan'])->latest('starts_at');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%");
                })->orWhereHas('plan', function ($pq) use ($search) {
                    $pq->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                });
            });
        }

        $subscriptions = $query->paginate(15)->withQueryString();

        $stats = [
            'active' => UserSubscription::where('status', 'active')->count(),
            'expired' => UserSubscription::where('status', 'expired')->count(),
            'cancelled' => UserSubscription::where('status', 'cancelled')->count(),
            'revenue_active' => UserSubscription::where('status', 'active')->sum('amount_paid'),
        ];

        return view('admin.subscriptions.index', compact(
            'subscriptions',
            'status',
            'search',
            'stats'
        ));
    }

    // ── User Subscription Detail (and activate/cancel) ──
    public function show(User $user)
    {
        $plans = SubscriptionPlan::active()->orderBy('sort_order')->get();

        $subscriptions = UserSubscription::with(['plan'])
            ->where('user_id', $user->id)
            ->orderByDesc('starts_at')
            ->get();

        $activeSubscription = $subscriptions->firstWhere('status', 'active');

        $payments = Payment::with(['plan'])
            ->where('user_id', $user->id)
            ->orderByDesc('paid_at')
            ->take(30)
            ->get();

        return view('admin.subscriptions.show', compact(
            'user',
            'plans',
            'subscriptions',
            'activeSubscription',
            'payments'
        ));
    }

    public function activate(Request $request, User $user)
    {
        $request->validate([
            'plan_id' => 'required|exists:subscription_plans,id',
            'amount_paid' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:2000',
            'starts_at' => 'nullable|date',
        ]);

        $plan = SubscriptionPlan::findOrFail($request->plan_id);
        $amountPaid = (float) $request->amount_paid;

        DB::transaction(function () use ($request, $user, $plan, $amountPaid) {
            $now = now();
            $startsAt = $request->filled('starts_at') ? Carbon::parse($request->input('starts_at')) : $now;

            // Use existing expiry (if still in future) so renewal extends correctly.
            $prevActive = UserSubscription::where('user_id', $user->id)
                ->where('status', 'active')
                ->orderByDesc('starts_at')
                ->first();

            $base = ($prevActive && $prevActive->expires_at && $prevActive->expires_at->isFuture())
                ? $prevActive->expires_at
                : $startsAt;

            // Cancel all currently active subscriptions so we keep a single active record.
            UserSubscription::where('user_id', $user->id)
                ->where('status', 'active')
                ->update([
                    'status' => 'cancelled',
                    'cancelled_at' => $now,
                ]);

            $expiresAt = $plan->duration_days > 0
                ? (clone $base)->addDays($plan->duration_days)
                : null;

            $user->update([
                'is_premium' => true,
                'premium_expires_at' => $expiresAt,
            ]);

            UserSubscription::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'amount_paid' => $amountPaid,
                'payment_method' => 'manual',
                'stripe_session_id' => null,
                'status' => 'active',
                'starts_at' => $startsAt,
                'expires_at' => $expiresAt,
                'cancelled_at' => null,
            ]);

            Payment::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'transaction_id' => 'MANUAL-SUB-' . $user->id . '-' . strtoupper(uniqid()),
                'amount' => $amountPaid,
                'currency' => 'USD',
                'payment_method' => 'manual',
                'status' => 'paid',
                'stripe_session_id' => null,
                'paid_at' => $now,
                'notes' => $request->input('notes'),
            ]);
        });

        return redirect()
            ->route('admin.subscriptions.users.show', $user->id)
            ->with('success', '✅ Subscription activated/renewed for ' . $user->name);
    }

    public function cancel(Request $request, User $user)
    {
        $active = UserSubscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->orderByDesc('starts_at')
            ->first();

        if (!$active) {
            return redirect()
                ->route('admin.subscriptions.users.show', $user->id)
                ->with('error', 'No active subscription found for ' . $user->name);
        }

        DB::transaction(function () use ($active, $user) {
            $now = now();

            $active->update([
                'status' => 'cancelled',
                'cancelled_at' => $now,
                'expires_at' => $now,
            ]);

            $user->update([
                'is_premium' => false,
                'premium_expires_at' => null,
            ]);
        });

        return redirect()
            ->route('admin.subscriptions.users.show', $user->id)
            ->with('success', 'Subscription cancelled for ' . $user->name);
    }
}

