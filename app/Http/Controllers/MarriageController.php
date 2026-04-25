<?php

namespace App\Http\Controllers;

use App\Models\MarriageFavourite;
use App\Models\MarriageLike;
use App\Models\MarriageMatch;
use App\Models\MarriagePass;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MarriageController extends Controller
{
    /**
     * GPS stack: only people within this radius (km).
     */
    private const NEARBY_GPS_MAX_KM = 50;

    // ── Main Page ──
    public function index()
    {
        return view('user.marriage');
    }

    // ── Update user location (called via AJAX when GPS enabled) ──
    public function updateLocation(Request $request)
    {
        $request->validate([
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        Auth::user()->update([
            'latitude'            => $request->latitude,
            'longitude'           => $request->longitude,
            'location_updated_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }

    // ── Fetch nearby profiles (GPS capped by NEARBY_GPS_MAX_KM, then city / country fallback) ──
    public function nearbyProfiles(Request $request)
    {
        $request->validate([
            'latitude'  => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $authUser = Auth::user();

        // Only use GPS when the client sends coordinates (Enable GPS flow).
        // "Continue with profile city" omits them — do not fall back to saved DB coords
        // or Haversine runs with stale GPS and skips same-city matching.
        $lat = $request->filled('latitude') ? (float) $request->latitude : null;
        $lng = $request->filled('longitude') ? (float) $request->longitude : null;

        $excludedIds = [$authUser->id];
        $authProfile = UserProfile::where('user_id', $authUser->id)->first();
        $authCity    = trim((string) ($authProfile?->city ?? ''));
        $authCountry = trim((string) ($authUser->country ?? $authProfile?->country ?? ''));

        // Only show people whose gender matches what I registered as looking for (e.g. Man → Woman).
        $desiredGender = $this->desiredPartnerGender($authUser);

        $favouriteIds = MarriageFavourite::where('user_id', $authUser->id)
            ->pluck('favourite_id')
            ->all();

        $baseQuery = User::query()
            ->where('id', '!=', $authUser->id)
            ->where(function ($q) {
                $q->whereNull('role')->orWhere('role', '!=', 'admin');
            });
        if ($desiredGender !== null) {
            $baseQuery->where('gender', $desiredGender);
        }

        $totalNearbyPool = (clone $baseQuery)->count();

        // Haversine formula — calculate distance in km
        $profiles = collect();
        if (! is_null($lat) && ! is_null($lng)) {
            $profiles = User::with('profile')
                ->selectRaw("
                users.*,
                ( 6371 * acos(
                    cos(radians(?)) * cos(radians(latitude))
                    * cos(radians(longitude) - radians(?))
                    + sin(radians(?)) * sin(radians(latitude))
                )) AS distance_km
            ", [$lat, $lng, $lat])
                ->whereNotIn('id', $excludedIds)
                ->where(function ($q) {
                    $q->whereNull('role')->orWhere('role', '!=', 'admin');
                })
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->when($desiredGender !== null, fn ($q) => $q->where('gender', $desiredGender))
                ->havingRaw('distance_km >= 0 AND distance_km <= ?', [self::NEARBY_GPS_MAX_KM])
                ->orderBy('distance_km')
                ->get();
        }

        // Fallback: same city on user_profiles (users.city was removed — country lives on users).
        if ($profiles->isEmpty() && $authCity !== '') {
            $cityNeedle = Str::lower($authCity);
            $profiles = User::with('profile')
                ->whereNotIn('id', $excludedIds)
                ->where(function ($q) {
                    $q->whereNull('role')->orWhere('role', '!=', 'admin');
                })
                ->whereHas('profile', function ($profileQuery) use ($cityNeedle) {
                    $profileQuery->whereRaw('LOWER(TRIM(COALESCE(city, ""))) = ?', [$cityNeedle]);
                })
                ->when($desiredGender !== null, fn ($q) => $q->where('gender', $desiredGender))
                ->orderByDesc('updated_at')
                ->limit(80)
                ->get()
                ->map(function ($user) {
                    $user->distance_km = 0;
                    return $user;
                });
        }

        // Broader fallback: same country when city is missing or no one in that city yet.
        if ($profiles->isEmpty() && $authCountry !== '') {
            $countryNeedle = Str::lower($authCountry);
            $profiles = User::with('profile')
                ->whereNotIn('id', $excludedIds)
                ->where(function ($q) {
                    $q->whereNull('role')->orWhere('role', '!=', 'admin');
                })
                ->where(function ($q) use ($countryNeedle) {
                    $q->whereRaw('LOWER(TRIM(COALESCE(users.country, ""))) = ?', [$countryNeedle])
                        ->orWhereHas('profile', function ($profileQuery) use ($countryNeedle) {
                            $profileQuery->whereRaw('LOWER(TRIM(COALESCE(country, ""))) = ?', [$countryNeedle]);
                        });
                })
                ->when($desiredGender !== null, fn ($q) => $q->where('gender', $desiredGender))
                ->orderByDesc('updated_at')
                ->limit(80)
                ->get()
                ->map(function ($user) {
                    $user->distance_km = 0;
                    return $user;
                });
        }

        // Format response
        $data = $profiles->map(function ($user) use ($favouriteIds) {
            $profile = $user->profile;
            $km = round($user->distance_km, 1);
            $distanceText = $km < 1
                ? ($km > 0 ? round($km * 1000) . ' m' : 'Same city')
                : $km . ' km';

            // Active status
            $lastSeen   = $user->last_seen_at ?? $user->updated_at;
            $diffMins   = now()->diffInMinutes($lastSeen);
            if ($diffMins <= 5) {
                $status     = 'online';
                $statusText = 'Active now';
            } elseif ($diffMins <= 1440) {
                $status     = 'today';
                $statusText = 'Active today';
            } else {
                $status     = 'offline';
                $statusText = 'Active ' . now()->diffForHumans($lastSeen, true) . ' ago';
            }

            // Verification flags
            $verified = [
                'phone' => (bool) $user->phone_verified_at,
                'email' => (bool) $user->email_verified_at,
                'photo' => (bool) ($user->photo_verified ?? false),
            ];

            return [
                'id'          => $user->id,
                'name'        => $user->name ?? 'Anonymous',
                'age'         => ($user->birthday ?? $profile?->date_of_birth)
                    ? now()->diffInYears($user->birthday ?? $profile?->date_of_birth)
                    : null,
                'distance'    => $distanceText,
                'country'     => $user->country ?? $profile?->country ?? $user->nationality ?? $profile?->nationality ?? '',
                'flag'        => $this->countryFlag($user->country ?? $profile?->country ?? $user->nationality ?? $profile?->nationality ?? ''),
                'status'      => $status,
                'statusText'  => $statusText,
                'isPremium'   => (bool) ($user->is_premium ?? false),
                'designation' => $user->profession ?? $profile?->profession ?? $user->designation ?? '—',
                'education'   => $user->education ?? $profile?->education ?? '—',
                'sect'        => $user->sect ?? $profile?->sect ?? '—',
                'marital'     => $user->marital_status ?? $profile?->marital_status ?? '—',
                'nationality' => $user->nationality ?? $profile?->nationality ?? '—',
                'city'        => $profile?->city ?? '—',
                'bio'         => $user->bio ?? $profile?->bio ?? '',
                'avatar'      => $user->avatar ?? null,
                'verified'    => $verified,
                'isFavourite' => in_array($user->id, $favouriteIds),
            ];
        });

        $isExhausted = $totalNearbyPool > 0 && $data->isEmpty();

        return response()->json([
            'profiles'          => $data,
            'is_premium_viewer' => false,
            'is_exhausted'      => $isExhausted,
            'has_nearby_pool'   => $totalNearbyPool > 0,
        ]);
    }

    // ── Like a profile ──
    public function like(Request $request)
    {
        $request->validate(['liked_id' => 'required|exists:users,id']);

        $authId  = Auth::id();
        $likedId = $request->liked_id;

        if ($likedId == $authId) {
            return response()->json(['error' => 'Cannot like yourself'], 400);
        }

        // Save like
        MarriageLike::firstOrCreate([
            'liker_id' => $authId,
            'liked_id' => $likedId,
        ]);

        // Check mutual like → create match
        $mutual = MarriageLike::where('liker_id', $likedId)
            ->where('liked_id', $authId)
            ->exists();

        $isMatch = false;
        if ($mutual) {
            [$a, $b] = $authId < $likedId ? [$authId, $likedId] : [$likedId, $authId];
            MarriageMatch::firstOrCreate([
                'user_one_id' => $a,
                'user_two_id' => $b,
            ], ['matched_at' => now()]);
            $isMatch = true;
        }

        return response()->json([
            'success' => true,
            'match'   => $isMatch,
        ]);
    }

    // ── Pass (skip) — just move on, no DB entry needed unless you want to track passes ──
    public function pass(Request $request)
    {
        $request->validate(['passed_id' => 'required|exists:users,id']);

        $authUser = Auth::user();
        if (!$authUser->isPremium()) {
            MarriagePass::firstOrCreate([
                'passer_id' => $authUser->id,
                'passed_id' => $request->passed_id,
            ]);
        }

        return response()->json(['success' => true]);
    }

    // ── Toggle Favourite ──
    public function toggleFavourite(Request $request)
    {
        $request->validate(['favourite_id' => 'required|exists:users,id']);

        $authId = Auth::id();
        $favId  = $request->favourite_id;

        $existing = MarriageFavourite::where('user_id', $authId)
            ->where('favourite_id', $favId)
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['success' => true, 'favourited' => false]);
        }

        MarriageFavourite::create([
            'user_id'      => $authId,
            'favourite_id' => $favId,
        ]);

        return response()->json(['success' => true, 'favourited' => true]);
    }

    /**
     * Gender shown in swipe stack: must equal the viewer's `looking_for` (Man / Woman).
     * If `looking_for` is missing or invalid, fall back to the conventional opposite of `gender`.
     */
    private function desiredPartnerGender(User $viewer): ?string
    {
        $looking = trim((string) ($viewer->looking_for ?? ''));
        if (in_array($looking, ['Man', 'Woman'], true)) {
            return $looking;
        }

        $mine = $viewer->gender ?? '';
        if ($mine === 'Man') {
            return 'Woman';
        }
        if ($mine === 'Woman') {
            return 'Man';
        }

        return null;
    }

    // ── Country flag helper ──
    private function countryFlag(string $country): string
    {
        $flags = [
            'Pakistan'     => '🇵🇰',
            'India'        => '🇮🇳',
            'Bangladesh'   => '🇧🇩',
            'USA'          => '🇺🇸',
            'UK'           => '🇬🇧',
            'Canada'       => '🇨🇦',
            'Australia'    => '🇦🇺',
            'UAE'          => '🇦🇪',
            'Saudi Arabia' => '🇸🇦',
            'Germany'      => '🇩🇪',
            'France'       => '🇫🇷',
            'Turkey'       => '🇹🇷',
            'Malaysia'     => '🇲🇾',
            'Indonesia'    => '🇮🇩',
        ];

        return $flags[$country] ?? '🌍';
    }
}