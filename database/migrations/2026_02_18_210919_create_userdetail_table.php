<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Step 1
            $table->string('sect')->nullable();

            // Step 2
            $table->string('profession')->nullable();

            // Step 3
            $table->string('education')->nullable();

            // Step 4
            $table->boolean('notifications_enabled')->default(false);

            // Step 5
            $table->boolean('hide_from_contacts')->default(false);

            // Step 6
            $table->string('nationality')->nullable();

            // Step 7
            $table->string('grew_up')->nullable();

            // Step 8
            $table->string('ethnicity')->nullable(); // comma-separated

            // Step 9
            $table->unsignedSmallInteger('height_cm')->nullable();

            // Step 10
            $table->string('marital_status')->nullable();

            // Step 11
            $table->string('marriage_intentions')->nullable();

            // Step 12
            $table->string('religion_practice')->nullable();

            // Step 13
            $table->enum('born_muslim', ['Yes', 'No', 'Prefer not to say'])->nullable();

            // Step 14
            $table->text('interests')->nullable(); // comma-separated

            // Step 15
            $table->text('bio')->nullable();

            // Step 16
            $table->string('personality')->nullable(); // comma-separated

            // Profile completion
            $table->unsignedTinyInteger('profile_completion')->default(0); // 0-100%

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};