<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Fix for deployments where `payments.amount` column is missing
        // but application code expects it (AdminPaymentController uses SUM(amount)).
        if (!Schema::hasColumn('payments', 'amount')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->decimal('amount', 8, 2)->default(0);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('payments', 'amount')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->dropColumn('amount');
            });
        }
    }
};

