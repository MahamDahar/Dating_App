<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('user_profile_photos', 'is_blurred')) {
            Schema::table('user_profile_photos', function (Blueprint $table) {
                $table->boolean('is_blurred')->default(false)->after('is_main');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('user_profile_photos', 'is_blurred')) {
            Schema::table('user_profile_photos', function (Blueprint $table) {
                $table->dropColumn('is_blurred');
            });
        }
    }
};

