<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChangedayoffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('changedayoffs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('type',100)->nullable();
            $table->unsignedTinyInteger('holiday_id')->nullable();
            $table->unsignedTinyInteger('workday_id')->nullable();
            // $table->unsignedTinyInteger('dayoff_type_id');
            $table->string('date',50)->nullable();
            $table->string('reason')->nullable();
            $table->string('from_date',50)->nullable();
            $table->string('to_date',50)->nullable();
            $table->string('duration',20)->nullable();
            $table->string('status',50)->nullable();
            $table->string('approve_by',50)->nullable();
            $table->foreign('holiday_id')->nullable()->references('id')->on('holidays')->onDelete('cascade');
            $table->foreign('workday_id')->nullable()->references('id')->on('workdays')->onDelete('cascade');
            // $table->foreign('dayoff_type_id')->references('id')->on('dayofftypes')->onDelete('cascade');
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
        Schema::dropIfExists('changedayoffs');
    }
}
