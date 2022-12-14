<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SubAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropColumn('account_state');
            $table->dropColumn('account_gst');
            $table->dropColumn('account_address_1');
            $table->dropColumn('account_address_2');
        });
        Schema::create('subaccounts', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('account_slug');
            $table->string('account_state');
            $table->string('account_address_1');
            $table->string('account_address_2');
            $table->string('account_gst');
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('account_slug')->references('slug')->on('accounts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->string('account_state');
            $table->string('account_gst');
            $table->string('account_address_1');
            $table->string('account_address_2');
        });
        Schema::dropIfExists('subaccounts');
    }
}
