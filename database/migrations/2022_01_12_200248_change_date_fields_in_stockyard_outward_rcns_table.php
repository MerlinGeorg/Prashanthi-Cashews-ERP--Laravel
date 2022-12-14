<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDateFieldsInStockyardOutwardRcnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stockyard_outward_rcns', function (Blueprint $table) {
            $table->datetime('dispatched_date_time')->change()->nullable();
            $table->datetime('received_date_time')->change()->nullable();
            $table->string('document')->change()->nullable();
            $table->float('out_turn')->change()->nullable();
            $table->float('nut_count')->change()->nullable();
            $table->float('rejection')->change()->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stockyard_outward_rcns', function (Blueprint $table) {
            //
        });
    }
}