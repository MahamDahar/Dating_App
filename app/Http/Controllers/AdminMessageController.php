<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class AdminMessageController extends Controller
{
    // All conversations list
    public function index(Request $request)
    {
        // Get all messages grouped by conversation
        $query = Message::with(['sender', 'receiver'])
            ->select('sender_id', 'receiver_id',
                \DB::raw('MAX(id) as last_message_id'),
                \DB::raw('COUNT(*) as message_count'),
                \DB::raw('MAX(created_at) as last_message_at')
            )
            ->groupBy('sender_id', 'receiver_id');

        // Search by user name or email
        if ($request->search) {
            $userIds = User::where('name',  'like', '%' . $request->search . '%')
                          ->orWhere('email', 'like', '%' . $request->search . '%')
                          ->pluck('id');

            $query->where(function($q) use ($userIds) {
                $q->whereIn('sender_id',   $userIds)
                  ->orWhereIn('receiver_id', $userIds);
            });
        }

        $conversations = $query->orderByDesc('last_message_at')->paginate(20);

        // Get last message for each conversation
        $conversations->getCollection()->transform(function ($conv) {
            $conv->last_message = Message::find($conv->last_message_id);
            $conv->senderUser   = User::find($conv->sender_id);
            $conv->receiverUser = User::find($conv->receiver_id);
            return $conv;
        });

        $totalMessages       = Message::count();
        $totalConversations  = Message::select('sender_id', 'receiver_id')
                                ->groupBy('sender_id', 'receiver_id')
                                ->get()->count();

        return view('admin.messages.index', compact(
            'conversations', 'totalMessages', 'totalConversations'
        ));
    }

    // View conversation between two users
    public function show(Request $request, $userId1, $userId2)
    {
        $user1 = User::findOrFail($userId1);
        $user2 = User::findOrFail($userId2);

        $messages = Message::where(function($q) use ($userId1, $userId2) {
                        $q->where('sender_id',   $userId1)
                          ->where('receiver_id', $userId2);
                    })->orWhere(function($q) use ($userId1, $userId2) {
                        $q->where('sender_id',   $userId2)
                          ->where('receiver_id', $userId1);
                    })
                    ->orderBy('created_at')
                    ->get();

        return view('admin.messages.show', compact('messages', 'user1', 'user2'));
    }
}