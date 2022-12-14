<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBalanceCuttingRcnStock extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('factory_cutting_stocks', function ($table) {
            $table->decimal('balance_cutting_rcn_stock',15,2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('factory_cutting_stocks', function ($table) {
            $table->dropColumn('balance_cutting_rcn_stock');
        });
    }
}
