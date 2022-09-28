<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('department_name', 100  );
            // $table->unsignedTinyInteger('workday_id')->default(1);
            // $table->unsignedTinyInteger('group_department_id')->default(1);
            // $table->unsignedTinyInteger('workday_id')->default(1);
            
            $table->unsignedTinyInteger('location_id')->default(1);
            $table->unsignedBigInteger('manager')->nullable();
            $table->string('notes')->nullable();
            // $table->foreign('group_department_id')->references('id')->on('group_departments')->onDelete('cascade');
            // $table->foreign('workday_id')->references('id')->on('workdays')->onDelete('cascade');
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
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
        Schema::dropIfExists('departments');
    }
}
