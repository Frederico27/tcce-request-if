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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade'); // if user is deleted, delete notifications too
            $table->string('title');
            $table->text('message');
            $table->boolean('is_read')->default(false); // to track read/unread
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            $table->softDeletes(); // to allow soft deletion of notifications
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
