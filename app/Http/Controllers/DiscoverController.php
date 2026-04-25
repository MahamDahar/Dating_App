<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use App\Models\Like;
use App\Models\MatchRequest;
use App\Models\SavedProfile;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DiscoverController extends Controller
{
    private function scoreProfiles($profiles, UserProfile $me, array $myInterests)
    {
        return $profiles->map(function ($profile) use ($me, $myInterests) {
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

            $theirInterests = $profile->interests ? explode(',', $profile->interests) : [];
            $commonInterests = array_values(array_intersect($myInterests, $theirInterests));
            $common = count($commonInterests);
            $score += min($common * 2, 10);

            if ($common > 0) {
                $reasons[] = ['icon' => '🎯', 'text' => $common . ' shared interest' . ($common > 1 ? 's' : '')];
            }

            $profile->match_score = $score;
            $profile->match_pct = min(round($score), 100);
            $profile->match_reasons = $reasons;
            $profile->common_interests = $commonInterests;
            $dob = $profile->date_of_birth ?? $profile->user?->birthday ?? null;
            $profile->age_years = $dob ? Carbon::parse($dob)->age : null;

            return $profile;
        });
    }

    private function applyFilters($matches, Request $request, string $selectedCountry)
    {
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
            $needle = trim((string) $request->city);
            $matches = $matches->filter(function ($p) use ($needle) {
                $city = trim((string) ($p->city ?? ''));
                $grewUp = trim((string) ($p->grew_up ?? ''));
                return stripos($city, $needle) !== false || stripos($grewUp, $needle) !== false;
            })->values();
        }
        if ($request->filled('nationality')) {
            $matches = $matches->filter(fn($p) => stripos($p->nationality ?? '', $request->nationality) !== false)->values();
        }

        $minAge = $request->filled('min_age') ? max(18, (int) $request->input('min_age')) : null;
        $maxAge = $request->filled('max_age') ? min(99, (int) $request->input('max_age')) : null;

        if ($minAge !== null || $maxAge !== null) {
            if ($minAge !== null && $maxAge !== null && $minAge > $maxAge) {
                [$minAge, $maxAge] = [$maxAge, $minAge];
            }

            $matches = $matches->filter(function ($p) use ($minAge, $maxAge) {
                if ($p->age_years === null) {
                    return false;
                }
                if ($minAge !== null && $p->age_years < $minAge) {
                    return false;
                }
                if ($maxAge !== null && $p->age_years > $maxAge) {
                    return false;
                }
                return true;
            })->values();
        }

        if ($selectedCountry !== '') {
            $matches = $matches->filter(function ($p) use ($selectedCountry) {
                $country = trim((string) ($p->country ?? ''));
                $nationality = trim((string) ($p->nationality ?? ''));
                return strcasecmp($country, $selectedCountry) === 0
                    || strcasecmp($nationality, $selectedCountry) === 0;
            })->values();
        }

        return $matches;
    }

    public function index(Request $request)
    {
        $me = UserProfile::where('user_id', auth()->id())->first();

        if (!$me) {
            return redirect()->route('user.profile')
                   ->with('error', 'Please complete your profile first.');
        }

        $myInterests = $me->interests ? explode(',', $me->interests) : [];
        $isPremiumUser = (bool) auth()->user()?->isPremium();

        $likedIds = Like::where('liker_id', auth()->id())
            ->pluck('liked_id')
            ->toArray();

        $savedIds = SavedProfile::where('user_id', auth()->id())
            ->pluck('saved_user_id')
            ->toArray();

        // Hide users from Discover while an accepted chat/match is active.
        $activeChatUserIds = MatchRequest::query()
            ->where('status', 'accepted')
            ->where(function ($q) {
                $q->where('sender_id', auth()->id())
                    ->orWhere('receiver_id', auth()->id());
            })
            ->get(['sender_id', 'receiver_id'])
            ->flatMap(function ($row) {
                return [(int) $row->sender_id, (int) $row->receiver_id];
            })
            ->reject(fn (int $id) => $id === (int) auth()->id())
            ->unique()
            ->values()
            ->all();

        // Show only people whose `gender` matches my `looking_for` (same rule as Marriage swipe).
        $authUser = auth()->user();
        $desiredGender = trim((string) ($authUser->looking_for ?? ''));
        if (! in_array($desiredGender, ['Man', 'Woman'], true)) {
            $desiredGender = ($authUser->gender === 'Man') ? 'Woman' : (($authUser->gender === 'Woman') ? 'Man' : '');
        }

        // Country-access rules:
        // - Free user: only 1 country (their own profile country)
        // - Premium user: up to 10 countries
        $profileCountry = trim((string) ($me->country ?: $me->nationality ?: ''));
        $premiumCountries = [
            'Pakistan',
            'India',
            'Bangladesh',
            'UAE',
            'Dubai',
            'Saudi Arabia',
            'Germany',
            'UK',
            'USA',
            'Canada',
        ];

        $allowedCountries = $isPremiumUser
            ? $premiumCountries
            : [($profileCountry !== '' ? $profileCountry : 'Pakistan')];

        $selectedCountry = trim((string) $request->get('country', ''));
        if ($selectedCountry === '' || !in_array($selectedCountry, $allowedCountries, true)) {
            $selectedCountry = $allowedCountries[0];
        }

        $baseGenderScoped = UserProfile::with(['user', 'photos'])
            ->where('user_id', '!=', auth()->id())
            ->when(! empty($activeChatUserIds), fn ($q) => $q->whereNotIn('user_id', $activeChatUserIds))
            ->whereHas('user', function ($q) use ($desiredGender) {
                if ($desiredGender !== '') {
                    $q->where('gender', $desiredGender);
                } else {
                    $q->whereRaw('1 = 0');
                }
            })
            ->get();

        $scoredPrimary = $this->scoreProfiles($baseGenderScoped, $me, $myInterests);

        // Primary: strong + weak matches (exclude absolute zero to keep relevance)
        $matches = $scoredPrimary
            ->filter(fn($p) => $p->match_score > 0)
            ->sortByDesc('match_score')
            ->values();

        $matches = $this->applyFilters($matches, $request, $selectedCountry);

        // If filters + country are too strict, relax country only — never mix in other genders.
        if ($matches->isEmpty()) {
            $fallbackLevel1 = $scoredPrimary
                ->filter(fn($p) => $p->match_score > 0)
                ->sortByDesc('match_score')
                ->values();
            $matches = $this->applyFilters($fallbackLevel1, $request, '');

            if ($matches->isEmpty()) {
                $matches = $this->applyFilters(
                    $scoredPrimary->sortByDesc('match_score')->values(),
                    $request,
                    ''
                );
            }
        }

        // Final cap for performance and UX
        $matches = $matches->take(20)->values();

        $minAge = $request->filled('min_age') ? max(18, (int) $request->input('min_age')) : null;
        $maxAge = $request->filled('max_age') ? min(99, (int) $request->input('max_age')) : null;

        $totalFound  = $matches->count();
        $highMatches = $matches->filter(fn($p) => $p->match_pct >= 70)->count();

        return view('user.discover', compact(
            'matches',
            'me',
            'totalFound',
            'highMatches',
            'allowedCountries',
            'selectedCountry',
            'isPremiumUser',
            'minAge',
            'maxAge',
            'likedIds',
            'savedIds'
        ));
    }
}