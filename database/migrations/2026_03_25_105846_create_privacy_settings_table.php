<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('privacy_settings', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->enum('who_can_send_proposals', ['everyone', 'no_one'])->default('everyone');
    $table->enum('who_can_see_photos',     ['everyone', 'matches_only', 'no_one'])->default('everyone');
    $table->enum('who_can_see_online',     ['everyone', 'matches_only', 'no_one'])->default('everyone');
    $table->enum('who_can_see_last_active',['everyone', 'matches_only', 'no_one'])->default('everyone');
    $table->enum('who_can_message',        ['everyone', 'matches_only', 'no_one'])->default('everyone');
    $table->boolean('show_on_search_engines')->default(true);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('privacy_settings');
    }
};
