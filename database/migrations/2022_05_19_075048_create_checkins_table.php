<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checkins', function (Blueprint $table) {
            $table->id();
            $table->string('checkin_time',10)->nullable();
            $table->string('checkout_time',10)->nullable();
            $table->string('checkin_late',50)->nullable();
            $table->string('checkout_late',50)->nullable();

            // $table->string('checkout_late',50)->nullable();
            $table->string('checkin_status',50)->nullable();
            $table->string('checkout_status',50)->nullable();
            // $table->string('checkout_status',50)->nullable();
            $table->string('date',50);
            $table->string('send_status',20)->nullable();
            // for account confirm 
            $table->string('confirm',20)->nullable();
            $table->string('ot_status',20)->nullable();

            $table->unsignedBigInteger('user_id');
            // use when employee ask permission for half day, this field will have status half day
            // and employee can only check out
            $table->string('leave_status',50)->nullable();
            $table->string('status',50)->nullable();
            $table->string('note',100)->nullable();
            $table->string('duration',50)->nullable();
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
        Schema::dropIfExists('checkins');
    }
}
