<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leaveouts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('date',50)->nullable();
            $table->string('reason')->nullable();
            // approve by cheif of department , status = approved
            // status == completed if staff back and security submit form to hr
           
            $table->time('time_out')->nullable();
            $table->time('time_in')->nullable();
            $table->string('note')->nullable();
            $table->string('approve_by',50)->nullable();
            $table->string('check_by',50)->nullable();
            $table->string('status',50);
            $table->string('duration',50);
            $table->string('type',50)->nullable();
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
        Schema::dropIfExists('leaveouts');
    }
}
