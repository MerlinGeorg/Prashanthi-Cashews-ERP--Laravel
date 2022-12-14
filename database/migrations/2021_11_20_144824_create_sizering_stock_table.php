<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSizeringStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sizering_stocks', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('factory_slug');
            $table->string('factory_stock_slug');
            $table->string('sizering_number');
            $table->float('rcn_weight')->nullable();
            $table->float('rcn_bag')->nullable();
            $table->timestamp('sizering_date_time')->nullable();
            $table->float('aplus_total_weight')->nullable();
            $table->float('a1_total_weight')->nullable();
            $table->float('a2_total_weight')->nullable();
            $table->float('b1_total_weight')->nullable();
            $table->float('b2_total_weight')->nullable();
            $table->float('c1_total_weight')->nullable();
            $table->float('c2_total_weight')->nullable();
            $table->float('d1_total_weight')->nullable();
            $table->float('d2_total_weight')->nullable();
            $table->float('foreign_matter_total_weight')->nullable();
            $table->float('aplus_balance_weight')->nullable();
            $table->float('a1_balance_weight')->nullable();
            $table->float('a2_balance_weight')->nullable();
            $table->float('b1_balance_weight')->nullable();
            $table->float('b2_balance_weight')->nullable();
            $table->float('c1_balance_weight')->nullable();
            $table->float('c2_balance_weight')->nullable();
            $table->float('d1_balance_weight')->nullable();
            $table->float('d2_balance_weight')->nullable();
            $table->float('total_sizering_rcn_weight')->nullable();
            $table->float('balance_sizering_rcn_weight')->nullable();
            $table->float('total_sizering_rcn_bag')->nullable();
            $table->float('balance_sizering_rcn_bag')->nullable();
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
        Schema::dropIfExists('sizering_stocks');
    }
}
