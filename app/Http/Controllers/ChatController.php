<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\MatchRequest;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
public function index(Request $request)
{
    $authId = Auth::id();
    $userId = $request->query('user_id'); // ← add this

    if (!$userId) {
        // No user selected yet, just show the sidebar
        $matchedUsers = MatchRequest::where('status', 'accepted')
            ->where(function ($q) use ($authId) {
                $q->where('sender_id', $authId)
                  ->orWhere('receiver_id', $authId);
            })
            ->get()
            ->map(function ($match) use ($authId) {
                return $match->sender_id == $authId
                    ? $match->receiver
                    : $match->sender;
            });

        return view('user.chat', compact('matchedUsers'))
            ->with(['messages' => collect(), 'userId' => null]);
    }

    // Check if match accepted
    $match = MatchRequest::where(function ($q) use ($authId, $userId) {
        $q->where('sender_id', $authId)
          ->where('receiver_id', $userId);
    })->orWhere(function ($q) use ($authId, $userId) {
        $q->where('sender_id', $userId)
          ->where('receiver_id', $authId);
    })->where('status', 'accepted')->first();

    if (!$match) {
        abort(403, 'Chat not allowed.');
    }

    $messages = Message::where(function ($q) use ($authId, $userId) {
        $q->where('sender_id', $authId)
          ->where('receiver_id', $userId);
    })->orWhere(function ($q) use ($authId, $userId) {
        $q->where('sender_id', $userId)
          ->where('receiver_id', $authId);
    })
    ->orderBy('created_at')
    ->get();

    $matchedUsers = MatchRequest::where('status', 'accepted')
        ->where(function ($q) use ($authId) {
            $q->where('sender_id', $authId)
              ->orWhere('receiver_id', $authId);
        })
        ->get()
        ->map(function ($match) use ($authId) {
            return $match->sender_id == $authId
                ? $match->receiver
                : $match->sender;
        });

    return view('user.chat', compact('messages', 'userId', 'matchedUsers'));
}

public function send(Request $request, $userId)
{
    Message::create([
        'sender_id' => Auth::id(),
        'receiver_id' => $userId,
        'message' => $request->message,
    ]);

    return back();
}
}
