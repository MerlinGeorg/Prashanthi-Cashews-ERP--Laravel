<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameCuttingToFactoryCutting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('cutting_boiling_mapping', 'factory_cutting_boiling_mapping');
        Schema::rename('cutting_stocks', 'factory_cutting_stocks');

        Schema::table('factory_cutting_stocks', function ($table) {
            $table->renameColumn('factory_stock_slug', 'stockyard_rcn_stock_slug')->nullable();
            $table->string('cutting_type')->nullable();
            $table->decimal('given_rcn_weight',15,2)->nullable();
            $table->decimal('total_cutting_weight',15,2)->nullable();
            $table->decimal('balance_cutting_weight',15,2)->nullable();
        });

        Schema::table('factory_boiling_stocks', function ($table) {
            $table->integer('balance_boiling_rcn_stock')->nullable();
        });    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('factory_cutting_boiling_mapping', 'cutting_boiling_mapping');
        Schema::rename('factory_cutting_stocks', 'cutting_stocks');
    }
}
