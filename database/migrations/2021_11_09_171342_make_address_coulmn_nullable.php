<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeAddressCoulmnNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('offices', function (Blueprint $table) {
            $table->string('office_address_2')->nullable()->change();
        });
        Schema::table('subaccounts', function (Blueprint $table) {
            $table->string('account_address_2')->nullable()->change();
        });
        Schema::table('stockyards', function (Blueprint $table) {
            $table->string('contact_address_2')->nullable()->change();
        });
        Schema::table('factories', function (Blueprint $table) {
            $table->string('factory_contact_address_2')->nullable()->change();
        });
        Schema::table('packaging_centers', function (Blueprint $table) {
            $table->string('package_center_contact_address_2')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
