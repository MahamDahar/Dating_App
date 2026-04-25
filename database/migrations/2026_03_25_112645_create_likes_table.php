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
       Schema::create('likes', function (Blueprint $table) {
    $table->id();
    $table->foreignId('liker_id')->constrained('users')->onDelete('cascade');
    $table->foreignId('liked_id')->constrained('users')->onDelete('cascade');
    $table->timestamps();

    $table->unique(['liker_id', 'liked_id']); // can't like twice
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
};
