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
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id_transactions')->primary();
            $table->enum('action', ['request', 'return', 'transfer']);
            $table->string('activity', 255);
            $table->string('description', 255);
            $table->decimal('amount', 15, 2)->default(0);
            $table->decimal('remaining_amount', 15, 2)->default(0);
            $table->decimal('additional_amount', 15, 2)->default(0);
            $table->foreignId('from_user_id')->constrained('users', 'id')
                ->onUpdate('cascade');
            $table->foreignId('to_user_id')->constrained('users', 'id')
                ->onUpdate('cascade');
            $table->string('requested_by', 100);
            $table->json('approved_by')->nullable();
            $table->string('rejected_by', 100)->nullable();
            $table->string('rejection_reason', 255)->nullable();
            $table->enum('status', ['draft', 'pending', 'manager_approved', 'admin_approved', 'senior_approved', 'vice_approved', 'verified', 'completed', 'rejected']);
            // $table->uuid('parent_transaction_id')->nullable();
            // $table->foreign('parent_transaction_id')
            //     ->references('id_transactions')->on('transactions')
            //     ->onUpdate('cascade');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['from_user_id', 'to_user_id', 'action']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
