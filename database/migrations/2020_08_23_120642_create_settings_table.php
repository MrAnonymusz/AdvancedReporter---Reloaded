<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('settings', function(Blueprint $table)
    {
      $table->id();
      $table->string('title', 200);
      $table->string('special_id');
      $table->string('type');
      $table->text('description')->nullable();
      $table->longText('value');
      $table->integer('created_at');
      $table->integer('updated_at')->nullable();
    });

    DB::table('settings')->insert([
      [
        'title' => 'Site Name',
        'special_id' => 'site_name',
        'type' => 'text/small',
        'description' => NULL,
        'value' => 'AR - WebGUI',
        'created_at' => time()
      ],
      [
        'title' => 'Site Favicon',
        'special_id' => 'site_favicon',
        'type' => 'text/small',
        'description' => NULL,
        'value' => 'img/favicon.png',
        'created_at' => time()
      ],
      [
        'title' => 'Site Logo',
        'special_id' => 'site_logo',
        'type' => 'text/small',
        'description' => NULL,
        'value' => 'img/logo.png',
        'created_at' => time()
      ],
      [
        'title' => 'Site Logo Type',
        'special_id' => 'site_logo_type',
        'type' => 'text/small',
        'description' => NULL,
        'value' => 'image',
        'created_at' => time()
      ],
      [
        'title' => 'Site Meta Image',
        'special_id' => 'site_meta_image',
        'type' => 'text/small',
        'description' => NULL,
        'value' => 'img/meta.jpg',
        'created_at' => time()
      ],
      [
        'title' => 'Site Meta Description',
        'special_id' => 'site_meta_description',
        'type' => 'text/large',
        'description' => NULL,
        'value' => 'Vivamus ultricies hendrerit dolor quis sodales. Donec vitae varius ipsum. Aenean et eros et nisi tristique consectetur nec vitae ligula. Donec vestibulum ornare ipsum, id posuere risus consectetur eget.',
        'created_at' => time()
      ],
      [
        'title' => 'Site Timezone',
        'special_id' => 'site_timezone',
        'type' => 'text/small',
        'description' => NULL,
        'value' => 'UTC',
        'created_at' => time()
      ],
      [
        'title' => 'Site Time Format',
        'special_id' => 'site_time_format',
        'type' => 'text/small',
        'description' => NULL,
        'value' => 'Y/m/d H:i',
        'created_at' => time()
      ],
      [
        'title' => 'Default Avatar',
        'special_id' => 'default_avatar',
        'type' => 'text/small',
        'description' => NULL,
        'value' => 'img/default_avatar.jpg',
        'created_at' => time()
      ],
      [
        'title' => 'Allowed Avatar Size',
        'special_id' => 'allowed_avatar_size',
        'type' => 'text/small',
        'description' => NULL,
        'value' => '10240',
        'created_at' => time()
      ],
      [
        'title' => 'Allowed Avatar Types',
        'special_id' => 'allowed_avatar_types',
        'type' => 'list',
        'description' => NULL,
        'value' => json_encode(['jpeg', 'jpg', 'png', 'gif', 'bmp']),
        'created_at' => time()
      ],
      [
        'title' => 'Username Min Length',
        'special_id' => 'username_min_length',
        'type' => 'text/small',
        'description' => NULL,
        'value' => 3,
        'created_at' => time()
      ],
      [
        'title' => 'Username Max Length',
        'special_id' => 'username_max_length',
        'type' => 'text/small',
        'description' => NULL,
        'value' => 16,
        'created_at' => time()
      ],
      [
        'title' => 'Username Regex',
        'special_id' => 'username_regex',
        'type' => 'text/small',
        'description' => NULL,
        'value' => '/^[a-zA-Z0-9_]+$/',
        'created_at' => time()
      ],
      [
        'title' => 'Enable Registration',
        'special_id' => 'enable_registration',
        'type' => 'boolean',
        'description' => NULL,
        'value' => 0,
        'created_at' => time()
      ],
      [
        'title' => 'Enable Password Reset',
        'special_id' => 'enable_password_reset',
        'type' => 'boolean',
        'description' => NULL,
        'value' => 1,
        'created_at' => time()
      ],
      [
        'title' => 'IP Query Provider',
        'special_id' => 'ip_query_provider',
        'type' => 'text/small',
        'description' => NULL,
        'value' => 'http://ip-api.com/#',
        'created_at' => time()
      ],
      [
        'title' => 'Location Query Provider',
        'special_id' => 'location_query_provider',
        'type' => 'text/small',
        'description' => NULL,
        'value' => 'https://www.google.com/maps?q=',
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
    Schema::dropIfExists('settings');
  }
}