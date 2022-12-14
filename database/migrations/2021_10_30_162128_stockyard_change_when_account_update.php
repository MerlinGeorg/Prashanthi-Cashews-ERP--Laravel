<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StockyardChangeWhenAccountUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stockyards', function (Blueprint $table) {
            $table->dropForeign('erp_stockyards_account_slug_foreign');
            $table->dropColumn('account_slug');
            $table->string('sub_account_slug');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stockyards', function (Blueprint $table) {
            $table->string('account_slug');
            $table->foreign('account_slug')->references('slug')->on('accounts');
            $table->dropColumn('sub_account_slug');
        });
    }
}
