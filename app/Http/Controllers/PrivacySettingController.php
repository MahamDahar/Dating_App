<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PrivacySetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrivacySettingController extends Controller {

    public function index() {
        $privacy = Auth::user()->getPrivacy();
        return view('user.settings.privacy', compact('privacy'));
    }

    public function update(Request $request) {
        $request->validate([
            'who_can_send_proposals'  => 'required|in:everyone,no_one',
            'who_can_see_photos'      => 'required|in:everyone,matches_only,no_one',
            'who_can_see_online'      => 'required|in:everyone,matches_only,no_one',
            'who_can_see_last_active' => 'required|in:everyone,matches_only,no_one',
            'who_can_message'         => 'required|in:everyone,matches_only,no_one',
            'show_on_search_engines'  => 'nullable|boolean',
        ]);

        PrivacySetting::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'who_can_send_proposals'  => $request->who_can_send_proposals,
                'who_can_see_photos'      => $request->who_can_see_photos,
                'who_can_see_online'      => $request->who_can_see_online,
                'who_can_see_last_active' => $request->who_can_see_last_active,
                'who_can_message'         => $request->who_can_message,
                'show_on_search_engines'  => $request->boolean('show_on_search_engines'),
            ]
        );

        return back()->with('success', 'Privacy settings updated successfully!');
    }
}