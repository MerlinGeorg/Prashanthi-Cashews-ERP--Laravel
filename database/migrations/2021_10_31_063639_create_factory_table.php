<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFactoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('factories', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('factory_of');
            $table->string('factory_sub_account_slug');
            $table->string('factory_office_slug')->nullable();
            $table->string('factory_name');
            $table->string('factory_short_name');
            $table->string('factory_reg_number');
            $table->string('factory_location');
            $table->string('factory_power_allocation');
            $table->string('factory_contact_address_1');
            $table->string('factory_contact_address_2');
            $table->string('factory_state');
            $table->string('factory_pincode');
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('factory_sub_account_slug')->references('slug')->on('subaccounts');
            $table->foreign('factory_office_slug')->references('slug')->on('offices');
        });
        Schema::table('factories', function (Blueprint $table) {
            $table->unique('slug');
        });
        Schema::create('factory_processing', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('factory_slug');
            $table->string('factory_processing_types');
            $table->string('factory_processing_capacity');
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('factory_slug')->references('slug')->on('factories');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('factory_processing');
        Schema::table('factories', function (Blueprint $table) {
            $table->dropUnique('slug');
        });
        Schema::dropIfExists('factories');
       
    }
}
