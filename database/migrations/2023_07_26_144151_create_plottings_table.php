<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    Schema::create('plottings', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('room_id')->nullable();
      $table->unsignedBigInteger('major_id')->nullable();
      $table->unsignedBigInteger('lesson_id')->nullable();
      $table->unsignedBigInteger('teacher_id')->nullable();
      $table->unsignedBigInteger('day_id')->nullable();
      $table->unsignedBigInteger('time_id')->nullable();
      $table->integer('total_sks');
      $table->timestamps();
      $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
      $table->foreign('major_id')->references('id')->on('majors')->onDelete('cascade');
      $table->foreign('lesson_id')->references('id')->on('lessons')->onDelete('cascade');
      $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
      $table->foreign('day_id')->references('id')->on('days')->onDelete('cascade');
      $table->foreign('time_id')->references('id')->on('times')->onDelete('cascade');
  });
  }
  
  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::dropIfExists('plottings');
  }
};
