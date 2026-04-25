<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\MatchRequest;
use App\Models\Message;
use App\Models\ProfileView;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ActivityController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // ── Likes ──
        $likesReceived = Like::with('liker')
            ->where('liked_id', $userId) // others liked me
            ->latest()
            ->take(10)
            ->get();

        $likesGiven = Like::with('liked')
            ->where('liker_id', $userId) // I liked others
            ->latest()
            ->take(10)
            ->get();

        $totalLikesReceived = Like::where('liked_id', $userId)->count();
        $totalLikesGiven    = Like::where('liker_id', $userId)->count();

        // ── Matches ──
        $matches = MatchRequest::with(['sender', 'receiver'])
            ->where(function ($q) use ($userId) {
                $q->where('sender_id', $userId)
                  ->orWhere('receiver_id', $userId);
            })
            ->where('status', 'accepted')
            ->latest()
            ->take(10)
            ->get();

        $totalMatches = $matches->count();

        // ── Profile Views ──
        $profileViews = ProfileView::with('viewer')
            ->where('viewed_id', $userId)
            ->latest()
            ->take(10)
            ->get();

        $totalProfileViews = ProfileView::where('viewed_id', $userId)->count();

        // ── Messages Summary ──
        $messagesSent     = Message::where('sender_id', $userId)->count();
        $messagesReceived = Message::where('receiver_id', $userId)->count();
        $totalMessages    = $messagesSent + $messagesReceived;

        // Recent conversations — last message per unique user
        $recentConversations = Message::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->orderByDesc('created_at')
            ->get()
            ->groupBy(function ($msg) use ($userId) {
                return $msg->sender_id === $userId ? $msg->receiver_id : $msg->sender_id;
            })
            ->take(5)
            ->map(function ($msgs) use ($userId) {
                $latest = $msgs->first();
                $otherId = $latest->sender_id === $userId ? $latest->receiver_id : $latest->sender_id;
                $other = User::find($otherId);
                if ($other) {
                    $other->last_message = $latest->body ?? $latest->message ?? '';
                }
                return $other;
            })
            ->filter();

        return view('user.activity', compact(
            'likesReceived',
            'likesGiven',
            'totalLikesReceived',
            'totalLikesGiven',
            'matches',
            'totalMatches',
            'profileViews',
            'totalProfileViews',
            'messagesSent',
            'messagesReceived',
            'totalMessages',
            'recentConversations'
        ));
    }
}