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
        Schema::create('metodes', function (Blueprint $table) {
            $table->id();
            $table->string('image_path');
            $table->string('bank_name');
            $table->string('bank_code');
            $table->integer('no_rekening')->nullable();
            $table->float('fee_bank');
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metode');
    }
};
