<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFactoryRcnInwardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('factory_rcn_inwards', function($table) {
            $table->string('slug');
            $table->dropColumn('truck_reg_number');
            $table->dropColumn('status');
            $table->dropColumn('moisture_level');
            $table->dropColumn('dispatched_date_time');
            $table->dropColumn('received_date_time');
            $table->dropColumn('contact_number');
            $table->dropColumn('document');
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
