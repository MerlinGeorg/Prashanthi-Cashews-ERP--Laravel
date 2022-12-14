<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFactoryRcnStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('factory_rcn_stocks', function (Blueprint $table) {
            $table->id();
            $table->string('factory_slug');
            $table->string('stockyard_rcn_stock_slug');
            $table->string('total_rcn_factory_stock');
            $table->string('total_rcn_bag');
            $table->string('balance_rcn_factory_stock');
            $table->string('balance_rcn_bag');
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
        Schema::dropIfExists('factory_rcn_stocks');
    }
}
