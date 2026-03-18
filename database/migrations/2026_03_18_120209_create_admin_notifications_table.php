<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type');  // new_user, new_report, new_subscription, support_ticket, account_blocked, verification_request
            $table->string('title');
            $table->text('message');
            $table->unsignedBigInteger('related_id')->nullable();  // user_id, report_id etc
            $table->string('related_type')->nullable();  // User, Report etc
            $table->string('icon')->default('notifications-outline');
            $table->string('color')->default('primary');  // primary, danger, success, warning, info
            $table->string('url')->nullable();  // click karne pe kahan jaye
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_notifications');
    }
};