<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('gender');
            $table->string('religion')->nullable();
            $table->date('dob');
            $table->string('email')->unique();
            $table->string('qualification')->nullable();
            $table->string('experiences')->nullable();
            $table->string('employee_no')->nullable();
            $table->string('aadhar_no')->nullable();
            $table->string('nationality')->nullable();
            $table->string('identification_file')->nullable();
            $table->string('job_type');
            $table->string('user_group')->nullable();
            $table->string('work_location_type')->nullable();
            $table->integer('work_location_id')->nullable();
            $table->string('address_line_1');
            $table->string('address_line_2')->nullable();
            $table->string('city');
            $table->string('district');
            $table->string('state');
            $table->string('pincode');
            $table->string('mobile');
            $table->string('whatsapp');
            $table->date('join_date')->nullable();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('status')->default('pending');            
            $table->integer('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}