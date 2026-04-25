<?php

namespace App\Http\Controllers;

use App\Models\PhotoVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminPhotoVerificationController extends Controller
{
    public function index(Request $request)
    {
        $query = PhotoVerification::query()->with(['user', 'reviewer']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $needle = trim((string) $request->search);
            $query->whereHas('user', function ($q) use ($needle) {
                $q->where('name', 'like', '%' . $needle . '%')
                    ->orWhere('email', 'like', '%' . $needle . '%');
            });
        }

        $items = $query->latest()->paginate(25)->withQueryString();

        $total = PhotoVerification::count();
        $pending = PhotoVerification::where('status', 'pending')->count();
        $approved = PhotoVerification::where('status', 'approved')->count();
        $rejected = PhotoVerification::where('status', 'rejected')->count();

        return view('admin.photo_verifications.index', compact(
            'items', 'total', 'pending', 'approved', 'rejected'
        ));
    }

    public function approve(PhotoVerification $verification)
    {
        if ($verification->status !== 'pending') {
            return redirect()->back()->with('error', 'This request is already reviewed.');
        }

        $verification->update([
            'status' => 'approved',
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id(),
        ]);

        User::where('id', $verification->user_id)->update([
            'photo_verified' => true,
            'photo_verified_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Photo verification approved.');
    }

    public function reject(Request $request, PhotoVerification $verification)
    {
        if ($verification->status !== 'pending') {
            return redirect()->back()->with('error', 'This request is already reviewed.');
        }

        $request->validate([
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $verification->update([
            'status' => 'rejected',
            'reason' => $request->input('reason'),
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id(),
        ]);

        User::where('id', $verification->user_id)->update([
            'photo_verified' => false,
            'photo_verified_at' => null,
        ]);

        return redirect()->back()->with('success', 'Photo verification rejected.');
    }
}

