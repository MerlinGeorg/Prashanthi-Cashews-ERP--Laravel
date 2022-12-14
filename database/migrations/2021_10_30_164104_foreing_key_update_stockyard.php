<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ForeingKeyUpdateStockyard extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subaccounts', function (Blueprint $table) {
            $table->unique('slug');
        });
        Schema::table('stockyards', function (Blueprint $table) {
            $table->foreign('sub_account_slug')->references('slug')->on('subaccounts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subaccounts', function (Blueprint $table) {
            $table->dropUnique('slug');
        });
        Schema::table('stockyards', function (Blueprint $table) {
            $table->dropForeign('erp_stockyards_sub_account_slug_foreign');
        });
    }
}
