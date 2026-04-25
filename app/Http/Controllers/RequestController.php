<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ChatRequest;
use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    public function index(Request $request)
    {
        $withUser = ['profile', 'profilePhotos'];

        $mainTab = $request->query('main', 'proposals');
        if (! in_array($mainTab, ['proposals', 'chat'], true)) {
            $mainTab = 'proposals';
        }

        // Pending marriage proposals
        $received = Proposal::with(['sender' => fn ($q) => $q->with($withUser)])
            ->where('receiver_id', Auth::id())
            ->where('status', 'pending')
            ->latest()
            ->get();

        $sent = Proposal::with(['receiver' => fn ($q) => $q->with($withUser)])
            ->where('sender_id', Auth::id())
            ->where('status', 'pending')
            ->latest()
            ->get();

        // Chat requests (after proposal accepted)
        $chatReceived = ChatRequest::with(['sender' => fn ($q) => $q->with($withUser)])
            ->where('receiver_id', Auth::id())
            ->where('status', 'pending')
            ->latest()
            ->get();

        $chatSent = ChatRequest::with(['receiver' => fn ($q) => $q->with($withUser)])
            ->where('sender_id', Auth::id())
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('user.requests.index', compact(
            'received',
            'sent',
            'chatReceived',
            'chatSent',
            'mainTab'
        ));
    }
}