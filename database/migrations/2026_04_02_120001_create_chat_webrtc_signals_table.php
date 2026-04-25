<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_webrtc_signals', function (Blueprint $table) {
            $table->id();
            $table->string('room', 64)->index();
            $table->foreignId('from_user_id')->constrained('users')->cascadeOnDelete();
            $table->string('kind', 32);
            $table->longText('payload');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_webrtc_signals');
    }
};
