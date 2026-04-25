<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ContentFlag;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;

class AdminContentModerationController extends Controller
{
    // Offensive keywords to auto-flag bios
    const OFFENSIVE_KEYWORDS = [
        'hate', 'kill', 'abuse', 'nude', 'sex',
        'porn', 'naked', 'explicit', 'vulgar', 'offensive'
    ];

    public function index(Request $request)
    {
        // ── Auto flag suspicious bios ──
        $this->autoFlagContent();

        // Pending flags
        $photoFlags    = ContentFlag::with('user')->where('type', 'photo')
                            ->where('status', 'pending')->latest()->get();
        $bioFlags      = ContentFlag::with('user')->where('type', 'bio')
                            ->where('status', 'pending')->latest()->get();
        $usernameFlags = ContentFlag::with('user')->where('type', 'username')
                            ->where('status', 'pending')->latest()->get();

        // Stats
        $totalPending  = ContentFlag::where('status', 'pending')->count();
        $totalApproved = ContentFlag::where('status', 'approved')->count();
        $totalRejected = ContentFlag::where('status', 'rejected')->count();

        return view('admin.content_moderation.index', compact(
            'photoFlags', 'bioFlags', 'usernameFlags',
            'totalPending', 'totalApproved', 'totalRejected'
        ));
    }

    // Auto scan bios for offensive keywords
    private function autoFlagContent()
    {
        $users = User::whereNotNull('id')->get();

        foreach ($users as $user) {
            $profile = $user->profile;
            if (!$profile) continue;

            // Check bio
            if ($profile->bio) {
                foreach (self::OFFENSIVE_KEYWORDS as $keyword) {
                    if (stripos($profile->bio, $keyword) !== false) {
                        ContentFlag::firstOrCreate(
                            ['user_id' => $user->id, 'type' => 'bio', 'status' => 'pending'],
                            ['content' => $profile->bio]
                        );
                        break;
                    }
                }
            }

            // Check username
            if ($user->username) {
                foreach (self::OFFENSIVE_KEYWORDS as $keyword) {
                    if (stripos($user->username, $keyword) !== false) {
                        ContentFlag::firstOrCreate(
                            ['user_id' => $user->id, 'type' => 'username', 'status' => 'pending'],
                            ['content' => $user->username]
                        );
                        break;
                    }
                }
            }
        }
    }

    // Approve a flag (content is ok)
    public function approve(ContentFlag $flag)
    {
        $flag->update([
            'status'     => 'approved',
            'admin_note' => request('note') ?? 'Content approved by admin.',
        ]);

        return back()->with('success', 'Content approved.');
    }

    // Reject a flag (remove offensive content)
    public function reject(ContentFlag $flag)
    {
        $flag->update([
            'status'     => 'rejected',
            'admin_note' => request('note') ?? 'Content removed for violating guidelines.',
        ]);

        // Notify user
        Notification::create([
            'user_id' => $flag->user_id,
            'type'    => 'warning',
            'title'   => '⚠️ Content Removed',
            'message' => $flag->type === 'photo'
                ? 'Your profile photo has been removed for violating our community guidelines. Please upload an appropriate photo.'
                : 'Your ' . $flag->type . ' has been removed for violating our community guidelines. Please update it.',
        ]);

        return back()->with('success', 'Content rejected and user notified.');
    }

    // Manually flag a photo
    public function flagPhoto(Request $request)
    {
        $request->validate([
            'user_id'    => 'required|exists:users,id',
            'photo_path' => 'required|string',
        ]);

        ContentFlag::create([
            'user_id'    => $request->user_id,
            'type'       => 'photo',
            'photo_path' => $request->photo_path,
            'status'     => 'pending',
        ]);

        return back()->with('success', 'Photo flagged for review.');
    }
}