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
    Schema::create('rooms', function (Blueprint $table) {
      $table->id();
      $table->string('code');
      $table->string('name');
      $table->unsignedBigInteger('major_id')->nullable();
      $table->timestamps(); 

      $table->foreign('major_id')->references('id')->on('majors')->onUpdate('cascade')->onDelete('set null');
  });
  }
  
  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::dropIfExists('rooms');
  }
};
