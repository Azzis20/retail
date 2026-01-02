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
            $table->string('type'); // order, payment, inventory, stock_alert
            $table->string('title');
            $table->text('message');
            $table->foreignId('related_id')->nullable(); // Order ID, Payment ID, Product ID
            $table->string('related_type')->nullable(); // Order, Payment, Product
            $table->boolean('is_read')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
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
