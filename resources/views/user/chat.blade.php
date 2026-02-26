@extends('layouts.user')
@section('usercontent')


    <!-- start page content wrapper-->
    <div class="page-content-wrapper">
      <!-- start page content-->
      <div class="page-content">
      
    <style>
.chat-container { display: flex; height: 90vh; }
.chat-users { width: 30%; border-right: 1px solid #ddd; overflow-y: auto; }
.chat-window { width: 70%; display: flex; flex-direction: column; }
.chat-messages { flex: 1; padding: 20px; overflow-y: auto; background: #f0f2f5; }
.message { padding: 10px; margin-bottom: 10px; border-radius: 10px; max-width: 60%; }
.sent { background: #dcf8c6; margin-left: auto; }
.received { background: #fff; }
.chat-input { display: flex; padding: 10px; border-top: 1px solid #ddd; }
.chat-input input { flex: 1; padding: 8px; }
</style>

<div class="chat-container">

    <!-- LEFT SIDE -->
    <div class="chat-users">
        <h4 style="padding:10px;">Chats</h4>
        @foreach($matchedUsers as $user)
            <div style="padding:10px; border-bottom:1px solid #eee;">
                <a href="{{ route('chat.index', $user->id) }}">
                    {{ $user->name }}
                </a>
            </div>
        @endforeach
    </div>

    <!-- RIGHT SIDE -->
    <div class="chat-window">

        <div class="chat-messages">
            @foreach($messages as $msg)
                <div class="message {{ $msg->sender_id == auth()->id() ? 'sent' : 'received' }}">
                    {{ $msg->message }}
                </div>
            @endforeach
        </div>

        <form method="POST" action="{{ route('user.chat.send', $userId) }}" class="chat-input">
            @csrf
            <input type="text" name="message" placeholder="Type a message..." required>
            <button type="submit">Send</button>
        </form>

    </div>
</div>
    
    </div>  
    </div>
  @endsection