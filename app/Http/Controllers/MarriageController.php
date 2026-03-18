<?php

namespace App\Http\Controllers;

use App\Models\MarriageFavourite;
use App\Models\MarriageLike;
use App\Models\MarriageMatch;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarriageController extends Controller
{
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

    // ── Fetch nearby profiles (no radius limit) ──
    public function nearbyProfiles(Request $request)
    {
        $request->validate([
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $authUser = Auth::user();
        $lat      = $request->latitude;
        $lng      = $request->longitude;

        // Get IDs already liked or passed
        $likedIds = MarriageLike::where('liker_id', $authUser->id)
            ->pluck('liked_id')->toArray();

        // Haversine formula — calculate distance in km
        $profiles = User::selectRaw("
                users.*,
                ( 6371 * acos(
                    cos(radians(?)) * cos(radians(latitude))
                    * cos(radians(longitude) - radians(?))
                    + sin(radians(?)) * sin(radians(latitude))
                )) AS distance_km
            ", [$lat, $lng, $lat])
            ->where('id', '!=', $authUser->id)
            ->where('role', '!=', 'admin')
            ->whereNotIn('id', $likedIds)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->having('distance_km', '>', 0)
            ->orderBy('distance_km')
            ->get();

        // Format response
        $data = $profiles->map(function ($user) use ($authUser) {
            $km = round($user->distance_km, 1);
            $distanceText = $km < 1
                ? round($km * 1000) . ' m'
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
                'id'    => (bool) ($user->id_verified ?? false),
                'photo' => (bool) ($user->photo_verified ?? false),
            ];

            return [
                'id'          => $user->id,
                'name'        => $user->name ?? 'Anonymous',
                'age'         => $user->birthday
                    ? now()->diffInYears($user->birthday)
                    : null,
                'distance'    => $distanceText,
                'country'     => $user->country ?? ($user->nationality ?? ''),
                'flag'        => $this->countryFlag($user->country ?? $user->nationality ?? ''),
                'status'      => $status,
                'statusText'  => $statusText,
                'isPremium'   => (bool) ($user->is_premium ?? false),
                'designation' => $user->profession ?? $user->designation ?? '—',
                'education'   => $user->education ?? '—',
                'sect'        => $user->sect ?? '—',
                'marital'     => $user->marital_status ?? '—',
                'nationality' => $user->nationality ?? '—',
                'city'        => $user->city ?? '—',
                'bio'         => $user->bio ?? '',
                'avatar'      => $user->avatar ?? null,
                'verified'    => $verified,
                'isFavourite' => MarriageFavourite::where('user_id', $authUser->id)
                    ->where('favourite_id', $user->id)->exists(),
            ];
        });

        return response()->json(['profiles' => $data]);
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
        // Optionally track passes here
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