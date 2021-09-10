<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateRolesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('roles', function(Blueprint $table)
    {
      $table->id();
      $table->string('display_name');
      $table->string('special_id');
      $table->tinyInteger('enabled');
      $table->tinyInteger('default');
      $table->string('css_class')->nullable();
      $table->longText('permissions');
      $table->string('created_by');
      $table->string('updated_by')->nullable();
      $table->integer('created_at');
      $table->integer('updated_at')->nullable();
    });

    DB::table('roles')->insert([
      [
        'display_name' => 'Admin',
        'special_id' => 'admin',
        'enabled' => 1,
        'default' => 0,
        'css_class' => 'role-admin',
        'permissions' => json_encode(['']),
        'created_by' => '56289f0354061028e560e0',
        'created_at' => time(),
      ],
      [
        'display_name' => 'Moderator',
        'special_id' => 'moderator',
        'enabled' => 1,
        'default' => 0,
        'css_class' => 'role-moderator',
        'permissions' => json_encode(['']),
        'created_by' => '56289f0354061028e560e0',
        'created_at' => time(),
      ],
      [
        'display_name' => 'Support',
        'special_id' => 'support',
        'enabled' => 1,
        'default' => 1,
        'css_class' => NULL,
        'permissions' => json_encode(['']),
        'created_by' => '56289f0354061028e560e0',
        'created_at' => time(),
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
    Schema::dropIfExists('roles');
  }
}