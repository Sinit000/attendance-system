<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStructuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('structures', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('name', 100 )->nullable();
            $table->string('base_salary', 100 )->nullable();
            // $table->string('bonus',100)->nullable();
            $table->string('allowance',100)->nullable();
            // $table->string('currency',100)->nullable();
            // $table->string('advance_salary',100)->nullable();
            // $table->string('senority_salary',100)->nullable();
            // $table->unsignedTinyInteger('structure_type_id')->unsigned();
            // $table->unsignedTinyInteger('salalry_rule_id')->nullable();
            // $table->foreign('salalry_rule_id')->nullable()->references('id')->on('salaryrules')->onDelete('cascade');
            // $table->foreign('structure_type_id')->references('id')->on('structuretypes')->onDelete('cascade');
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
        Schema::dropIfExists('structures');
    }
}
