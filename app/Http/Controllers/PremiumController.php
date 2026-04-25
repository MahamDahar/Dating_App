<?php

namespace App\Http\Controllers;

use App\Models\AdminNotification;
use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class PremiumController extends Controller
{
    // ── Show plans page — from DB ──
    public function index()
    {
        $activePlans = SubscriptionPlan::active()->orderBy('sort_order')->get();

        // User-side simplified pricing: only Free + one paid Premium plan.
        $freePlan = $activePlans->firstWhere('price', 0)
            ?? $activePlans->firstWhere('slug', 'free');

        $premiumPlan = $activePlans
            ->filter(fn($p) => (float) $p->price > 0)
            ->sortByDesc(fn($p) => $p->is_featured ? 1 : 0)
            ->sortBy('sort_order')
            ->firstWhere('slug', 'premium');

        if (!$premiumPlan) {
            $premiumPlan = $activePlans
                ->filter(fn($p) => (float) $p->price > 0)
                ->sortByDesc(fn($p) => $p->is_featured ? 1 : 0)
                ->sortBy('sort_order')
                ->first();
        }

        $plans = collect([$freePlan, $premiumPlan])->filter()->values();
        return view('user.premium.plans', compact('plans'));
    }

    // ── Create Stripe Checkout Session ──
    public function checkout(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:subscription_plans,id',
        ]);

        $plan = SubscriptionPlan::findOrFail($request->plan_id);

        // Free plan — no payment needed
        if ($plan->price == 0) {
            return redirect()->route('user.discover')
                ->with('success', 'You are on the Free plan!');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $session = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency'     => 'usd',
                        'unit_amount'  => (int)($plan->price * 100), // convert to cents
                        'product_data' => [
                            'name'        => 'Fobia ' . $plan->name . ' Plan',
                            'description' => $plan->description ?? 'Unlock premium features',
                        ],
                    ],
                    'quantity' => 1,
                ]],
                'mode'     => 'payment',
                'metadata' => [
                    'user_id'  => Auth::id(),
                    'plan_id'  => $plan->id,
                    'plan_slug'=> $plan->slug,
                    'days'     => $plan->duration_days,
                    'amount'   => $plan->price,
                ],
                'success_url' => route('user.premium.success') . '?session_id={CHECKOUT_SESSION_ID}&plan_id=' . $plan->id,
                'cancel_url'  => route('user.premium.plans'),
            ]);

            return redirect($session->url);

        } catch (\Exception $e) {
            return redirect()->route('user.premium.plans')
                ->with('error', 'Payment could not be initiated. Please try again.');
        }
    }

    // ── Success page ──
    public function success(Request $request)
    {
        $planId    = $request->query('plan_id');
        $sessionId = $request->query('session_id');
        $plan      = SubscriptionPlan::find($planId);

        if (!$plan) {
            return redirect()->route('user.premium.plans');
        }

        // Verify with Stripe and activate
        if ($sessionId && str_starts_with($sessionId, 'cs_')) {
            try {
                Stripe::setApiKey(config('services.stripe.secret'));
                $session = StripeSession::retrieve($sessionId);

                if ($session->payment_status === 'paid') {
                    $this->activatePremium(Auth::user(), $plan, $sessionId);
                }
            } catch (\Exception $e) {
                \Log::error('Stripe success verification failed: ' . $e->getMessage());
            }
        }

        return view('user.premium.success', [
            'plan'      => $plan,
            'expiresAt' => Carbon::now()->addDays($plan->duration_days),
        ]);
    }

    // ── Cancel ──
    public function cancel()
    {
        return redirect()->route('user.premium.plans')
            ->with('error', 'Payment was cancelled. You can try again anytime.');
    }

    // ── Stripe Webhook ──
    public function webhook(Request $request)
    {
        $payload   = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sigHeader,
                config('services.stripe.webhook')
            );
        } catch (SignatureVerificationException $e) {
            return response('Webhook signature verification failed.', 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            if ($session->payment_status === 'paid') {
                $userId  = $session->metadata->user_id;
                $planId  = $session->metadata->plan_id;
                $amount  = $session->metadata->amount;

                $user = \App\Models\User::find($userId);
                $plan = SubscriptionPlan::find($planId);

                if ($user && $plan) {
                    $this->activatePremium($user, $plan, $session->id, $amount);
                }
            }
        }

        return response()->json(['status' => 'ok']);
    }

    // ── Helper: activate premium ──
    private function activatePremium(
    \App\Models\User $user,
    \App\Models\SubscriptionPlan $plan,
    string $sessionId = null,
    float $amountPaid = null
): void {
    $base = ($user->is_premium && $user->premium_expires_at?->isFuture())
        ? $user->premium_expires_at
        : Carbon::now();
 
    $expiresAt = $plan->duration_days > 0
        ? $base->addDays($plan->duration_days)
        : null;
 
    // ── Update user ──
    $user->update([
        'is_premium'         => true,
        'premium_expires_at' => $expiresAt,
    ]);
 
    // ── Save to user_subscriptions ──
    \App\Models\UserSubscription::create([
        'user_id'           => $user->id,
        'plan_id'           => $plan->id,
        'amount_paid'       => $amountPaid ?? $plan->price,
        'payment_method'    => 'stripe',
        'stripe_session_id' => $sessionId,
        'status'            => 'active',
        'starts_at'         => now(),
        'expires_at'        => $expiresAt,
    ]);
 
    // ── Save to payments table ──
    \App\Models\Payment::create([
        'user_id'           => $user->id,
        'plan_id'           => $plan->id,
        'transaction_id'    => $sessionId,
        'amount'            => $amountPaid ?? $plan->price,
        'currency'          => 'USD',
        'payment_method'    => 'stripe',
        'status'            => 'paid',
        'stripe_session_id' => $sessionId,
        'paid_at'           => now(),
    ]);
 
    // ── Admin notification ──
    \App\Models\AdminNotification::newSubscription($user, $plan->name);
}
}