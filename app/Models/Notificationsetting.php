<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationSetting extends Model
{
    use HasFactory;

    protected $table = 'notificationsettings';

    protected $fillable = [
        'user_id',
        'push_new_match',
        'push_new_message',
        'push_profile_views',
        'push_likes_received',
        'inapp_new_match',
        'inapp_new_message',
        'inapp_profile_views',
        'inapp_likes_received',
        'email_new_match',
        'email_new_message',
        'email_profile_views',
        'email_likes_received',
        'security_login_alerts',
        'security_new_device',
        'security_password_change',
        'security_suspicious_activity',
    ];

    protected $casts = [
        'push_new_match'               => 'boolean',
        'push_new_message'             => 'boolean',
        'push_profile_views'           => 'boolean',
        'push_likes_received'          => 'boolean',
        'inapp_new_match'              => 'boolean',
        'inapp_new_message'            => 'boolean',
        'inapp_profile_views'          => 'boolean',
        'inapp_likes_received'         => 'boolean',
        'email_new_match'              => 'boolean',
        'email_new_message'            => 'boolean',
        'email_profile_views'          => 'boolean',
        'email_likes_received'         => 'boolean',
        'security_login_alerts'        => 'boolean',
        'security_new_device'          => 'boolean',
        'security_password_change'     => 'boolean',
        'security_suspicious_activity' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getOrCreate(int $userId): self
    {
        return self::firstOrCreate(
            ['user_id' => $userId],
            [
                'push_new_match'               => true,
                'push_new_message'             => true,
                'push_profile_views'           => true,
                'push_likes_received'          => true,
                'inapp_new_match'              => true,
                'inapp_new_message'            => true,
                'inapp_profile_views'          => true,
                'inapp_likes_received'         => true,
                'email_new_match'              => true,
                'email_new_message'            => false,
                'email_profile_views'          => false,
                'email_likes_received'         => true,
                'security_login_alerts'        => true,
                'security_new_device'          => true,
                'security_password_change'     => true,
                'security_suspicious_activity' => true,
            ]
        );
    }
}