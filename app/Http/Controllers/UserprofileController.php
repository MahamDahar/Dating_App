<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserProfileController extends Controller
{
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

            'ethnicity' => ['nullable', 'string', 'max:255'],

            'height_cm' => ['nullable', 'integer', 'min:100', 'max:250'],

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
        ];
    }

    // -----------------------------------------------
    // STORE (Create or Update Profile)
    // -----------------------------------------------
    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        $profile = UserProfile::updateOrCreate(
            ['user_id' => Auth::id()],
            $validated
        );

        $profile->profile_completion = $profile->calculateCompletion();
        $profile->save();

        return redirect()->route('profile.show')
            ->with('success', 'Profile saved successfully!');
    }

    // -----------------------------------------------
    // UPDATE (PATCH)
    // -----------------------------------------------
    public function update(Request $request)
    {
        $validated = $request->validate($this->rules());

        $profile = UserProfile::updateOrCreate(
            ['user_id' => Auth::id()],
            $validated
        );

        $profile->profile_completion = $profile->calculateCompletion();
        $profile->save();

        return redirect()->route('profile.show')
            ->with('success', 'Profile updated successfully!');
    }

    // -----------------------------------------------
    // SHOW (View Own Profile)
    // -----------------------------------------------
    public function show()
    {
        $profile = UserProfile::where('user_id', Auth::id())->first();
        return view('user.profile', compact('profile'));
    }

    // -----------------------------------------------
    // EDIT (Open Edit Page With Existing Data)
    // -----------------------------------------------
    public function edit()
    {
        $profile = UserProfile::where('user_id', Auth::id())->first();
        return view('user.editprofile', compact('profile'));
    }

    // -----------------------------------------------
    // INDEX (Admin View All Profiles)
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

        return redirect()->route('home')
            ->with('success', 'Profile deleted.');
    }
}
