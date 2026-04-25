<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller {

    public function index() {
        $authId = Auth::id();

        // Profiles I liked
        $likesSent = Like::with('liked')
            ->where('liker_id', $authId)
            ->latest()
            ->get();

        // Profiles who liked me
        $likesReceived = Like::with('liker')
            ->where('liked_id', $authId)
            ->latest()
            ->get();

        // Mutual likes — I liked them AND they liked me
        $sentIds     = $likesSent->pluck('liked_id');
        $receivedIds = $likesReceived->pluck('liker_id');
        $mutualIds   = $sentIds->intersect($receivedIds);

        $mutualLikes = Like::with('liked')
            ->where('liker_id', $authId)
            ->whereIn('liked_id', $mutualIds)
            ->latest()
            ->get();

        return view('user.likes.index', compact(
            'likesSent', 'likesReceived', 'mutualLikes'
        ));
    }

    // Toggle like / unlike
    public function toggle(Request $request, User $user) {
        $authId = Auth::id();

        if ($user->id === $authId) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'error' => 'You cannot like yourself.'], 422);
            }

            return back()->with('error', 'You cannot like yourself.');
        }

        $existing = Like::where('liker_id', $authId)
                        ->where('liked_id', $user->id)
                        ->first();

        if ($existing) {
            // Unlike
            $existing->delete();
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'liked' => false, 'message' => 'Like removed.']);
            }

            return back()->with('success', 'Like removed.');
        } else {
            // Like
            Like::create([
                'liker_id' => $authId,
                'liked_id' => $user->id,
            ]);
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'liked' => true, 'message' => 'Profile liked!']);
            }

            return back()->with('success', 'Profile liked!');
        }
    }
}