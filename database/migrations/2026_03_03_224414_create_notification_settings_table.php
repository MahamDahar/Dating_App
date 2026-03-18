<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notificationsettings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Push Notifications
            $table->boolean('push_new_match')->default(true);
            $table->boolean('push_new_message')->default(true);
            $table->boolean('push_profile_views')->default(true);
            $table->boolean('push_likes_received')->default(true);

            // In-App Notifications
            $table->boolean('inapp_new_match')->default(true);
            $table->boolean('inapp_new_message')->default(true);
            $table->boolean('inapp_profile_views')->default(true);
            $table->boolean('inapp_likes_received')->default(true);

            // Email Notifications
            $table->boolean('email_new_match')->default(true);
            $table->boolean('email_new_message')->default(false);
            $table->boolean('email_profile_views')->default(false);
            $table->boolean('email_likes_received')->default(true);

            // Security Alerts
            $table->boolean('security_login_alerts')->default(true);
            $table->boolean('security_new_device')->default(true);
            $table->boolean('security_password_change')->default(true);
            $table->boolean('security_suspicious_activity')->default(true);

            $table->timestamps();
            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notificationsettings');
    }
};