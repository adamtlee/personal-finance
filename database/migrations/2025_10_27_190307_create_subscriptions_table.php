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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->enum('billing_frequency', ['weekly', 'monthly', 'yearly'])->default('monthly');
            $table->text('description')->nullable();
            $table->enum('category', ['entertainment', 'software', 'news', 'fitness', 'education', 'productivity', 'cloud_storage', 'music', 'video', 'gaming', 'food_delivery', 'transportation', 'other'])->default('other');
            $table->enum('status', ['active', 'paused', 'cancelled', 'expired'])->default('active');
            $table->date('next_billing_date')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
