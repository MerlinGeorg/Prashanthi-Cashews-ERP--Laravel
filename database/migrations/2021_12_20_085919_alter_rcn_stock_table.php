<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterRcnStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stockyard_rcn_stocks', function($table) {
            $table->decimal('bl_despatched_rcn_weight',10,2)->default(0)->change();
            $table->decimal('bl_despatched_rcn_bags',10,2)->default(0)->change();
            $table->decimal('balance_rcn_stock',10,2)->default(0)->change();
            $table->decimal('balance_rcn_bag',10,2)->default(0)->change();
            $table->decimal('out_turn',10,2)->default(0)->change();
            $table->decimal('nut_count',10,2)->default(0)->change();
            $table->decimal('rejection',10,2)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}