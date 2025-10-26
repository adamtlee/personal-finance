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
        // Drop the old banks table if it exists
        Schema::dropIfExists('banks');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate the banks table (for rollback)
        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['checking', 'savings', 'credit_card', 'money_market', 'cd', 'other']);
            $table->decimal('amount', 15, 2)->default(0);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }
};
