<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserProfilePhoto;
use App\Models\ProfileView;
use App\Models\Proposal;
use App\Models\MatchRequest;
use App\Models\ChatRequest;
use App\Support\CountryCities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserProfileController extends Controller
{
    private function invalidatePhotoVerificationForAuthUser(): void
    {
        $user = Auth::user();
        if (!$user) {
            return;
        }

        $user->forceFill([
            'photo_verified' => false,
            'photo_verified_at' => null,
        ])->save();
    }

    private function refreshCompletionForAuthUser(): void
    {
        $profile = UserProfile::where('user_id', Auth::id())->first();
        if (!$profile) {
            return;
        }

        $profile->profile_completion = $profile->calculateCompletion();
        $profile->save();
    }

    // -----------------------------------------------
    // VALIDATION RULES
    // -----------------------------------------------
    private function rules(): array
    {
        return [
            'sect' => ['nullable', 'string', Rule::in([
                'Sunni', 'Shia', 'Ahmadi', 'Nation of Islam',
                'Ibadi', 'Just Muslim', 'Prefer not to say',
            ])],

            'profession' => ['nullable', 'string', 'max:100'],

            'education' => ['nullable', 'string', Rule::in([
                'High School', 'Diploma / Vocational', "Bachelor's Degree",
                "Master's Degree", 'PhD / Doctorate', 'Islamic Education', 'Other',
            ])],

            'notifications_enabled' => ['nullable', 'boolean'],
            'hide_from_contacts'    => ['nullable', 'boolean'],

            'nationality' => ['nullable', 'string', 'max:100'],
            'grew_up'     => ['nullable', 'string', 'max:100'],
            'ethnicity'   => ['nullable', 'string', 'max:255'],
            'height_cm'   => ['nullable', 'integer', 'min:100', 'max:250'],

            'marital_status' => ['nullable', 'string', Rule::in([
                'Never Married', 'Divorced', 'Widowed',
                'Separated', 'Married (polygamy)',
            ])],

            'marriage_intentions' => ['nullable', 'string', Rule::in([
                'Seriously looking', 'Open to options', 'Not sure yet',
                'Within 1 year', 'Within 2-3 years',
            ])],

            'religion_practice' => ['nullable', 'string', Rule::in([
                'Practising', 'Moderately Practising',
                'Not Practising', 'Revert / New Muslim',
            ])],

            'born_muslim' => ['nullable', Rule::in(['Yes', 'No', 'Prefer not to say'])],

            'interests'   => ['nullable', 'string', 'max:500'],
            'bio'         => ['nullable', 'string', 'max:300'],
            'personality' => ['nullable', 'string', 'max:255'],

            // ── NEW FIELDS ──
            'date_of_birth'  => ['nullable', 'date', 'before:-18 years'],
            'city'           => ['nullable', 'string', 'max:100'],
            'smoking'        => ['nullable', 'string', Rule::in(['Never', 'Occasionally', 'Yes, regularly', 'Prefer not to say'])],
            'drinking'       => ['nullable', 'string', Rule::in(['Never', 'Occasionally', 'Yes, regularly', 'Prefer not to say'])],
            'want_children'  => ['nullable', 'string', Rule::in(['Yes', 'No', 'Open to it', 'Have children already'])],
            'num_children'   => ['nullable', 'string', Rule::in(['1', '2', '3', '4+', 'Prefer not to say'])],
            'languages'      => ['nullable', 'string', 'max:255'],
        ];
    }

    // -----------------------------------------------
    // SHOW (View Own Profile)
    // -----------------------------------------------
    public function show(Request $request)
    {
        $profile = UserProfile::where('user_id', Auth::id())->first();
        $user = Auth::user();

        // Prefill edit form from user table when profile table is partially empty.
        // This keeps old registration data visible instead of blank selects/inputs.
        if ($profile && $request->boolean('edit')) {
            if (empty($profile->date_of_birth) && !empty($user?->birthday)) {
                $profile->date_of_birth = $user->birthday;
            }
            // Country is fixed at registration; always show that value in the profile editor.
            $profile->country = (string) ($user?->country ?? '');
            if (empty($profile->nationality)) {
                $profile->nationality = (string) ($user?->nationality ?? $user?->country ?? '');
            }
            if (empty($profile->marital_status) && !empty($user?->marital_status)) {
                $profile->marital_status = $user->marital_status;
            }
        }

        if ($profile) {
            // Always keep completion in sync with actual filled data + photos.
            $freshCompletion = $profile->calculateCompletion();
            if ((int) $profile->profile_completion !== (int) $freshCompletion) {
                $profile->profile_completion = $freshCompletion;
                $profile->save();
            }
        }
        $photos  = UserProfilePhoto::where('user_id', Auth::id())
                    ->orderBy('is_main', 'desc')
                    ->orderBy('order')
                    ->get();

        // Log profile view (only for other users visiting your profile)
        if (Auth::check() && $profile && $profile->user_id !== Auth::id()) {
            ProfileView::updateOrCreate(
                [
                    'viewer_id' => Auth::id(),
                    'viewed_id' => $profile->user_id,
                ],
                [
                    'seen'      => false,
                    'viewed_at' => now(),
                ]
            );
        }

        return view('user.userprofile', compact('profile', 'photos'));
    }

    // -----------------------------------------------
    // VIEW OTHER USER PROFILE (Saved Profiles -> View)
    // -----------------------------------------------
    public function view(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('user.profile');
        }

        $profile = UserProfile::with('photos')->where('user_id', $user->id)->first();
        $photos  = $profile?->photos ?? collect();

        // Log profile view
        ProfileView::updateOrCreate(
            [
                'viewer_id' => Auth::id(),
                'viewed_id' => $user->id,
            ],
            [
                'seen'      => false,
                'viewed_at' => now(),
            ]
        );

        $authId = Auth::id();
        $otherId = $user->id;

        $messagingUnlocked = MatchRequest::messagingUnlockedBetween($authId, $otherId);
        $proposalAccepted = Proposal::hasAcceptedBetween($authId, $otherId);
        $pendingChatRequest = ChatRequest::pendingBetween($authId, $otherId);

        return view('user.profile-view', compact(
            'user',
            'profile',
            'photos',
            'messagingUnlocked',
            'proposalAccepted',
            'pendingChatRequest'
        ));
    }

    // -----------------------------------------------
    // EDIT (Open Edit Page)
    // -----------------------------------------------
    public function edit()
    {
        $profile = UserProfile::where('user_id', Auth::id())->first();
        $photos  = UserProfilePhoto::where('user_id', Auth::id())
                    ->orderBy('is_main', 'desc')
                    ->orderBy('order')
                    ->get();

        return view('user.editprofile', compact('profile', 'photos'));
    }

    // -----------------------------------------------
    // STORE (Create Profile — multi-step form)
    // -----------------------------------------------
    public function store(Request $request)
    {
        $rules = $this->rules();
        $registrationCountry = (string) (Auth::user()->country ?? '');
        $rules['city'] = [
            'required',
            'string',
            'max:100',
            Rule::in(CountryCities::citiesFor($registrationCountry)),
        ];
        $validated = $request->validate($rules);
        $validated['country'] = $registrationCountry;

        // Checkboxes are absent when unchecked; normalize explicitly.
        $validated['notifications_enabled'] = $request->boolean('notifications_enabled');
        $validated['hide_from_contacts'] = $request->boolean('hide_from_contacts');

        $profile = UserProfile::updateOrCreate(
            ['user_id' => Auth::id()],
            $validated
        );

        $profile->profile_completion = $profile->calculateCompletion();
        $profile->save();

        return redirect()->route('user.profile')
            ->with('success', 'Profile saved successfully!');
    }

    // -----------------------------------------------
    // UPDATE (PATCH — edit form)
    // -----------------------------------------------
    public function update(Request $request)
    {
        $rules = $this->rules();
        $registrationCountry = (string) (Auth::user()->country ?? '');
        $rules['city'] = [
            'required',
            'string',
            'max:100',
            Rule::in(CountryCities::citiesFor($registrationCountry)),
        ];
        $validated = $request->validate($rules);

        $profile = UserProfile::firstOrNew(['user_id' => Auth::id()]);

        // Keep existing values unless field is actually submitted.
        // This prevents accidental wipe of profile data on partial edit posts.
        foreach (array_keys($this->rules()) as $field) {
            if (! $request->has($field)) {
                unset($validated[$field]);
            }
        }

        // Country always matches registration (never taken from the request body).
        $validated['country'] = $registrationCountry;

        // Normalize toggles (unchecked checkboxes are not posted).
        $validated['notifications_enabled'] = $request->boolean('notifications_enabled');
        $validated['hide_from_contacts'] = $request->boolean('hide_from_contacts');

        $profile->fill($validated);
        $profile->save();

        $profile->profile_completion = $profile->calculateCompletion();
        $profile->save();

        return redirect()->route('user.profile')
            ->with('success', 'Profile updated successfully!');
    }

    // -----------------------------------------------
    // INDEX (Admin — all profiles)
    // -----------------------------------------------
    public function index(Request $request)
    {
        $query = UserProfile::with('user')
            ->latest()
            ->paginate(15);

        return view('profile.index', compact('query'));
    }

    // -----------------------------------------------
    // DELETE PROFILE
    // -----------------------------------------------
    public function destroy()
    {
        UserProfile::where('user_id', Auth::id())->delete();

        return redirect()->route('user.discover')
            ->with('success', 'Profile deleted.');
    }

    // -----------------------------------------------
    // UPLOAD PHOTO (AJAX)
    // -----------------------------------------------
    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $count = UserProfilePhoto::where('user_id', Auth::id())->count();

        if ($count >= 6) {
            return response()->json(['error' => 'Maximum 6 photos allowed.'], 422);
        }

        $path = $request->file('photo')->store('profile-photos', 'public');

        $photo = UserProfilePhoto::create([
            'user_id' => Auth::id(),
            'path'    => $path,
            'is_main' => $count === 0, // first photo = main
            'order'   => $count,
        ]);

        if ($photo->is_main) {
            $this->invalidatePhotoVerificationForAuthUser();
        }

        $this->refreshCompletionForAuthUser();

        return response()->json([
            'id'      => $photo->id,
            'url'     => asset('storage/' . $path),
            'is_main' => $photo->is_main,
        ]);
    }

    // -----------------------------------------------
    // SET MAIN PHOTO (AJAX)
    // -----------------------------------------------
    public function setMainPhoto(Request $request)
    {
        $request->validate(['photo_id' => 'required|exists:user_profile_photos,id']);

        UserProfilePhoto::where('user_id', Auth::id())
            ->update(['is_main' => false]);

        UserProfilePhoto::where('id', $request->photo_id)
            ->where('user_id', Auth::id())
            ->update(['is_main' => true]);

        $this->invalidatePhotoVerificationForAuthUser();

        return response()->json(['success' => true]);
    }

    // -----------------------------------------------
    // TOGGLE BLUR (AJAX) - Premium only
    // -----------------------------------------------
    public function toggleBlurPhoto(Request $request)
    {
        $request->validate(['photo_id' => 'required|exists:user_profile_photos,id']);

        $user = Auth::user();
        if (!$user || !$user->isPremium()) {
            return response()->json([
                'error' => 'Blur option is available for premium users only.',
                'upgrade_url' => '/user/premium/plans',
            ], 403);
        }

        $photo = UserProfilePhoto::where('id', $request->photo_id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$photo) {
            return response()->json(['error' => 'Photo not found.'], 404);
        }

        $photo->is_blurred = !$photo->is_blurred;
        $photo->save();

        return response()->json([
            'success' => true,
            'is_blurred' => (bool) $photo->is_blurred,
        ]);
    }

    // -----------------------------------------------
    // DELETE PHOTO (AJAX)
    // -----------------------------------------------
    public function deletePhoto(Request $request)
    {
        $request->validate(['photo_id' => 'required|exists:user_profile_photos,id']);

        $photo = UserProfilePhoto::where('id', $request->photo_id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$photo) {
            return response()->json(['error' => 'Photo not found.'], 404);
        }

        Storage::disk('public')->delete($photo->path);
        $wasMain = $photo->is_main;
        $photo->delete();

        // If deleted photo was main → promote next one
        if ($wasMain) {
            $next = UserProfilePhoto::where('user_id', Auth::id())
                ->orderBy('order')
                ->first();
            if ($next) $next->update(['is_main' => true]);

            $this->invalidatePhotoVerificationForAuthUser();
        }

        $this->refreshCompletionForAuthUser();

        return response()->json(['success' => true]);
    }
}