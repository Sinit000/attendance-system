<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimetableEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('timetable_employees', function (Blueprint $table) {
        //     $table->tinyIncrements('id');
        //     $table->unsignedBigInteger('user_id')->unsigned();
        //     $table->unsignedTinyInteger('timetable_id')->unsigned();
        //     $table->timestamps();
        //     // $table->primary(array('employee_id', 'timetable_id'));
        //     $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        //     $table->foreign('timetable_id')->references('id')->on('timetables')->onDelete('cascade');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('timetable_employees');
    }
}
