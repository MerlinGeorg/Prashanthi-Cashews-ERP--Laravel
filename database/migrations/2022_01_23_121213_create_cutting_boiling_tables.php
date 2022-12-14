<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCuttingBoilingTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cutting_stocks', function (Blueprint $table) {
            $table->unique('slug');
        });
        Schema::create('cutting_boiling_mapping', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('boiling_slug');
            $table->string('cutting_slug');
            $table->float('aplus')->nullable();
            $table->float('a1')->nullable();
            $table->float('a2')->nullable();
            $table->float('b1')->nullable();
            $table->float('b2')->nullable();
            $table->float('c1')->nullable();
            $table->float('c2')->nullable();
            $table->float('d1')->nullable();
            $table->float('d2')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('cutting_slug')->references('slug')->on('cutting_stocks');
            $table->foreign('boiling_slug')->references('slug')->on('boiling_stocks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cutting_boiling_mapping');
    }
}
