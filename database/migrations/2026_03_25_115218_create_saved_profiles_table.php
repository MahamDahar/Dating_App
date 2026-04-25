<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saved_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');    // jisne save kiya
            $table->foreignId('saved_user_id')->constrained('users')->onDelete('cascade'); // jise save kiya
            $table->timestamps();

            // Ek user ek profile sirf ek baar save kar sakta hai
            $table->unique(['user_id', 'saved_user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saved_profiles');
    }
};