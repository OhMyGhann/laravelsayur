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
        Schema::table('setting_webs', function (Blueprint $table) {
            $table->integer('phone')->nullable()->after('warna_3');
            $table->json('social_media')->nullable()->after('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('setting_webs', function (Blueprint $table) {
            $table->dropColumn('phone');
            $table->dropColumn('social_media');
        });
    }
};
