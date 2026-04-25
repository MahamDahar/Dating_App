<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('user_blocks', 'reason')) {
            Schema::table('user_blocks', function (Blueprint $table) {
                $table->string('reason')->nullable()->after('blocked_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('user_blocks', 'reason')) {
            Schema::table('user_blocks', function (Blueprint $table) {
                $table->dropColumn('reason');
            });
        }
    }
};

