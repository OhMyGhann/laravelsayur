<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifySubTotalInOrderItemsTable extends Migration
{
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->decimal('sub_total', 15, 2)->change();
        });
    }

    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->integer('sub_total')->change(); // Atur kembali ke tipe data awal jika diperlukan
        });
    }
}
