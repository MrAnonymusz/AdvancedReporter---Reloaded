<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\CoreController;

class CreateUsersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('users', function(Blueprint $table)
    {
      $table->id();
      $table->string('username');
      $table->string('email');
      $table->string('password');
      $table->string('ip_address', 200)->nullable();
      $table->text('avatar');
      $table->string('role');
      $table->string('timezone');
      $table->tinyInteger('is_active');
      $table->rememberToken();
      $table->longText('permissions');
      $table->integer('created_at');
      $table->integer('updated_at')->nullable();
      $table->string('uuid');
    });

    $core = new CoreController;

    DB::table('users')->insert([
      'username' => 'Administrator',
      'email' => 'admin@example.com',
      'password' => $core->hash('root123'),
      'avatar' => json_encode([
        'type' => 'default'
      ]),
      'role' => 'admin',
      'timezone' => 'Europe/Bucharest',
      'is_active' => 1,
      'permissions' => '["superadmin"]',
      'created_at' => time(),
      'uuid' => '56289f0354061028e560e0'
    ]);
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

