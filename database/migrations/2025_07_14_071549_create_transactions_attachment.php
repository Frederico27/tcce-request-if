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
        Schema::create('transactions_attachment', function (Blueprint $table) {
            $table->id('id_transaction_attach');
            $table->foreignId('id_transaction_detail')
                ->constrained('transactions_details', 'id_transaction_detail')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->string('file_path', 255);
            $table->enum('file_type', ['image', 'document', 'other']);
            $table->string('uploaded_by', 100);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions_attachment');
    }
};
