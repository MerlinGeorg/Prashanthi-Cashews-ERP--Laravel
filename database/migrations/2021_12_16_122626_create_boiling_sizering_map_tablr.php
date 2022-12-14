<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoilingSizeringMapTablr extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('boiling_stocks', function (Blueprint $table) {
            $table->unique('slug');
        });
        Schema::create('boiling_sizering_mapping', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('boiling_slug');
            $table->string('sizering_slug');
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
            $table->foreign('sizering_slug')->references('slug')->on('sizering_stocks');
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
        Schema::dropIfExists('boiling_sizering_mapping');
    }
}
