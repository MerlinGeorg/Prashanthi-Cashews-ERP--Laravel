<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveStatePincodeFromShipperDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipper_details', function (Blueprint $table) {
            $table->string('shipper_contact_address_2')->nullable()->change();
            $table->dropColumn('shipper_state');
            $table->dropColumn('shipper_pincode');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shipper_details', function (Blueprint $table) {
            $table->string('shipper_state');
            $table->string('shipper_pincode');
        });
    }
}
