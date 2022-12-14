<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAddressOfficeStockAccount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('offices', function (Blueprint $table) {
            $table->text('office_state');
        });
        Schema::table('accounts', function (Blueprint $table) {
            $table->string('account_address_2');
            $table->renameColumn('account_address', 'account_address_1');
        });
        Schema::table('stockyards', function (Blueprint $table) {
            $table->renameColumn('contact_address', 'contact_address_1');
            $table->string('contact_address_2');
            $table->text('stockyard_state');
            $table->text('stockyard_pincode');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offices', function (Blueprint $table) {
            $table->dropColumn('office_state');
        });
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropColumn('account_address_2');
            $table->renameColumn('account_address_1', 'account_address');
        });
        Schema::table('stockyards', function (Blueprint $table) {
            $table->renameColumn('contact_address_1', 'contact_address');
            $table->dropColumn('contact_address_2');
            $table->dropColumn('stockyard_state');
            $table->dropColumn('stockyard_pincode');
        });
    }
}
