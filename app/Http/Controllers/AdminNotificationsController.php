<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use Illuminate\Http\Request;

class AdminNotificationsController extends Controller
{
    // ── Full Notifications Page ──
    public function index()
    {
        $notifications = AdminNotification::latest()->paginate(20);
        $unreadCount   = AdminNotification::unreadCount();

        return view('admin.notifications', compact('notifications', 'unreadCount'));
    }

    // ── Mark all as read (AJAX) ──
    public function markAllRead()
    {
        AdminNotification::markAllRead();

        return response()->json(['success' => true]);
    }

    // ── Mark single as read (AJAX) ──
    public function markRead($id)
    {
        $notification = AdminNotification::findOrFail($id);
        $notification->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    // ── Delete single notification ──
    public function destroy($id)
    {
        AdminNotification::findOrFail($id)->delete();

        return back()->with('success', 'Notification deleted.');
    }

    // ── Clear all notifications ──
    public function clearAll()
    {
        AdminNotification::truncate();

        return back()->with('success', 'All notifications cleared.');
    }
}