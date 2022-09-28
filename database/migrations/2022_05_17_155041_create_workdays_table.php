<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkdaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workdays', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('name',100);
            $table->string('type_date_time',50)->nullable();
            $table->string('date_time',50)->nullable();
            $table->string('working_day', 20)->default('1,2,3,4,5,6');
            $table->string('off_day', 20)->nullable();
            $table->string('notes')->nullable();
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
        Schema::dropIfExists('workdays');
    }
}
