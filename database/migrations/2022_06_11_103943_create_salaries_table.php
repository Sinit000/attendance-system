<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('salaries', function (Blueprint $table) {
        //     $table->id();
        //     $table->unsignedBigInteger('user_id');
        //     $table->string('monthly',50);
        //     $table->string('base_salary',100);
        //     $table->string('salary_increment',100)->nullable();
        //     $table->string('salary_rate',100)->nullable();
        //     $table->string('gross_salary',250);
        //     $table->string('allowance',100)->nullable();
        //     $table->string('ot_rate',100)->nullable();
        //     $table->string('ot_hour',100)->nullable();
        //     $table->string('ot_method',100)->nullable();
        //     $table->string('total_ot',100)->nullable();
        //     // ket chea time if user accept in cash
        //     $table->string('bonus',100)->nullable();
        //     $table->string('advance_salary',100)->nullable();
        //     $table->string('senority_salary',100)->nullable();
        //     $table->string('tax_allowance',100)->nullable();
        //     $table->string('tax_salary',100)->nullable();
        //     $table->string('currency',100)->nullable();
        //     $table->string('exchange_rate',100)->nullable();

        //     $table->string('notes',100)->nullable();
        //     $table->string('deduction',50)->nullable();
        //     $table->string('net_salary',50)->nullable(); 
        //     $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salaries');
    }
}
