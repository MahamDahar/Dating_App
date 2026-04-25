<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('plan_id')->nullable()->constrained('subscription_plans')->onDelete('set null');
            $table->string('transaction_id')->unique()->nullable(); // Stripe session ID or manual ref
            $table->decimal('amount', 8, 2);
            $table->string('currency')->default('USD');
            $table->enum('payment_method', ['stripe', 'manual'])->default('stripe');
            $table->enum('status', ['paid', 'pending', 'failed', 'refunded'])->default('paid');
            $table->string('stripe_session_id')->nullable();
            $table->string('stripe_payment_intent')->nullable();
            $table->text('notes')->nullable(); // For manual payments
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};