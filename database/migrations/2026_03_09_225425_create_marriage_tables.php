<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Likes ──
        Schema::create('marriage_likes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('liker_id');
            $table->unsignedBigInteger('liked_id');
            $table->foreign('liker_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('liked_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['liker_id', 'liked_id']);
        });

        // ── Favourites ──
        Schema::create('marriage_favourites', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('favourite_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('favourite_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['user_id', 'favourite_id']);
        });

        // ── Matches (mutual likes) ──
        Schema::create('marriage_matches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_one_id');
            $table->unsignedBigInteger('user_two_id');
            $table->foreign('user_one_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_two_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('matched_at')->useCurrent();
            $table->timestamps();
            $table->unique(['user_one_id', 'user_two_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marriage_matches');
        Schema::dropIfExists('marriage_favourites');
        Schema::dropIfExists('marriage_likes');
    }
};