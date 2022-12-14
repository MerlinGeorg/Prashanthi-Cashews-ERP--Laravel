<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackagingCenterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packaging_centers', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('package_center_sub_account_slug');
            $table->string('package_center_office_slug');
            $table->string('package_center_name');
            $table->string('package_center_short_name');
            $table->string('package_center_reg_number');
            $table->string('package_center_power_allocation');
            $table->string('package_center_location');
            $table->string('package_center_state');
            $table->string('package_center_contact_address_1');
            $table->string('package_center_contact_address_2');
            $table->string('package_center_pincode');
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('package_center_sub_account_slug')->references('slug')->on('subaccounts');
            $table->foreign('package_center_office_slug')->references('slug')->on('offices');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('packaging_centers');
    }
}