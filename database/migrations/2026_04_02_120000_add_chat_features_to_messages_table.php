<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            if (! Schema::hasColumn('messages', 'read_at')) {
                $table->timestamp('read_at')->nullable()->after('message');
            }
            if (! Schema::hasColumn('messages', 'message_type')) {
                $table->string('message_type', 20)->default('text')->after('message');
            }
            if (! Schema::hasColumn('messages', 'media_path')) {
                $table->string('media_path', 500)->nullable()->after('message_type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            if (Schema::hasColumn('messages', 'media_path')) {
                $table->dropColumn('media_path');
            }
            if (Schema::hasColumn('messages', 'message_type')) {
                $table->dropColumn('message_type');
            }
            if (Schema::hasColumn('messages', 'read_at')) {
                $table->dropColumn('read_at');
            }
        });
    }
};
