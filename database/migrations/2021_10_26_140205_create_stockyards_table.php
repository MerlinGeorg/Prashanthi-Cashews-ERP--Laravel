<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockyardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('offices', function (Blueprint $table) {
            $table->unique('slug');
        });
        Schema::table('accounts', function (Blueprint $table) {
            $table->unique('slug');
        });
        Schema::create('stockyards', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('account_slug');
            $table->string('office_slug');
            $table->string('stockyard_name');
            $table->string('stockyard_short_name');
            $table->string('stockyard_reg_number');
            $table->string('contact_address');
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('account_slug')->references('slug')->on('accounts');
            $table->foreign('office_slug')->references('slug')->on('offices');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stockyards');
    }
}






