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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('customer_id')
            //     ->constrained('customers')
            //     ->cascadeOnDelete();
            $table->string('order_number');
            $table->decimal('total_price', 10, 2);
            $table->enum('status', ['pending', 'processing', 'packed', 'completed', 'declined'])
                ->default('pending');
            $table->decimal('shipping_price')->nullable();
            $table->text('note')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order');
    }
};
