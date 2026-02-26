<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use Illuminate\Http\Request;

class DiscoverController extends Controller
{
    public function index(Request $request)
    {
        $me = UserProfile::where('user_id', auth()->id())->first();

        if (!$me) {
            return redirect()->route('user.userprofile')
                   ->with('error', 'Pehle apni profile complete karo!');
        }

        $myInterests = $me->interests ? explode(',', $me->interests) : [];

        $matches = UserProfile::with('user')
            ->where('user_id', '!=', auth()->id())
            ->whereNotNull('sect')
            ->get()
            ->map(function ($profile) use ($me, $myInterests) {

                $score   = 0;
                $reasons = [];

                if ($profile->sect && $profile->sect === $me->sect) {
                    $score += 25;
                    $reasons[] = ['icon' => '🕌', 'text' => 'Same sect (' . $profile->sect . ')'];
                }

                if ($profile->religion_practice === $me->religion_practice) {
                    $score += 20;
                    $reasons[] = ['icon' => '📿', 'text' => 'Same level of religious practice'];
                }

                if ($profile->nationality && $profile->nationality === $me->nationality) {
                    $score += 10;
                    $reasons[] = ['icon' => '🌍', 'text' => 'Same nationality (' . $profile->nationality . ')'];
                }

                if ($profile->grew_up && $profile->grew_up === $me->grew_up) {
                    $score += 15;
                    $reasons[] = ['icon' => '🏙️', 'text' => 'Grew up in same city (' . $profile->grew_up . ')'];
                }

                if ($profile->education === $me->education) {
                    $score += 10;
                    $reasons[] = ['icon' => '🎓', 'text' => 'Same education level'];
                }

                if ($profile->marital_status === $me->marital_status) {
                    $score += 10;
                    $reasons[] = ['icon' => '💍', 'text' => 'Same marital status'];
                }

                if ($profile->born_muslim === $me->born_muslim) {
                    $score += 5;
                    $reasons[] = ['icon' => '☪️', 'text' => 'Both ' . ($me->born_muslim ? 'born Muslim' : 'reverts')];
                }

                $theirInterests = $profile->interests
                    ? explode(',', $profile->interests) : [];
                $commonInterests = array_values(array_intersect($myInterests, $theirInterests));
                $common = count($commonInterests);
                $score += min($common * 2, 10);

                if ($common > 0) {
                    $reasons[] = ['icon' => '🎯', 'text' => $common . ' shared interest' . ($common > 1 ? 's' : '')];
                }

                $profile->match_score    = $score;
                $profile->match_pct      = min(round($score), 100);
                $profile->match_reasons  = $reasons;
                $profile->common_interests = $commonInterests;

                return $profile;
            })
            ->filter(fn($p) => $p->match_score > 0)
            ->sortByDesc('match_score')
            ->take(20)
            ->values();

        // Apply filters
        if ($request->filled('sect')) {
            $matches = $matches->filter(fn($p) => $p->sect === $request->sect)->values();
        }
        if ($request->filled('education')) {
            $matches = $matches->filter(fn($p) => $p->education === $request->education)->values();
        }
        if ($request->filled('marital_status')) {
            $matches = $matches->filter(fn($p) => $p->marital_status === $request->marital_status)->values();
        }
        if ($request->filled('city')) {
            $matches = $matches->filter(fn($p) => stripos($p->grew_up ?? '', $request->city) !== false)->values();
        }
        if ($request->filled('nationality')) {
            $matches = $matches->filter(fn($p) => stripos($p->nationality ?? '', $request->nationality) !== false)->values();
        }

        $totalFound  = $matches->count();
        $highMatches = $matches->filter(fn($p) => $p->match_pct >= 70)->count();

        return view('user.discover', compact('matches', 'me', 'totalFound', 'highMatches'));
    }
}