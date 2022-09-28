<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDayofftypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dayofftypes', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->unsignedTinyInteger('holiday_id')->nullable();
            $table->string('name', 100 );
            $table->foreign('holiday_id')->references('id')->on('holidays')->onDelete('cascade');
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
        Schema::dropIfExists('dayofftypes');
    }
}
