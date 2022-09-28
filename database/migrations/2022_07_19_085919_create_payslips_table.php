<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayslipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payslips', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unsigned();
            $table->string('wage_hour',50)->nullable();
            $table->string('net_perday',100)->nullable();
            $table->string('net_perhour',100)->nullable();
            $table->string('total_attendance',100)->nullable();
            $table->string('standance_attendance',100)->nullable();
            // $table->string('total_day',100)->nullable();
            // $table->unsignedBigInteger('contract_id')->unsigned();
            $table->string('from_date',50)->nullable();
            $table->string('to_date',50)->nullable();
            $table->string('base_salary',100);
            $table->string('allowance',100)->nullable();
            $table->string('bonus',100)->nullable();
           

            $table->string('total_leave',100)->nullable();
            $table->string('ot_hour',100)->nullable();
            $table->string('total_ot',100)->nullable();
            $table->string('advance_salary',100)->nullable();
           
            
            // $table->string('monthly',50);
            // $table->string('base_salary',100);
            $table->string('gross_salary',250)->nullable();
            // $table->string('allowance',100)->nullable();
            $table->string('notes',250)->nullable();
            
            // $table->string('senority_salary',100)->nullable();
            $table->string('tax_allowance',100)->nullable();
            $table->string('tax_salary',100)->nullable();
            $table->string('currency',100)->nullable();
            $table->string('exchange_rate',100)->nullable();
            $table->string('deduction',50)->nullable();
            $table->string('net_salary',50)->nullable(); 
            // $table->foreign('contract_id')->references('id')->on('contracts')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /** n  
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payslips');
    }
}
