<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterRcnOutwardDispatchRecivedNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stockyard_inward_rcns', function (Blueprint $table) {
            $table->dateTime('dispatched_date_time')->nullable()->change();
            $table->dateTime('received_date_time')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stockyard_inward_rcns', function (Blueprint $table) {
            //
        });
    }
}
