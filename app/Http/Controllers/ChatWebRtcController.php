<?php

namespace App\Http\Controllers;

use App\Models\ChatWebrtcSignal;
use App\Models\MatchRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatWebRtcController extends Controller
{
    private function webrtcRoom(int $a, int $b): string
    {
        return min($a, $b).'_'.max($a, $b);
    }

    private function canChat(int $authId, User $user): bool
    {
        return MatchRequest::messagingUnlockedBetween($authId, $user->id);
    }

    public function show(Request $request, User $user)
    {
        if (! $this->canChat(Auth::id(), $user)) {
            abort(403, 'Chat not allowed.');
        }

        $type = $request->query('type', 'audio');
        if (! in_array($type, ['audio', 'video'], true)) {
            $type = 'audio';
        }

        $role = $request->query('role', 'answer');
        if (! in_array($role, ['caller', 'answer'], true)) {
            $role = 'answer';
        }

        return view('user.chat-call', [
            'peer' => $user,
            'callType' => $type,
            'callRole' => $role,
        ]);
    }

    public function poll(Request $request, User $user)
    {
        if (! $this->canChat(Auth::id(), $user)) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $since = max(0, (int) $request->query('since', 0));
        $room = $this->webrtcRoom(Auth::id(), $user->id);

        ChatWebrtcSignal::where('room', $room)
            ->where('created_at', '<', Carbon::now()->subHours(2))
            ->delete();

        $signals = ChatWebrtcSignal::where('room', $room)
            ->where('id', '>', $since)
            ->orderBy('id')
            ->get(['id', 'from_user_id', 'kind', 'payload']);

        return response()->json(['signals' => $signals]);
    }

    public function store(Request $request, User $user)
    {
        if (! $this->canChat(Auth::id(), $user)) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $request->validate([
            'kind' => ['required', 'string', 'in:offer,answer,candidate'],
            'payload' => ['required', 'string', 'max:65000'],
        ]);

        ChatWebrtcSignal::create([
            'room' => $this->webrtcRoom(Auth::id(), $user->id),
            'from_user_id' => Auth::id(),
            'kind' => $request->input('kind'),
            'payload' => $request->input('payload'),
        ]);

        return response()->json(['success' => true]);
    }
}
