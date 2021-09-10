<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthLogsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('auth_logs', function(Blueprint $table)
    {
      $table->string('id')->unique();
      $table->string('user');
      $table->string('ip_address', 200);
      $table->tinyInteger('visible')->default('1');
      $table->text('payload')->nullable();
      $table->string('action');
      $table->integer('created_at');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('auth_logs');
  }
}