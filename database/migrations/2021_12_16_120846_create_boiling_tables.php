<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoilingTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boiling_stocks', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('factory_slug');
            $table->string('boiling_number');
            $table->timestamp('boiling_date_time')->nullable();
            $table->float('aplus_total_weight')->nullable();
            $table->float('a1_total_weight')->nullable();
            $table->float('a2_total_weight')->nullable();
            $table->float('b1_total_weight')->nullable();
            $table->float('b2_total_weight')->nullable();
            $table->float('c1_total_weight')->nullable();
            $table->float('c2_total_weight')->nullable();
            $table->float('d1_total_weight')->nullable();
            $table->float('d2_total_weight')->nullable();
            $table->float('total_boiling_weight')->nullable();
            $table->float('aplus_balance_weight')->nullable();
            $table->float('a1_balance_weight')->nullable();
            $table->float('a2_balance_weight')->nullable();
            $table->float('b1_balance_weight')->nullable();
            $table->float('b2_balance_weight')->nullable();
            $table->float('c1_balance_weight')->nullable();
            $table->float('c2_balance_weight')->nullable();
            $table->float('d1_balance_weight')->nullable();
            $table->float('d2_balance_weight')->nullable();
            $table->float('balance_boiling_weight')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('factory_slug')->references('slug')->on('factories');
        });
        Schema::table('sizering_stocks', function (Blueprint $table) {
            $table->unique('slug');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('boiling_stocks');
    }
}
