<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('no', 50)->nullable();
            $table->string('status', 20)->nullable();
            $table->string('status_leave', 20)->nullable();
            // $table->string('check_date', 50)->nullable();
            $table->string('name', 100);
            $table->string('gender', 20);
            $table->string('nationality', 100)->nullable();
            $table->string('dob', 50)->nullable();
            $table->string('office_tel', 20)->nullable();
            $table->string('card_number', 50)->nullable();
            $table->string('employee_phone', 30)->unique()->nullable();
            $table->string('email', 50)->unique()->nullable();
            $table->string('profile_url', 250)->nullable();
            $table->string('address', 250)->nullable();
            $table->string('username', 50)->unique();
            $table->string('password', 60);
            $table->unsignedTinyInteger('role_id');
            $table->string('note', 100)->nullable();
            $table->string('is_manager', 50)->nullable();
            $table->unsignedTinyInteger('position_id');
            $table->unsignedTinyInteger('department_id');
            $table->unsignedTinyInteger('timetable_id');
            $table->unsignedTinyInteger('workday_id');
            $table->string('merital_status', 50)->nullable();
            $table->string('spouse_job', 50)->nullable();
            $table->string('minor_children', 20)->nullable();
            
            $table->timestamp('password_changed_at')->nullable();
            $table->rememberToken();
            $table->string('session_id', 40)->nullable();
            $table->string('device_token', 250)->nullable();
            $table->timestamps();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('position_id')->references('id')->on('positions')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->foreign('timetable_id')->references('id')->on('timetables')->onDelete('cascade');
            $table->foreign('workday_id')->references('id')->on('workdays')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
