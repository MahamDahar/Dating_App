<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Some databases may have an older `payments` table without
        // `payment_method` and/or `status` columns. The application
        // expects both, so we add them defensively if missing.
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'payment_method')) {
                $table->enum('payment_method', ['stripe', 'manual'])->default('stripe');
            }

            if (!Schema::hasColumn('payments', 'status')) {
                $table->enum('status', ['paid', 'pending', 'failed', 'refunded'])->default('paid');
            }
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('payments', 'status')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};

