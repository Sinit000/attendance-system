<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeavetypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leavetypes', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('leave_type', 100  );
            $table->string('duration',20)->nullable();
            $table->unsignedTinyInteger('parent_id')->nullable();
            $table->string('notes')->nullable();
            // $table->foreign('leave_type_id')->nullable()->references('id')->on('leavetypes')->onDelete('cascade');
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
        Schema::dropIfExists('leavetypes');
    }
}
