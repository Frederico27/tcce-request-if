<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admin_asman', function (Blueprint $table) {
            $table->id('id_admin_asman');
            $table->foreignId('id_admin')
                ->constrained('users', 'id')
                ->onDelete('cascade');
            $table->foreignId('id_asman')
                ->constrained('users', 'id')
                ->onDelete('cascade');

            // Mencegah satu asman dimiliki lebih dari satu admin
            $table->unique('id_asman');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_asman');
    }
};
