<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\MatchRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

class ChatController extends Controller
{
    private function messagePayload(Message $msg, int $authId): array
    {
        $isOut = (int) $msg->sender_id === (int) $authId;
        $mediaUrl = $msg->media_path ? asset('storage/'.$msg->media_path) : null;
        $extLower = $msg->media_path ? strtolower((string) pathinfo($msg->media_path, PATHINFO_EXTENSION)) : '';
        $isImage = $msg->isImage() || ($mediaUrl && in_array($extLower, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true));
        $isAudio = $msg->isAudio() || ($mediaUrl && in_array($extLower, ['webm', 'ogg', 'opus', 'mp3', 'wav', 'm4a', 'mp4', 'mpeg', 'oga'], true));
        $copyPlain = $msg->previewPlain();
        $searchBlob = '';
        if (! $msg->isRevoked()) {
            $searchBlob = mb_strtolower(trim($copyPlain.' '.strip_tags((string) $msg->message)));
        }

        return [
            'id' => (int) $msg->id,
            'sender_id' => (int) $msg->sender_id,
            'is_out' => $isOut,
            'revoked' => (bool) $msg->isRevoked(),
            'starred' => (bool) $msg->isStarredBy($authId),
            'copy_text' => (string) $copyPlain,
            'search_text' => (string) $searchBlob,
            'created_iso' => $msg->created_at?->toIso8601String(),
            'day_key' => $msg->created_at?->toDateString(),
            'time_label' => $msg->created_at?->format('H:i') ?? '',
            'day_label' => $msg->created_at
                ? ($msg->created_at->isToday()
                    ? 'Today'
                    : ($msg->created_at->isYesterday() ? 'Yesterday' : $msg->created_at->format('M j, Y')))
                : '',
            'text' => (string) ($msg->message ?? ''),
            'media_url' => $mediaUrl,
            'is_image' => (bool) $isImage,
            'is_audio' => (bool) $isAudio,
            'read_at' => $msg->read_at?->toIso8601String(),
        ];
    }

    /** Chat after accepted chat request (stored as accepted match_requests row). */
    private function canChat(int $authId, User $user): bool
    {
        return MatchRequest::messagingUnlockedBetween($authId, $user->id);
    }

    /** Users you can chat with: accepted match_requests only. */
    private function chatPartners(int $authId)
    {
        return MatchRequest::where('status', 'accepted')
            ->where(function ($q) use ($authId) {
                $q->where('sender_id', $authId)
                    ->orWhere('receiver_id', $authId);
            })
            ->with(['sender', 'receiver'])
            ->get()
            ->map(function ($match) use ($authId) {
                return $match->sender_id == $authId
                    ? $match->receiver
                    : $match->sender;
            })
            ->filter()
            ->unique('id')
            ->values();
    }

    /**
     * WhatsApp-style sidebar rows: last preview, time, unread count per partner.
     *
     * @param  \Illuminate\Support\Collection<int, User>  $matchedUsers
     * @return \Illuminate\Support\Collection<int, array{user: User, last_message: ?Message, preview: string, time_label: string, unread: int}>
     */
    private function chatSidebarRows(int $authId, $matchedUsers)
    {
        if ($matchedUsers->isEmpty()) {
            return collect();
        }

        $partnerIds = $matchedUsers->pluck('id')->all();

        $recent = Message::query()
            ->where(function ($q) use ($authId, $partnerIds) {
                $q->where(function ($q2) use ($authId, $partnerIds) {
                    $q2->where('receiver_id', $authId)->whereIn('sender_id', $partnerIds);
                })->orWhere(function ($q2) use ($authId, $partnerIds) {
                    $q2->where('sender_id', $authId)->whereIn('receiver_id', $partnerIds);
                });
            })
            ->visibleToUser($authId)
            ->orderByDesc('created_at')
            ->get();

        $lastByPartner = [];
        foreach ($recent as $m) {
            $other = (int) ($m->sender_id === $authId ? $m->receiver_id : $m->sender_id);
            if (! isset($lastByPartner[$other])) {
                $lastByPartner[$other] = $m;
            }
        }

        $unreadCounts = Message::query()
            ->where('receiver_id', $authId)
            ->whereIn('sender_id', $partnerIds)
            ->whereNull('read_at')
            ->visibleToUser($authId)
            ->selectRaw('sender_id, COUNT(*) as c')
            ->groupBy('sender_id')
            ->pluck('c', 'sender_id');

        return $matchedUsers->map(function (User $u) use ($authId, $lastByPartner, $unreadCounts) {
            $last = $lastByPartner[$u->id] ?? null;
            $preview = 'Tap to chat';
            if ($last) {
                if ($last->isRevoked()) {
                    $preview = $last->sender_id === $authId
                        ? 'You deleted this message'
                        : 'This message was deleted';
                } elseif ($last->sender_id === $authId) {
                    $preview = 'You: '.($last->previewPlain() ?: 'Message');
                } else {
                    $preview = $last->previewPlain() ?: 'Message';
                }
            }

            $timeLabel = '';
            if ($last) {
                $timeLabel = $this->formatSidebarTime($last->created_at);
            }

            return [
                'user' => $u,
                'last_message' => $last,
                'preview' => $preview,
                'time_label' => $timeLabel,
                'unread' => (int) ($unreadCounts[$u->id] ?? 0),
            ];
        })->sortByDesc(function (array $row) {
            $m = $row['last_message'];

            return $m ? $m->created_at->timestamp : 0;
        })->values();
    }

    private function formatSidebarTime(Carbon $dt): string
    {
        $now = Carbon::now();
        if ($dt->isSameDay($now)) {
            return $dt->format('H:i');
        }
        if ($dt->isSameDay($now->copy()->subDay())) {
            return 'Yesterday';
        }
        if ($dt->greaterThanOrEqualTo($now->copy()->subDays(6)->startOfDay())) {
            return $dt->format('D');
        }

        return $dt->format('j/n/y');
    }

    public function index(Request $request, ?User $user = null)
    {
        $authId = Auth::id();

        $matchedUsers = $this->chatPartners($authId);
        $chatSidebar = $this->chatSidebarRows($authId, $matchedUsers);
        $totalChatUnread = (int) $chatSidebar->sum(fn (array $row) => (int) ($row['unread'] ?? 0));

        // Support old query style (?user_id=123)
        if (!$user && $request->filled('user_id')) {
            $user = User::find($request->query('user_id'));
        }

        if (!$user) {
            return view('user.chat', [
                'matchedUsers'     => $matchedUsers,
                'chatSidebar'      => $chatSidebar,
                'totalChatUnread'  => $totalChatUnread,
                'messages'         => collect(),
                'activeUser'       => null,
            ]);
        }

        if (!$this->canChat($authId, $user)) {
            abort(403, 'Chat not allowed.');
        }

        Message::where('sender_id', $user->id)
            ->where('receiver_id', $authId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $messages = Message::where(function ($q) use ($authId, $user) {
            $q->where('sender_id', $authId)->where('receiver_id', $user->id);
        })
            ->orWhere(function ($q) use ($authId, $user) {
                $q->where('sender_id', $user->id)->where('receiver_id', $authId);
            })
            ->visibleToUser($authId)
            ->orderBy('created_at')
            ->get();

        return view('user.chat', [
            'matchedUsers'     => $matchedUsers,
            'chatSidebar'      => $chatSidebar,
            'totalChatUnread'  => $totalChatUnread,
            'messages'         => $messages,
            'activeUser'       => $user,
        ]);
    }

    /** Poll sidebar state so unread/preview updates without opening chat. */
    public function pollSidebar(Request $request)
    {
        $authId = Auth::id();
        $activeUserId = max(0, (int) $request->query('active_user_id', 0));

        $matchedUsers = $this->chatPartners($authId);
        $chatSidebar = $this->chatSidebarRows($authId, $matchedUsers);

        $rows = $chatSidebar->map(function (array $row) {
            $u = $row['user'];
            return [
                'user_id' => (int) $u->id,
                'name' => (string) ($u->name ?? ''),
                'preview' => (string) ($row['preview'] ?? ''),
                'time_label' => (string) ($row['time_label'] ?? ''),
                'unread' => (int) ($row['unread'] ?? 0),
            ];
        })->values();

        // If that chat is already open, don't show unread in sidebar for it.
        if ($activeUserId > 0) {
            $rows = $rows->map(function (array $r) use ($activeUserId) {
                if ((int) $r['user_id'] === $activeUserId) {
                    $r['unread'] = 0;
                }
                return $r;
            })->values();
        }

        return response()->json([
            'success' => true,
            'rows' => $rows,
            'total_unread' => (int) $rows->sum(fn (array $r) => (int) ($r['unread'] ?? 0)),
        ]);
    }

    public function send(Request $request, User $user)
    {
        $authId = Auth::id();

        if (!$this->canChat($authId, $user)) {
            abort(403, 'Chat not allowed.');
        }

        $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $message = Message::create([
            'sender_id'    => $authId,
            'receiver_id'  => $user->id,
            'message'      => $request->input('message'),
            'message_type' => 'text',
        ]);

        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $this->messagePayload($message, $authId),
            ]);
        }

        return redirect()->route('user.chat.with', $user->id);
    }

    public function sendMedia(Request $request, User $user)
    {
        $authId = Auth::id();

        if (! $this->canChat($authId, $user)) {
            abort(403, 'Chat not allowed.');
        }

        $wantsJson = $request->expectsJson()
            || $request->ajax()
            || $request->wantsJson();

        $base = Validator::make($request->all(), [
            'file' => ['required', 'file', 'max:15360'],
        ]);
        if ($base->fails()) {
            return $this->sendMediaError($wantsJson, $user, $base->errors()->first('file') ?? 'Invalid file.');
        }

        $file = $request->file('file');
        $mime = strtolower((string) $file->getMimeType());
        $ext = strtolower((string) ($file->getClientOriginalExtension() ?: $file->guessExtension() ?: ''));

        $imageExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $audioExts = ['webm', 'ogg', 'opus', 'mp3', 'wav', 'm4a', 'mp4', 'mpeg', 'oga'];

        $messageType = null;
        if (str_starts_with($mime, 'image/') || in_array($ext, $imageExts, true)) {
            $messageType = 'image';
        } elseif (str_starts_with($mime, 'audio/')
            || $mime === 'application/ogg'
            || (str_starts_with($mime, 'video/') && in_array($ext, ['webm', 'mp4'], true))
            || in_array($ext, $audioExts, true)
            || ($mime === 'application/octet-stream' && in_array($ext, $audioExts, true))) {
            $messageType = 'audio';
        }

        if ($messageType === 'image') {
            $rules = ['file' => ['image', 'mimes:jpeg,png,gif,webp', 'max:10240']];
        } elseif ($messageType === 'audio') {
            $rules = ['file' => ['file', 'max:15360']];
        } else {
            return $this->sendMediaError($wantsJson, $user, 'Only images and voice notes are supported.');
        }

        $v = Validator::make($request->all(), $rules);
        if ($v->fails()) {
            return $this->sendMediaError($wantsJson, $user, $v->errors()->first('file') ?? 'Invalid file.');
        }

        $path = $file->store('chat-media/'.$authId, 'public');
        if (! $path) {
            return $this->sendMediaError($wantsJson, $user, 'Could not store file.');
        }

        $message = Message::create([
            'sender_id'    => $authId,
            'receiver_id'  => $user->id,
            'message'      => '',
            'message_type' => $messageType,
            'media_path'   => $path,
        ]);

        if ($wantsJson) {
            return response()->json([
                'success'  => true,
                'redirect' => route('user.chat.with', $user->id),
                'id'       => $message->id,
            ]);
        }

        return redirect()->route('user.chat.with', $user->id);
    }

    private function sendMediaError(bool $wantsJson, User $user, string $message)
    {
        if ($wantsJson) {
            return response()->json(['success' => false, 'message' => $message], 422);
        }

        return redirect()
            ->route('user.chat.with', $user->id)
            ->with('error', $message);
    }

    /** Delete selected messages: scope `me` = hide on your device; `everyone` = unsend own recent messages. */
    public function deleteMessages(Request $request, User $user)
    {
        $authId = Auth::id();

        if (! $this->canChat($authId, $user)) {
            abort(403, 'Chat not allowed.');
        }

        $wantsJson = $request->expectsJson()
            || $request->ajax()
            || $request->wantsJson();

        $validated = Validator::make($request->all(), [
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'distinct'],
            'scope' => ['required', 'in:me,everyone'],
        ]);

        if ($validated->fails()) {
            if ($wantsJson) {
                return response()->json(['success' => false, 'message' => $validated->errors()->first()], 422);
            }

            return redirect()->route('user.chat.with', $user->id)->with('error', $validated->errors()->first());
        }

        $ids = array_values(array_unique(array_map('intval', $request->input('ids', []))));
        $scope = $request->input('scope');

        $messages = Message::whereIn('id', $ids)
            ->where(function ($q) use ($authId, $user) {
                $q->where(function ($q2) use ($authId, $user) {
                    $q2->where('sender_id', $authId)->where('receiver_id', $user->id);
                })->orWhere(function ($q2) use ($authId, $user) {
                    $q2->where('sender_id', $user->id)->where('receiver_id', $authId);
                });
            })
            ->get()
            ->keyBy('id');

        foreach ($ids as $id) {
            if (! $messages->has($id)) {
                $msg = 'One or more messages are invalid.';
                if ($wantsJson) {
                    return response()->json(['success' => false, 'message' => $msg], 422);
                }

                return redirect()->route('user.chat.with', $user->id)->with('error', $msg);
            }
        }

        if ($scope === 'me') {
            foreach ($ids as $id) {
                $msg = $messages[$id];
                if ($msg->sender_id == $authId) {
                    $msg->update(['hidden_for_sender_at' => now()]);
                } else {
                    $msg->update(['hidden_for_receiver_at' => now()]);
                }
            }
        } else {
            $maxAgeHours = 48;
            $cutoff = now()->subHours($maxAgeHours);
            foreach ($ids as $id) {
                $msg = $messages[$id];
                if ($msg->sender_id != $authId) {
                    $m = 'You can only unsend your own messages.';
                    if ($wantsJson) {
                        return response()->json(['success' => false, 'message' => $m], 422);
                    }

                    return redirect()->route('user.chat.with', $user->id)->with('error', $m);
                }
                if ($msg->revoked_at) {
                    $m = 'That message was already removed.';
                    if ($wantsJson) {
                        return response()->json(['success' => false, 'message' => $m], 422);
                    }

                    return redirect()->route('user.chat.with', $user->id)->with('error', $m);
                }
                if ($msg->created_at->lt($cutoff)) {
                    $m = 'Messages older than '.$maxAgeHours.' hours cannot be removed for everyone.';
                    if ($wantsJson) {
                        return response()->json(['success' => false, 'message' => $m], 422);
                    }

                    return redirect()->route('user.chat.with', $user->id)->with('error', $m);
                }
            }
            foreach ($ids as $id) {
                $messages[$id]->update([
                    'revoked_at' => now(),
                    'message' => '',
                    'media_path' => null,
                    'message_type' => 'text',
                ]);
            }
        }

        if ($wantsJson) {
            return response()->json(['success' => true, 'redirect' => route('user.chat.with', $user->id)]);
        }

        return redirect()->route('user.chat.with', $user->id);
    }

    public function markAllRead(Request $request, User $user)
    {
        $authId = Auth::id();

        if (! $this->canChat($authId, $user)) {
            abort(403, 'Chat not allowed.');
        }

        Message::where('sender_id', $user->id)
            ->where('receiver_id', $authId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('user.chat.with', $user->id);
    }

    /** Poll new messages for active conversation without full page reload. */
    public function pollMessages(Request $request, User $user)
    {
        $authId = Auth::id();

        if (! $this->canChat($authId, $user)) {
            abort(403, 'Chat not allowed.');
        }

        $afterId = max(0, (int) $request->query('after_id', 0));

        $messages = Message::where(function ($q) use ($authId, $user) {
            $q->where('sender_id', $authId)->where('receiver_id', $user->id);
        })
            ->orWhere(function ($q) use ($authId, $user) {
                $q->where('sender_id', $user->id)->where('receiver_id', $authId);
            })
            ->where('id', '>', $afterId)
            ->visibleToUser($authId)
            ->orderBy('id')
            ->get();

        // While chat is open, mark partner's unseen messages as read.
        Message::where('sender_id', $user->id)
            ->where('receiver_id', $authId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $payload = $messages
            ->map(fn (Message $msg) => $this->messagePayload($msg, $authId))
            ->values();

        $readUpToId = (int) (
            Message::where('sender_id', $authId)
                ->where('receiver_id', $user->id)
                ->whereNotNull('read_at')
                ->max('id') ?? 0
        );

        return response()->json([
            'success' => true,
            'messages' => $payload,
            'last_id' => (int) ($payload->last()['id'] ?? $afterId),
            'read_up_to_id' => $readUpToId,
        ]);
    }

    /** JSON list of messages starred by the current user in this chat. */
    public function starredMessages(Request $request, User $user)
    {
        $authId = Auth::id();

        if (! $this->canChat($authId, $user)) {
            abort(403, 'Chat not allowed.');
        }

        $rows = Message::where(function ($q) use ($authId, $user) {
            $q->where('sender_id', $authId)->where('receiver_id', $user->id);
        })
            ->orWhere(function ($q) use ($authId, $user) {
                $q->where('sender_id', $user->id)->where('receiver_id', $authId);
            })
            ->visibleToUser($authId)
            ->whereJsonContains('starred_user_ids', $authId)
            ->orderByDesc('created_at')
            ->get();

        $payload = $rows->map(function (Message $m) use ($authId) {
            return [
                'id' => $m->id,
                'preview' => $m->previewPlain() ?: ($m->isRevoked() ? '[Deleted message]' : ''),
                'time' => $m->created_at->format('H:i'),
                'date' => $m->created_at->format('M j, Y'),
                'is_out' => $m->sender_id === $authId,
                'revoked' => $m->isRevoked(),
            ];
        });

        return response()->json(['success' => true, 'messages' => $payload]);
    }

    /** Star or unstar messages for the current user (like WhatsApp). */
    public function starMessages(Request $request, User $user)
    {
        $authId = Auth::id();

        if (! $this->canChat($authId, $user)) {
            abort(403, 'Chat not allowed.');
        }

        $wantsJson = $request->expectsJson()
            || $request->ajax()
            || $request->wantsJson();

        $validated = Validator::make($request->all(), [
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'distinct'],
            'action' => ['required', 'in:star,unstar'],
        ]);

        if ($validated->fails()) {
            if ($wantsJson) {
                return response()->json(['success' => false, 'message' => $validated->errors()->first()], 422);
            }

            return redirect()->route('user.chat.with', $user->id)->with('error', $validated->errors()->first());
        }

        $ids = array_values(array_unique(array_map('intval', $request->input('ids', []))));
        $action = $request->input('action');

        $messages = Message::whereIn('id', $ids)
            ->where(function ($q) use ($authId, $user) {
                $q->where(function ($q2) use ($authId, $user) {
                    $q2->where('sender_id', $authId)->where('receiver_id', $user->id);
                })->orWhere(function ($q2) use ($authId, $user) {
                    $q2->where('sender_id', $user->id)->where('receiver_id', $authId);
                });
            })
            ->get()
            ->keyBy('id');

        foreach ($ids as $id) {
            if (! $messages->has($id)) {
                $msg = 'One or more messages are invalid.';
                if ($wantsJson) {
                    return response()->json(['success' => false, 'message' => $msg], 422);
                }

                return redirect()->route('user.chat.with', $user->id)->with('error', $msg);
            }
        }

        foreach ($ids as $id) {
            $msg = $messages[$id];
            $arr = $msg->starredUserIdList();
            $has = in_array($authId, $arr, true);
            if ($action === 'star' && ! $has) {
                $arr[] = $authId;
            } elseif ($action === 'unstar' && $has) {
                $arr = array_values(array_filter($arr, fn (int $uid) => $uid !== $authId));
            }
            $msg->starred_user_ids = count($arr) ? $arr : null;
            $msg->save();
        }

        if ($wantsJson) {
            return response()->json(['success' => true, 'redirect' => route('user.chat.with', $user->id)]);
        }

        return redirect()->route('user.chat.with', $user->id);
    }
}
