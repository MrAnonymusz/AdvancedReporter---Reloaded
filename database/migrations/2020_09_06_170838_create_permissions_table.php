<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('permissions', function (Blueprint $table)
    {
      $table->id();
      $table->text('name');
      $table->text('value')->nullable();
    });

    DB::table('permissions')->insert([
      [
        'name' => 'can_update_avatar'
      ],
      [
        'name' => 'can_create_report'
      ],
      [
        'name' => 'can_take_report'
      ],
      [
        'name' => 'can_edit_report'
      ],
      [
        'name' => 'can_remove_report'
      ],
      [
        'name' => 'can_see_users'
      ],
      [
        'name' => 'can_create_user'
      ],
      [
        'name' => 'can_edit_user'
      ],
      [
        'name' => 'can_remove_user'
      ],
      [
        'name' => 'can_see_roles'
      ],
      [
        'name' => 'can_edit_role'
      ],
      [
        'name' => 'can_create_role'
      ],
      [
        'name' => 'can_remove_role'
      ],
      [
        'name' => 'can_update_site_settings'
      ],
      [
        'name' => 'superadmin'
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
    Schema::dropIfExists('permissions');
  }
}