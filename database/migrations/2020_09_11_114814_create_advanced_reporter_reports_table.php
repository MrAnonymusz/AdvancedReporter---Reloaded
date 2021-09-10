<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvancedReporterReportsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    if(!Schema::hasTable('advanced_reporter_reports'))
    {
      Schema::create('advanced_reporter_reports', function(Blueprint $table)
      {
        $table->integer('id')->primary();
        $table->string('reported', 50);
        $table->string('reporter', 50);
        $table->string('reason', 200);
        $table->string('world', 50);
        $table->double('x');
        $table->double('y');
        $table->double('z');
        $table->string('section', 50);
        $table->string('subSection', 50);
        $table->tinyInteger('resolving');
        $table->tinyInteger('open');
        $table->string('ticketManager', 50);
        $table->string('howResolved', 200);
        $table->string('serverName', 50);
      });

      for($i = 1; $i < 6; $i++)
      {
        DB::table('advanced_reporter_reports')->insert([
          'id' => $i,
          'reported' => 'Administrator',
          'reporter' => 'MrAnonymusz',
          'reason' => 'Suspendisse tincidunt mauris sed pharetra convallis. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; In vulputate libero eget odio sodales.',
          'world' => 'world',
          'x' => '-2345.9380506930006',
          'y' => '73',
          'z' => '-2253.7573065453494',
          'section' => 'stafferreport',
          'subSection' => 'give',
          'resolving' => 0,
          'open' => 1,
          'ticketManager' => 'none',
          'howResolved' => 'none',
          'serverName' => 'lobby'
        ]);
      }
    }
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    // Schema::dropIfExists('advanced_reporter_reports');
  }
}