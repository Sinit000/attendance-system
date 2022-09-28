<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOvertimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('overtimes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('date',50);
            $table->string('reason');
            $table->string('from_date',50);
            $table->string('to_date',50);
            // $table->string('status',50);
            $table->string('number',20)->nullable();
            $table->string('send_status',20)->nullable();
            // for choosing hour or day
            $table->string('type',50)->nullable();
            // user accept overtime and pay type: have cash or holiday
            // requested_by
            $table->string('pay_status',50)->nullable();
            $table->string('pay_type',50)->nullable();
            $table->string('status',50);
            $table->string('ot_rate', 100 )->nullable();
            $table->string('ot_hour',100)->nullable();
            $table->string('ot_method',100)->nullable();
            $table->string('total_ot',100)->nullable();
            $table->string('notes')->nullable();
            $table->string('requested_by',50)->nullable();
            $table->string('approve_by',50)->nullable();
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
        Schema::dropIfExists('overtimes');
    }
}
