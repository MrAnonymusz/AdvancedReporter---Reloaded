<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CoreController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Report;

class ReportRemoveController extends Controller
{
  private $token;

  /*
    >> Remove's a report
  */

  public function action($token)
  {
    $core = new CoreController;

    if($core->hasPermission('can_remove_report'))
    {
      $this->user = Auth::user();

      $this->token = $token;

      $this->token_name = __('words.id');

      $this->error = 0;

      if(empty($this->token))
      {
        $this->error = 1;
        $this->error_message = __('validation.filled', ['attribute' => $this->token_name]);
      }
      else
      {
        $this->get_report = Report::where('id', $this->token);

        if($this->get_report->count() != 1)
        {
          $this->error = 1;
          $this->error_message = __('validation.in', ['attribute' => $this->token_name]);
        }
        else
        {
          $this->error_message = "";
        }
      }

      if($this->error != 1)
      {
        $this->get_report->delete();

        return response()->json([
          'error' => 0,
          'message' => __('sentences.report-successfully-removed')
        ]);
      }
      else
      {
        return response()->json([
          'error' => 2,
          'message' => $this->error_message
        ]);
      }
    }
    else
    {
      return abort(403);
    }
  }
}
