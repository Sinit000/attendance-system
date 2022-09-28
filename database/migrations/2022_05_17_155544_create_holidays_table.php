<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHolidaysTable extends Migration
{
    
    public function up()
    {
        Schema::create('holidays', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('name', 250  );
            $table->string('from_date',50);
            $table->string('to_date',50);
            $table->string('notes')->nullable();
            $table->string('duration',50)->nullable();
            $table->string('status',20)->nullable();
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
        Schema::dropIfExists('holidays');
    }
}
