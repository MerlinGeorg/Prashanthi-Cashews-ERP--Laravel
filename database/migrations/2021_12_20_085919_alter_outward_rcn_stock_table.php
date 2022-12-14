<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOutwardRcnStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stockyard_inward_rcns', function($table) {
            $table->decimal('out_turn',12,2)->default(0)->change();
            $table->decimal('nut_count',12,2)->default(0)->change();
            $table->decimal('rejection',12,2)->default(0)->change();
        });

        Schema::table('stockyard_outward_rcns', function($table) {
            $table->decimal('out_turn',12,2)->default(0)->change();
            $table->decimal('nut_count',12,2)->default(0)->change();
            $table->decimal('rejection',12,2)->default(0)->change();
        });

        Schema::table('stockyard_rcn_stocks', function($table) {
            $table->decimal('bl_despatched_rcn_weight',12,2)->default(0)->change();
            $table->decimal('bl_despatched_rcn_bags',12,2)->default(0)->change();
            $table->decimal('balance_rcn_stock',12,2)->default(0)->change();
            $table->decimal('balance_rcn_bag',12,2)->default(0)->change();
            $table->decimal('out_turn',12,2)->default(0)->change();
            $table->decimal('nut_count',12,2)->default(0)->change();
            $table->decimal('rejection',12,2)->default(0)->change();
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