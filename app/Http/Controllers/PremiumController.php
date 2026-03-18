<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class PremiumController extends Controller
{
    // ── Plans config ─────────────────────────────────────────
    // Once you have Stripe, replace the price_xxx values with
    // your actual Stripe Price IDs from your Stripe dashboard.
    // For now the amounts/months are defined here for reference.
    private array $plans = [
        'monthly' => [
            'label'       => '1 Month',
            'amount'      => 400,   // in cents = $4.00
            'months'      => 1,
            'stripe_price'=> 'price_monthly_placeholder',  // ← replace with real Stripe Price ID
        ],
        'quarterly' => [
            'label'       => '3 Months',
            'amount'      => 600,   // in cents = $6.00
            'months'      => 3,
            'stripe_price'=> 'price_quarterly_placeholder', // ← replace with real Stripe Price ID
        ],
        'biannual' => [
            'label'       => '6 Months',
            'amount'      => 800,   // in cents = $8.00
            'months'      => 6,
            'stripe_price'=> 'price_biannual_placeholder',  // ← replace with real Stripe Price ID
        ],
    ];

    // ── Show plans page ───────────────────────────────────────
    public function index()
    {
        return view('user.premium.plans');
    }

    // ── Create Stripe Checkout Session ───────────────────────
    public function checkout(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:monthly,quarterly,biannual',
        ]);

        $plan = $this->plans[$request->plan];

        // Set Stripe secret key
        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $session = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency'     => 'usd',
                        'unit_amount'  => $plan['amount'],
                        'product_data' => [
                            'name'        => 'Fobia Premium — ' . $plan['label'],
                            'description' => 'Unlock all countries, advanced filters & 10 chats/month',
                        ],
                    ],
                    'quantity' => 1,
                ]],
                'mode'        => 'payment',
                'metadata'    => [
                    'user_id' => Auth::id(),
                    'plan'    => $request->plan,
                    'months'  => $plan['months'],
                ],
                'success_url' => route('user.premium.success') . '?session_id={CHECKOUT_SESSION_ID}&plan=' . $request->plan,
                'cancel_url'  => route('user.premium.plans'),
            ]);

            return redirect($session->url);

        } catch (\Exception $e) {
            return redirect()->route('user.premium.plans')
                ->with('error', 'Payment could not be initiated. Please try again.');
        }
        
    }

    // ── Success page (after Stripe redirects back) ────────────
    public function success(Request $request)
    {
        $plan      = $request->query('plan', 'monthly');
        $sessionId = $request->query('session_id');
        $planData  = $this->plans[$plan] ?? $this->plans['monthly'];

        // If we have a real session ID, verify with Stripe and activate premium
        if ($sessionId && str_starts_with($sessionId, 'cs_')) {
            try {
                Stripe::setApiKey(config('services.stripe.secret'));
                $session = StripeSession::retrieve($sessionId);

                if ($session->payment_status === 'paid') {
                    $this->activatePremium(Auth::user(), $planData['months']);
                }
            } catch (\Exception $e) {
                // Log silently — don't show error on success page
                \Log::error('Stripe success verification failed: ' . $e->getMessage());
            }
        }

        $expiresAt = Carbon::now()->addMonths($planData['months']);

        return view('user.premium.success', [
            'plan'      => $plan,
            'planData'  => $planData,
            'expiresAt' => $expiresAt,
        ]);
    }

    // ── Cancel (user cancelled on Stripe page) ────────────────
    public function cancel()
    {
        return redirect()->route('user.premium.plans')
            ->with('error', 'Payment was cancelled. You can try again anytime.');
    }

    // ── Stripe Webhook (for reliable payment confirmation) ────
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

        // Handle successful payment
        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            if ($session->payment_status === 'paid') {
                $userId = $session->metadata->user_id;
                $months = (int) $session->metadata->months;

                $user = \App\Models\User::find($userId);
                if ($user) {
                    $this->activatePremium($user, $months);
                }
            }
        }

        return response()->json(['status' => 'ok']);
    }

    // ── Helper: activate premium on user ─────────────────────
    private function activatePremium(\App\Models\User $user, int $months): void
    {
        // If already premium, extend from current expiry; otherwise from now
        $base = ($user->is_premium && $user->premium_expires_at?->isFuture())
            ? $user->premium_expires_at
            : Carbon::now();

        $user->update([
            'is_premium'          => true,
            'premium_expires_at'  => $base->addMonths($months),
        ]);
    }
}