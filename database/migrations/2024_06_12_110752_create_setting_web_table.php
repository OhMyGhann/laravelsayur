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
        Schema::create('setting_webs', function (Blueprint $table) {
            $table->id();
            $table->string('logo_1')->nullable();
            $table->string('logo_2')->nullable();
            $table->string('logo_3')->nullable();
            $table->string('warna_1')->nullable();
            $table->string('warna_2')->nullable();
            $table->string('warna_3')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setting_web');
    }
};
