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
        Schema::create('transactions_details', function (Blueprint $table) {
            $table->id('id_transaction_detail');
            $table->uuid('id_transactions');
            $table->foreign('id_transactions')->references('id_transactions')->on('transactions')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->string('used_for', 255);
            $table->unsignedBigInteger('id_sub_category');
            $table->foreign('id_sub_category')->references('id_sub_category')->on('sub_categories')
                ->onUpdate('cascade');
            $table->decimal('amount', 15, 2);
            $table->decimal('addicional_amount', 15, 2)->default(0);
          
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions_details');
    }
};
