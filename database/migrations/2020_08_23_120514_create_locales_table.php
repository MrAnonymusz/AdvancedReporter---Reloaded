<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocalesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('locales', function (Blueprint $table)
    {
      $table->id();
      $table->string('display_name', 200);
      $table->string('special_id');
      $table->string('code', 3);
      $table->text('flag');
      $table->tinyInteger('enabled');
      $table->tinyInteger('default');
      $table->string('added_by');
      $table->integer('created_at');
    });

    DB::table('locales')->insert([
      [
        'display_name' => 'English',
        'special_id' => 'english',
        'code' => 'en',
        'flag' => '{site_url}/assets/main/img/en_flag.jpg',
        'enabled' => 1,
        'default' => 1,
        'added_by' => '56289f0354061028e560e0',
        'created_at' => time()
      ]
    ]);
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('locales');
  }
}