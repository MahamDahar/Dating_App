<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\EmailCampaign;
use App\Models\PushCampaign;
use App\Models\PromoCode;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class MarketingController extends Controller
{
    // ── Main Marketing Page ──
    public function index()
    {
        $emailCampaigns = EmailCampaign::latest()->get();
        $pushCampaigns  = PushCampaign::latest()->get();
        $promoCodes     = PromoCode::latest()->get();
        $announcements  = Announcement::latest()->get();

        $stats = [
            'emails_sent' => EmailCampaign::where('status', 'sent')->sum('sent_count'),
            'push_sent'   => PushCampaign::where('status', 'sent')->sum('sent_count'),
            'promo_codes' => PromoCode::where('is_active', true)->count(),
            'banners'     => Announcement::where('is_active', true)->count(),
        ];

        return view('admin.marketing.index', compact(
            'emailCampaigns', 'pushCampaigns',
            'promoCodes', 'announcements', 'stats'
        ));
    }

    // ════════════════════════════
    // EMAIL CAMPAIGNS
    // ════════════════════════════

    public function createEmail()
    {
        return view('admin.marketing.create-email');
    }

    public function sendEmail(Request $request)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body'    => 'required|string',
            'target'  => 'required|in:all,premium,free',
        ]);

        // Get target users
        $users = $this->getTargetUsers($request->target);

        // Create campaign record
        $campaign = EmailCampaign::create([
            'title'      => $request->title,
            'subject'    => $request->subject,
            'body'       => $request->body,
            'target'     => $request->target,
            'status'     => 'sent',
            'sent_count' => $users->count(),
            'sent_at'    => now(),
        ]);

        // Send emails (in real app use Queue)
        foreach ($users as $user) {
            try {
                Mail::send([], [], function ($message) use ($user, $request) {
                    $message->to($user->email, $user->name)
                            ->subject($request->subject)
                            ->html($request->body);
                });
            } catch (\Exception $e) {
                // Log and continue
                \Log::error('Email failed to ' . $user->email . ': ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.marketing.index')
            ->with('success', "✅ Email sent to {$users->count()} users!");
    }

    // ════════════════════════════
    // PUSH NOTIFICATIONS
    // ════════════════════════════

    public function sendPush(Request $request)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'message' => 'required|string',
            'target'  => 'required|in:all,premium,free',
        ]);

        $users = $this->getTargetUsers($request->target);

        PushCampaign::create([
            'title'      => $request->title,
            'message'    => $request->message,
            'target'     => $request->target,
            'status'     => 'sent',
            'sent_count' => $users->count(),
            'sent_at'    => now(),
        ]);

        // In real app: integrate Firebase FCM here

        return redirect()->route('admin.marketing.index')
            ->with('success', "✅ Push notification sent to {$users->count()} users!");
    }

    // ════════════════════════════
    // PROMO CODES
    // ════════════════════════════

    public function storePromo(Request $request)
    {
        $request->validate([
            'code'           => 'required|string|unique:promo_codes,code|max:20',
            'description'    => 'nullable|string',
            'discount_type'  => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:1',
            'max_uses'       => 'nullable|integer|min:1',
            'expires_at'     => 'nullable|date',
        ]);

        PromoCode::create($request->only([
            'code', 'description', 'discount_type',
            'discount_value', 'max_uses', 'expires_at',
        ]));

        return back()->with('success', '✅ Promo code created!');
    }

    public function togglePromo($id)
    {
        $promo = PromoCode::findOrFail($id);
        $promo->update(['is_active' => !$promo->is_active]);
        return back()->with('success', 'Promo code ' . ($promo->is_active ? 'enabled' : 'disabled'));
    }

    public function deletePromo($id)
    {
        PromoCode::findOrFail($id)->delete();
        return back()->with('success', 'Promo code deleted.');
    }

    // ════════════════════════════
    // ANNOUNCEMENTS / BANNERS
    // ════════════════════════════

    public function storeAnnouncement(Request $request)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'message' => 'required|string',
            'type'    => 'required|in:info,warning,success,danger',
            'target'  => 'required|in:all,premium,free',
        ]);

        Announcement::create([
            'title'     => $request->title,
            'message'   => $request->message,
            'type'      => $request->type,
            'target'    => $request->target,
            'is_active' => true,
            'starts_at' => $request->starts_at ?? now(),
            'ends_at'   => $request->ends_at,
        ]);

        return back()->with('success', '✅ Announcement created!');
    }

    public function toggleAnnouncement($id)
    {
        $ann = Announcement::findOrFail($id);
        $ann->update(['is_active' => !$ann->is_active]);
        return back()->with('success', 'Announcement ' . ($ann->is_active ? 'activated' : 'deactivated'));
    }

    public function deleteAnnouncement($id)
    {
        Announcement::findOrFail($id)->delete();
        return back()->with('success', 'Announcement deleted.');
    }

    // ── Helper: get users by target ──
    private function getTargetUsers(string $target)
    {
        $query = User::where('role', 'user');

        if ($target === 'premium') {
            $query->where('is_premium', true);
        } elseif ($target === 'free') {
            $query->where('is_premium', false);
        }

        return $query->get();
    }
}