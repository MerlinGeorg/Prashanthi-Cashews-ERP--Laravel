<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BorrowedLotSlug extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stockyard_outward_rcns', function($table) {
            $table->string('borrowed_lot_slug', 50)->nullable();
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stockyard_outward_rcns', function($table) {
            $table->dropColumn('borrowed_lot_slug');
        });
    }
}
