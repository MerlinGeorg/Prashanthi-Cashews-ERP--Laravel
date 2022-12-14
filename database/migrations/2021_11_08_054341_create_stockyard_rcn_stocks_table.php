<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockyardRcnStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stockyard_rcn_stocks', function (Blueprint $table) {
            $table->id();
            $table->integer('stockyard_id');
            $table->integer('sub_account_id');
            $table->integer('shipper_company');
            $table->string('lot_number');
            $table->string('rcn_mark');
            $table->string('be_number');
            $table->string('bl_number');
            $table->string('invoice_number');
            $table->string('bl_despatched_rcn_weight');
            $table->string('bl_despatched_rcn_bags');
            $table->string('balance_rcn_stock')->nullable();
            $table->string('balance_rcn_bag')->nullable();
            $table->integer('out_turn');
            $table->integer('nut_count');
            $table->string('rejection');
            $table->softDeletes();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stockyard_rcn_stocks');
    }
}
