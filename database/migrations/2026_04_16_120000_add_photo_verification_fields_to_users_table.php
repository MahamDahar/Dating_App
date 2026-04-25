<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'photo_verified')) {
                $table->boolean('photo_verified')->default(false)->after('email_verified_at');
            }
            if (!Schema::hasColumn('users', 'photo_verified_at')) {
                $table->timestamp('photo_verified_at')->nullable()->after('photo_verified');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'photo_verified_at')) {
                $table->dropColumn('photo_verified_at');
            }
            if (Schema::hasColumn('users', 'photo_verified')) {
                $table->dropColumn('photo_verified');
            }
        });
    }
};

