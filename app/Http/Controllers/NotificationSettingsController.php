<?php

namespace App\Http\Controllers;

use App\Models\NotificationSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationSettingsController extends Controller
{
    public function index()
    {
        return view('user.settings.notifications.index');
    }

    // ── Push ──
    public function push()
    {
        $settings = NotificationSetting::getOrCreate(Auth::id());
        return view('user.settings.notificationsetting.push-notification', compact('settings'));
    }

    public function updatePush(Request $request)
    {
        $settings = NotificationSetting::getOrCreate(Auth::id());
        $settings->update([
            'push_new_match'      => $request->boolean('push_new_match'),
            'push_new_message'    => $request->boolean('push_new_message'),
            'push_profile_views'  => $request->boolean('push_profile_views'),
            'push_likes_received' => $request->boolean('push_likes_received'),
        ]);
        return back()->with('success', 'Push notification settings saved successfully.');
    }

    // ── In-App ──
    public function inApp()
    {
        $settings = NotificationSetting::getOrCreate(Auth::id());
        return view('user.settings.notificationsetting.inapp-notification', compact('settings'));
    }

    public function updateInApp(Request $request)
    {
        $settings = NotificationSetting::getOrCreate(Auth::id());
        $settings->update([
            'inapp_new_match'      => $request->boolean('inapp_new_match'),
            'inapp_new_message'    => $request->boolean('inapp_new_message'),
            'inapp_profile_views'  => $request->boolean('inapp_profile_views'),
            'inapp_likes_received' => $request->boolean('inapp_likes_received'),
        ]);
        return back()->with('success', 'In-app notification settings saved successfully.');
    }

    // ── Email ──
    public function email()
    {
        $settings = NotificationSetting::getOrCreate(Auth::id());
        return view('user.settings.notificationsetting.email-notification', compact('settings'));
    }

    public function updateEmail(Request $request)
    {
        $settings = NotificationSetting::getOrCreate(Auth::id());
        $settings->update([
            'email_new_match'      => $request->boolean('email_new_match'),
            'email_new_message'    => $request->boolean('email_new_message'),
            'email_profile_views'  => $request->boolean('email_profile_views'),
            'email_likes_received' => $request->boolean('email_likes_received'),
        ]);
        return back()->with('success', 'Email notification settings saved successfully.');
    }

    // ── Security ──
    public function security()
    {
        $settings = NotificationSetting::getOrCreate(Auth::id());
        return view('user.settings.notificationsetting.security-notification', compact('settings'));
    }

    public function updateSecurity(Request $request)
    {
        $settings = NotificationSetting::getOrCreate(Auth::id());
        $settings->update([
            'security_login_alerts'        => $request->boolean('security_login_alerts'),
            'security_new_device'          => $request->boolean('security_new_device'),
            'security_password_change'     => $request->boolean('security_password_change'),
            'security_suspicious_activity' => $request->boolean('security_suspicious_activity'),
        ]);
        return back()->with('success', 'Security alert settings saved successfully.');
    }
}