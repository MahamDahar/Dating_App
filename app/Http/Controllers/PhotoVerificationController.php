<?php

namespace App\Http\Controllers;

use App\Models\PhotoVerification;
use App\Models\UserProfilePhoto;
use App\Support\ImageSimilarity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PhotoVerificationController extends Controller
{
    private const PASS_THRESHOLD = 60.00;

    public function index()
    {
        $user = Auth::user();
        $latest = $user->latestPhotoVerification()->first();

        $mainPhoto = UserProfilePhoto::query()
            ->where('user_id', $user->id)
            ->where('is_main', true)
            ->first();

        return view('user.verification.index', [
            'user' => $user,
            'latest' => $latest,
            'mainPhoto' => $mainPhoto,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $mainPhoto = UserProfilePhoto::query()
            ->where('user_id', $user->id)
            ->where('is_main', true)
            ->first();

        if (!$mainPhoto) {
            return redirect()->back()->with('error', 'Please upload a main profile photo first.');
        }

        $existingPending = PhotoVerification::query()
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->latest('id')
            ->first();

        if ($existingPending) {
            return redirect()->back()->with('success', 'Your verification request is already pending review.');
        }

        $validated = $request->validate([
            'selfie' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ]);

        $path = $request->file('selfie')->store('photo-verifications/' . $user->id, 'public');

        $mainAbsolute = Storage::disk('public')->path($mainPhoto->path);
        $selfieAbsolute = Storage::disk('public')->path($path);
        $score = ImageSimilarity::score($mainAbsolute, $selfieAbsolute);
        $status = $score >= self::PASS_THRESHOLD ? 'approved' : 'rejected';

        PhotoVerification::create([
            'user_id' => $user->id,
            'status' => $status,
            'provider' => 'local-face-match',
            'score' => $score,
            'selfie_path' => $path,
            'reason' => $status === 'approved' ? null : 'Face did not match main profile photo.',
            'provider_payload' => [
                'threshold' => self::PASS_THRESHOLD,
                'main_photo_path' => $mainPhoto->path,
            ],
            'reviewed_at' => now(),
        ]);

        if ($status === 'approved') {
            $user->forceFill([
                'photo_verified' => true,
                'photo_verified_at' => now(),
            ])->save();

            return redirect()->route('user.verification.index')
                ->with('success', 'Verification successful! Match score: ' . number_format($score, 2) . '%.');
        }

        $user->forceFill(['photo_verified' => false, 'photo_verified_at' => null])->save();

        return redirect()->route('user.verification.index')
            ->with('error', 'Verification failed. Match score: ' . number_format($score, 2) . '%. Minimum ' . number_format(self::PASS_THRESHOLD, 2) . '% required.');
    }
}

