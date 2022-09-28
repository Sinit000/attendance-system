<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedTinyInteger('leave_type_id');
            // $table->unsignedTinyInteger('subtype_id')->nullable();
            $table->string('date',50);
            $table->string('status',50);
            $table->string('number',20)->nullable();
            // add new field type, when user request leave must choose type , hour, half day, or day
            // if day, have show from date, to date,
            $table->string('subtype',100)->nullable(); 
            $table->string('type',50)->nullable();
            $table->string('reason');
            $table->string('from_date',50)->nullable();
            $table->string('to_date',50)->nullable();
            $table->string('note')->nullable();
            $table->string('leave_deduction',100)->nullable();
            $table->string('image_url', 250)->nullable();
            $table->string('send_status',20)->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('leave_type_id')->references('id')->on('leavetypes')->onDelete('cascade');
            // $table->foreign('subtype_id')->nullable()->references('id')->on('subleavetypes')->onDelete('cascade');
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
        Schema::dropIfExists('leaves');
    }
}
