<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCuttingTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cutting_stocks', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('factory_slug');
            $table->string('cutting_work_number');
            $table->timestamp('factory_stock_slug')->nullable();
            $table->float('cutting_date_time')->nullable();
            $table->float('given_rcn_bag')->nullable();
            $table->float('wholes')->nullable();
            $table->float('brokens')->nullable();
            $table->float('piruwel')->nullable();
            $table->float('rejection')->nullable();
            $table->float('uncut')->nullable();
            $table->float('unscoop')->nullable();
            $table->float('balance_wholes')->nullable();
            $table->float('balance_brokens')->nullable();
            $table->float('balance_piruwel')->nullable();
            $table->float('balance_cutting_selling_kernals')->nullable();
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
        Schema::dropIfExists('cutting_stocks');
    }
}
