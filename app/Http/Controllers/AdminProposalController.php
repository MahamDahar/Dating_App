<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Http\Request;

class AdminProposalController extends Controller
{
    public function index(Request $request)
    {
        $query = Proposal::with([
            'sender' => fn ($q) => $q->with(['profile', 'profilePhotos']),
            'receiver' => fn ($q) => $q->with(['profile', 'profilePhotos']),
        ]);

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Search by user name
        if ($request->search) {
            $userIds = User::where('name', 'like', '%' . $request->search . '%')
                          ->orWhere('email', 'like', '%' . $request->search . '%')
                          ->pluck('id');
            $query->where(function($q) use ($userIds) {
                $q->whereIn('sender_id',   $userIds)
                  ->orWhereIn('receiver_id', $userIds);
            });
        }

        $proposals = $query->latest()->paginate(20);

        // Stats
        $totalProposals    = Proposal::count();
        $pendingProposals  = Proposal::where('status', 'pending')->count();
        $acceptedProposals = Proposal::where('status', 'accepted')->count();
        $rejectedProposals = Proposal::where('status', 'rejected')->count();

        // Spam detection — users who sent more than 5 proposals
        $spamSenders = Proposal::selectRaw('sender_id, COUNT(*) as proposal_count')
            ->groupBy('sender_id')
            ->having('proposal_count', '>=', 5)
            ->orderByDesc('proposal_count')
            ->with('sender')
            ->take(5)
            ->get();

        return view('admin.proposals.index', compact(
            'proposals',
            'totalProposals', 'pendingProposals',
            'acceptedProposals', 'rejectedProposals',
            'spamSenders'
        ));
    }

    // Delete a proposal
    public function destroy(Proposal $proposal)
    {
        $proposal->delete();
        return back()->with('success', 'Proposal deleted successfully.');
    }
}