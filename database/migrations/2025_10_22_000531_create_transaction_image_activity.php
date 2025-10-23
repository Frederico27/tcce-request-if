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
        Schema::create('transaction_image_activity', function (Blueprint $table) {
            $table->id('id_image_activity');
            $table->foreignId('id_transaction_detail')
                ->constrained('transactions_details', 'id_transaction_detail')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->string('description');
            $table->string('image_path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_image_activity');
    }
};
