<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockyardRcnSplitStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stockyard_rcn_stocks_split', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('lot_number');
            $table->string('account');
            $table->string('rcn_kg');
            $table->string('rcn_bags');
            $table->string('stockyard');
          //  $table->string('balance_wholes')->nullable();
         
 
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stockyard_rcn_stocks_split');
    }
}
