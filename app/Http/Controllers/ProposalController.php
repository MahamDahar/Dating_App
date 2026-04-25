<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProposalController extends Controller {

    private static function proposalUserWith(): array
    {
        return ['profile', 'profilePhotos'];
    }

    // Proposals I sent
    public function sent() {
        $proposals = Proposal::with(['receiver' => fn ($q) => $q->with(self::proposalUserWith())])
            ->where('sender_id', Auth::id())
            ->latest()
            ->get();
        return view('user.proposals.sent', compact('proposals'));
    }

    // Proposals I received
    public function received() {
        $proposals = Proposal::with(['sender' => fn ($q) => $q->with(self::proposalUserWith())])
            ->where('receiver_id', Auth::id())
            ->latest()
            ->get();
        return view('user.proposals.received', compact('proposals'));
    }

    // Send a proposal from profile page
    public function send(Request $request, User $user) {
        $request->validate([
            'message' => 'required|string|max:500',
        ]);

        // Can't send to yourself
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot send a proposal to yourself.');
        }

        // Check if already sent
        $exists = Proposal::where('sender_id', Auth::id())
                          ->where('receiver_id', $user->id)
                          ->exists();

        if ($exists) {
            return back()->with('error', 'You have already sent a proposal to this person.');
        }

        Proposal::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $user->id,
            'message'     => $request->message,
            'status'      => 'pending',
        ]);

        return back()->with('success', 'Your proposal has been sent successfully!');
    }

    // Accept a proposal
    public function accept(Proposal $proposal) {
        // Make sure I am the receiver
        if ($proposal->receiver_id !== Auth::id()) {
            abort(403);
        }

        $proposal->update([
            'status'      => 'accepted',
            'accepted_at' => now(),
        ]);

        return back()->with(
            'success',
            'Proposal accepted. Send or accept a chat request under Requests → Chat to start messaging.'
        );
    }

    // Reject a proposal
    public function reject(Proposal $proposal) {
        if ($proposal->receiver_id !== Auth::id()) {
            abort(403);
        }

        $proposal->update([
            'status'      => 'rejected',
            'rejected_at' => now(),
        ]);

        return back()->with('success', 'Proposal rejected.');
    }

    // Block sender
    public function block(Proposal $proposal) {
        if ($proposal->receiver_id !== Auth::id()) {
            abort(403);
        }

        // Block the sender
        \App\Models\BlockedUser::firstOrCreate([
            'user_id'    => Auth::id(),
            'blocked_id' => $proposal->sender_id,
        ]);

        // Also reject the proposal
        $proposal->update([
            'status'      => 'rejected',
            'rejected_at' => now(),
        ]);

        return back()->with('success', 'User has been blocked.');
    }
}