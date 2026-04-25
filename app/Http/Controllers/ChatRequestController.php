<?php

namespace App\Http\Controllers;

use App\Models\ChatRequest;
use App\Models\MatchRequest;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatRequestController extends Controller
{
    public function store(Request $request, User $user)
    {
        $request->validate([
            'message' => ['nullable', 'string', 'max:500'],
        ]);

        $authId = Auth::id();

        if ($user->id === $authId) {
            return back()->with('error', 'Invalid request.');
        }

        if (! Proposal::hasAcceptedBetween($authId, $user->id)) {
            return back()->with('error', 'You can send a chat request only after the marriage proposal is accepted.');
        }

        if (MatchRequest::messagingUnlockedBetween($authId, $user->id)) {
            return redirect()->route('user.chat.with', $user->id);
        }

        if (ChatRequest::pendingBetween($authId, $user->id)) {
            return back()->with('error', 'A chat request is already pending between you two.');
        }

        ChatRequest::create([
            'sender_id'   => $authId,
            'receiver_id' => $user->id,
            'message'     => $request->input('message'),
            'status'      => 'pending',
        ]);

        return back()->with('success', 'Chat request sent. They can accept it under Requests → Chat.');
    }

    public function accept(ChatRequest $chatRequest)
    {
        if ($chatRequest->receiver_id !== Auth::id()) {
            abort(403);
        }

        if (! $chatRequest->isPending()) {
            return back()->with('error', 'This chat request is no longer pending.');
        }

        $chatRequest->update([
            'status'       => 'accepted',
            'responded_at' => now(),
        ]);

        MatchRequest::updateOrCreate(
            [
                'sender_id'   => $chatRequest->sender_id,
                'receiver_id' => $chatRequest->receiver_id,
            ],
            ['status' => 'accepted']
        );

        return back()->with('success', 'Chat request accepted. You can chat now.');
    }

    public function reject(ChatRequest $chatRequest)
    {
        if ($chatRequest->receiver_id !== Auth::id()) {
            abort(403);
        }

        if (! $chatRequest->isPending()) {
            return back()->with('error', 'This chat request is no longer pending.');
        }

        $chatRequest->update([
            'status'       => 'rejected',
            'responded_at' => now(),
        ]);

        return back()->with('success', 'Chat request declined.');
    }
}
