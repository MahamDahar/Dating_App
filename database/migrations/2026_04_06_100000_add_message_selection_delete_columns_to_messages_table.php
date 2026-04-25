<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            if (! Schema::hasColumn('messages', 'hidden_for_sender_at')) {
                $table->timestamp('hidden_for_sender_at')->nullable()->after('read_at');
            }
            if (! Schema::hasColumn('messages', 'hidden_for_receiver_at')) {
                $table->timestamp('hidden_for_receiver_at')->nullable()->after('hidden_for_sender_at');
            }
            if (! Schema::hasColumn('messages', 'revoked_at')) {
                $table->timestamp('revoked_at')->nullable()->after('hidden_for_receiver_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            if (Schema::hasColumn('messages', 'revoked_at')) {
                $table->dropColumn('revoked_at');
            }
            if (Schema::hasColumn('messages', 'hidden_for_receiver_at')) {
                $table->dropColumn('hidden_for_receiver_at');
            }
            if (Schema::hasColumn('messages', 'hidden_for_sender_at')) {
                $table->dropColumn('hidden_for_sender_at');
            }
        });
    }
};
