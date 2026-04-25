<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\Like;

class AdminMatchController extends Controller {

    public function index() {
        // Accepted proposals
        $acceptedProposals = Proposal::with(['sender', 'receiver'])
            ->where('status', 'accepted')
            ->latest()
            ->paginate(20);

        // Mutual likes
        $allLikes    = Like::with(['liker', 'liked'])->get();
        $likerIds    = $allLikes->pluck('liker_id');
        $likedIds    = $allLikes->pluck('liked_id');

        $mutualLikes = $allLikes->filter(function($like) use ($allLikes) {
            return $allLikes->where('liker_id', $like->liked_id)
                           ->where('liked_id',  $like->liker_id)
                           ->count() > 0;
        })->unique(function($like) {
            return collect([$like->liker_id, $like->liked_id])->sort()->implode('-');
        });

        $proposalMatches = $acceptedProposals->total();
        $likeMatches     = $mutualLikes->count();
        $totalMatches    = $proposalMatches + $likeMatches;

        return view('admin.matches.index', compact(
            'acceptedProposals', 'mutualLikes',
            'totalMatches', 'proposalMatches', 'likeMatches'
        ));
    }
}