<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('marriage_passes')) {
            Schema::create('marriage_passes', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('passer_id');
                $table->unsignedBigInteger('passed_id');
                $table->foreign('passer_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('passed_id')->references('id')->on('users')->onDelete('cascade');
                $table->timestamps();

                $table->unique(['passer_id', 'passed_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('marriage_passes');
    }
};

