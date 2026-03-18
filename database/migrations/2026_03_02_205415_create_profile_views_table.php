<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profile_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('viewer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('viewed_id')->constrained('users')->onDelete('cascade');
            $table->boolean('seen')->default(false);  // false = unseen (shows badge)
            $table->timestamp('viewed_at')->useCurrent();
            $table->timestamps();

            // One record per viewer per profile — update viewed_at on re-visit
            $table->unique(['viewer_id', 'viewed_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profile_views');
    }
};