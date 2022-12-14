<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class RenameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('sizering_stocks', 'factory_sizering_stocks');
        Schema::rename('boiling_stocks', 'factory_boiling_stocks');
        Schema::rename('boiling_sizering_mapping', 'factory_sizering_boiling_mapping');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}