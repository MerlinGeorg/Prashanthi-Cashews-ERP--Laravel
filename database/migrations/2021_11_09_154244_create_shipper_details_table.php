<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipperDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipper_details', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('shipper_company_name');
            $table->string('shipper_location');
            $table->string('shipper_contact_address_1');
            $table->string('shipper_contact_address_2');
            $table->string('shipper_state');
            $table->string('shipper_pincode');
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
        Schema::dropIfExists('shipper_details');
    }
}
