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
       Schema::table('users', function (Blueprint $table) {
    $table->boolean('is_blocked')->default(false)->after('role');
    $table->boolean('warning_sent')->default(false)->after('is_blocked');
    $table->timestamp('blocked_at')->nullable()->after('warning_sent');
    $table->text('block_reason')->nullable()->after('blocked_at');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
