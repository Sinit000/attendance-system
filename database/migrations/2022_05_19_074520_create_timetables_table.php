<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimetablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timetables', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('timetable_name', 100);
            $table->time('on_duty_time');
            $table->time('off_duty_time');
            $table->string('late_minute', 100)->nullable();
            $table->string('early_leave', 100)->nullable();
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
        Schema::dropIfExists('timetables');
        Schema::dropIfExists('timetable_employees');
    }
}
