<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBormaStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('borma_stocks', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('borma_work_number');
            $table->date('borma_work_date_time');
            $table->string('factory_slug');
            $table->string('wholes');
            $table->string('brokens');
            $table->string('piruwal');
            $table->string('balance_wholes')->nullable();
            $table->string('balance_brokens')->nullable();
            $table->string('balance_piruwal')->nullable();
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
        Schema::dropIfExists('borma_stocks');
    }
}
