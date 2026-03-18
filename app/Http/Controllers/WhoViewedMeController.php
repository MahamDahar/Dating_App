<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ProfileView;

class WhoViewedMeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Fetch all viewers with their profile & user data
        $viewers = ProfileView::where('viewed_id', $user->id)
            ->with(['viewer.profile'])
            ->orderByDesc('viewed_at')
            ->get();

        // Mark all as seen now that user is viewing the page
        ProfileView::where('viewed_id', $user->id)
            ->where('seen', false)
            ->update(['seen' => true]);

        // Total count this week
        $weeklyCount = ProfileView::where('viewed_id', $user->id)
            ->where('viewed_at', '>=', now()->subDays(7))
            ->count();

        return view('user.who-viewed-me', [
            'viewers'     => $viewers,
            'weeklyCount' => $weeklyCount,
            'isPremium'   => $user->isPremium(),
        ]);
    }
}