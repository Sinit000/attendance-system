<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOvertimecompesationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('overtimecompesations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('type',50)->nullable();
            $table->string('reason')->nullable();
            $table->string('from_date',50)->nullable();
            $table->string('to_date',50)->nullable();
            $table->string('duration',20)->nullable();
            $table->string('note')->nullable();
            $table->string('approved_by',50)->nullable();
            $table->string('date',50)->nullable();
            $table->string('status',50)->nullable();
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
        Schema::dropIfExists('overtimecompesations');
    }
}
