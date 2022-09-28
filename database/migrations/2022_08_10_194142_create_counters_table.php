<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('counters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('ot_duration',20)->nullable();
            $table->string('total_ph',20)->nullable();
            $table->string('hospitality_leave',20)->nullable();
            $table->string('marriage_leave',20)->nullable();
            $table->string('peternity_leave',20)->nullable();
            $table->string('funeral_leave',20)->nullable();
            $table->string('maternity_leave',20)->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('counters');
    }
}
