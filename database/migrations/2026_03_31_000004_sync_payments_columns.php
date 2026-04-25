<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Defensive schema sync:
        // Your app code expects a full `payments` table (amount/status/paid_at/etc.),
        // but some environments may have an older/partial table.
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'user_id')) {
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            }

            if (!Schema::hasColumn('payments', 'plan_id')) {
                $table->foreignId('plan_id')->nullable()->constrained('subscription_plans')->onDelete('set null');
            }

            if (!Schema::hasColumn('payments', 'transaction_id')) {
                // Unique constraint is set by original migration, but we only add column safely here.
                $table->string('transaction_id')->unique()->nullable();
            }

            if (!Schema::hasColumn('payments', 'amount')) {
                $table->decimal('amount', 8, 2)->default(0);
            }

            if (!Schema::hasColumn('payments', 'currency')) {
                $table->string('currency')->default('USD');
            }

            if (!Schema::hasColumn('payments', 'payment_method')) {
                $table->enum('payment_method', ['stripe', 'manual'])->default('stripe');
            }

            if (!Schema::hasColumn('payments', 'status')) {
                $table->enum('status', ['paid', 'pending', 'failed', 'refunded'])->default('paid');
            }

            if (!Schema::hasColumn('payments', 'stripe_session_id')) {
                $table->string('stripe_session_id')->nullable();
            }

            if (!Schema::hasColumn('payments', 'stripe_payment_intent')) {
                $table->string('stripe_payment_intent')->nullable();
            }

            if (!Schema::hasColumn('payments', 'notes')) {
                $table->text('notes')->nullable();
            }

            if (!Schema::hasColumn('payments', 'paid_at')) {
                $table->timestamp('paid_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        // Optional rollback. Only drop columns that exist.
        Schema::table('payments', function (Blueprint $table) {
            foreach ([
                'user_id',
                'plan_id',
                'transaction_id',
                'amount',
                'currency',
                'payment_method',
                'status',
                'stripe_session_id',
                'stripe_payment_intent',
                'notes',
                'paid_at',
            ] as $column) {
                if (Schema::hasColumn('payments', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};

