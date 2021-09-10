<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CoreController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Report;

class ReportResolveController extends Controller
{
  private $token;
  private $how_resolved;

  /*
    >> Resolve's a report
  */

  public function action(Request $request, $token)
  {
    $core = new CoreController;
    $this->user = Auth::user();

    if($core->hasPermission('can_take_report'))
    {
      $this->token = $token;

      $this->error = 0;

      if(!empty($this->token))
      {
        $this->get_report = Report::where('id', $this->token);

        if($this->get_report->count() == 1)
        {
          $this->report = $this->get_report->first();

          if($this->report->resolving == 1 && $this->report->open == 0)
          {
            $this->how_resolved = $request->how_resolved;

            $this->how_resolved_name = __('pages.reports.general.how-resolved');

            if(empty($this->how_resolved))
            {
              $this->error = 1;
              $this->error_how_resolved = __('validation.filled', ['attribute' => $this->how_resolved_name]);
            }
            else if(strlen($this->how_resolved) > 200)
            {
              $this->error = 1;
              $this->error_how_resolved = __('validation.lt.numeric', ['attribute' => $this->how_resolved_name, 'value' => 200]);
            }
            else
            {
              $this->error_how_resolved = "";
            }

            if($this->error != 1)
            {
              $this->get_report->update([
                'resolving' => 0,
                'open' => 0,
                'ticketManager' => $this->user->username,
                'howResolved' => $this->how_resolved
              ]);

              return response()->json([
                'error' => 0,
                'message' => __('sentences.report-successfully-resolved')
              ]);
            }
            else
            {
              return response()->json([
                'error' => 1,
                'error_how_resolved' => $this->error_how_resolved
              ]);
            }
          }
          else
          {
            return abort(403);
          }
        }
        else
        {
          return response()->json([
            'error' => 2,
            'message' => __('validation.in', ['attribute' => __('words.id')])
          ]);
        }
      }
      else
      {
        return response()->json([
          'error' => 2,
          'message' => __('validation.filled', ['attribute' => __('words.id')])
        ]);
      }
    }
    else
    {
      return abort(403);
    }
  }
}
