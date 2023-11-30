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
    Schema::create('results', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('code_room_id')->nullable();
      $table->unsignedBigInteger('code_major_id')->nullable();
      $table->unsignedBigInteger('lesson_id')->nullable();
      $table->unsignedBigInteger('teacher_id')->nullable();
      $table->unsignedBigInteger('day_id')->nullable();
      $table->unsignedBigInteger('time_id')->nullable();
      $table->unsignedBigInteger('')->nullable();
      $table->timestamps();

      $table->foreign('code_room_id')->references('id')->on('rooms')->onDelete('cascade');
      $table->foreign('code_major_id')->references('major_id')->on('rooms')->onDelete('cascade');
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
    Schema::dropIfExists('results');
  }
};
