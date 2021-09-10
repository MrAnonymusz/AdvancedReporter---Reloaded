<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CoreController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Report;

class ReportTakeController extends Controller
{
  private $token;

  /*
    >> Assigns a 'ticketManager' to a report
  */

  public function action($token)
  {
    $core = new CoreController;

    $this->error = 0;

    if($core->hasPermission('can_take_report'))
    {
      $this->user = Auth::user();

      $this->token = $token;

      $this->token_name = mb_strtolower(__('words.id'));

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
          $this->report = $this->get_report->first();

          if($this->report->resolving != 0 && $this->report->ticketManager != "none")
          {
            $this->error = 1;
            $this->error_message =  __('validation.custom.already-has-ticket-manager');
          }
          else
          {
            $this->error_message = "";
          }
        }
      }

      if($this->error != 1)
      {
        $this->get_report->update([
          'resolving' => 1,
          'open' => 0,
          'ticketManager' => $this->user->username
        ]);

        return response()->json([
          'error' => 0
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
