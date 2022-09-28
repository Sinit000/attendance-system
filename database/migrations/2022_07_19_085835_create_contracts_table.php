<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unsigned();
            $table->unsignedTinyInteger('structure_id')->unsigned();
            $table->string('start_date',50)->nullable();
            $table->string('end_date',50)->nullable();
            $table->string('working_schedule',50)->nullable();
            $table->string('status',20)->nullable();
            $table->string('ref_code',250)->nullable();
            // $table->unsignedTinyInteger('salalry_rule_id')->nullable();
            // $table->foreign('salalry_rule_id')->nullable()->references('id')->on('salaryrules')->onDelete('cascade');
            $table->foreign('structure_id')->references('id')->on('structures')->onDelete('cascade');
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
        Schema::dropIfExists('contracts');
    }
}
