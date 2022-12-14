<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockyardOutwardRcnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stockyard_outward_rcns', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('stockyard_rcn_stock_slug');
            $table->string('factory_slug');
            $table->string('truck_reg_number');
            $table->string('dc_number');
            $table->string('ewb_number');
            $table->string('rcn_bags');
            $table->string('rcn_net_weight');
            $table->string('tare_weight');
            $table->integer('status')->comment('0=>Schedule, 1=>Dispatch, 2=>Received');
            $table->integer('moisture_level')->comment('0=>Dry, 1=>Semi, 2=> Un Dry');
            $table->dateTime('dispatched_date_time');
            $table->dateTime('received_date_time');
            $table->string('contact_number');
            $table->integer('out_turn');
            $table->integer('nut_count');
            $table->string('rejection');
            $table->string('document');
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
        Schema::dropIfExists('stockyard_outward_rcns');
    }
}
