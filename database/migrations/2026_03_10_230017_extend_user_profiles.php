<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Add new columns to user_profiles ──
        Schema::table('user_profiles', function (Blueprint $table) {
            // Personal
            $table->date('date_of_birth')->nullable()->after('marital_status');
            $table->string('city')->nullable()->after('grew_up');
            $table->string('country')->nullable()->after('city');

            // Lifestyle
            $table->string('smoking')->nullable()->after('height_cm');   // Never / Occasionally / Yes
            $table->string('drinking')->nullable()->after('smoking');     // Never / Occasionally / Yes
            $table->string('want_children')->nullable()->after('drinking'); // Yes / No / Open to it
            $table->string('num_children')->nullable()->after('want_children'); // 1,2,3,4+,None yet

            // Languages
            $table->string('languages')->nullable()->after('num_children'); // comma separated
        });

        // ── Profile Photos table ──
        Schema::create('user_profile_photos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('path');           // storage path
            $table->boolean('is_main')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_profile_photos');
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'date_of_birth','city','country',
                'smoking','drinking','want_children','num_children','languages'
            ]);
        });
    }
};