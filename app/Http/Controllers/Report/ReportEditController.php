<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CoreController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Report;

class ReportEditController extends Controller
{
  private $token;
  private $reported;
  private $reporter;
  private $world;
  private $x;
  private $y;
  private $z;
  private $section;
  private $sub_section;
  private $resolving;
  private $open;
  private $ticket_manager;
  private $server_name;
  private $reason;
  private $how_resolved;

  // Edit's a report
  public function action(Request $request, $token)
  {
    $core = new CoreController;
    $this->user = Auth::user();

    if($core->hasPermission('can_edit_report'))
    {
      $this->token = $token;

      $this->error = 0;

      if(!empty($this->token))
      {
        $this->get_report = Report::where('id', $this->token);

        if($this->get_report->count() == 1)
        {
          $this->reported = $request->reported;
          $this->reporter = $request->reporter;
          $this->world = $request->world;
          $this->x = $request->x;
          $this->y = $request->y;
          $this->z = $request->z;
          $this->section = $request->section;
          $this->sub_section = $request->sub_section;
          $this->resolving = $request->resolving;
          $this->open = $request->open;
          $this->ticket_manager = $request->ticket_manager;
          $this->server_name = $request->server_name;
          $this->reason = $request->reason;
          $this->how_resolved = $request->how_resolved;

          $this->reported_name = mb_strtolower(__('pages.reports.general.reported'));
          $this->reporter_name = mb_strtolower(__('pages.reports.general.reporter'));
          $this->reason_name = mb_strtolower(__('pages.reports.general.reason'));
          $this->coordinates_name = mb_strtolower(__('words.coordinate.pl'));
          $this->section_name = mb_strtolower(__('pages.reports.general.section'));
          $this->sub_section_name = mb_strtolower(__('pages.reports.general.sub-section'));
          $this->resolving_name = mb_strtolower(__('words.resolving'));
          $this->open_name = mb_strtolower(__('words.open'));
          $this->ticket_manager_name = mb_strtolower(__('pages.reports.general.ticket-manager'));
          $this->server_name_name = mb_strtolower(__('pages.reports.general.server-name'));
          $this->how_resolved_name = mb_strtolower(__('pages.reports.general.how-resolved'));

          // Reported
          if(empty($this->reported))
          {
            $this->error = 1;
            $this->error_reported = __('validation.filled', ['attribute' => $this->reported_name]);
          }
          else if(strlen($this->reported) > 50)
          {
            $this->error = 1;
            $this->error_reported = __('validation.lt.numeric', ['attribute' => $this->reported_name]);
          }
          else
          {
            $this->error_reported = "";
          }

          // Reporter
          if(empty($this->reporter))
          {
            $this->error = 1;
            $this->error_reporter = __('validation.filled', ['attribute' => $this->reporter_name]);
          }
          else if(strlen($this->reporter) > 50)
          {
            $this->error = 1;
            $this->error_reporter = __('validation.lt.numeric', ['attribute' => $this->reporter_name]);
          }
          else
          {
            $this->error_reporter = "";
          }

          // Reason
          if(empty($this->reason))
          {
            $this->error = 1;
            $this->error_reason = __('validation.filled', ['attribute' => $this->reason_name]);
          }
          else if(strlen($this->reason) > 200)
          {
            $this->error = 1;
            $this->error_reason = __('validation.lt.numeric', ['attribute' => $this->reason_name]);
          }
          else
          {
            $this->error_reason = "";
          }

          // Coordinates (X, Y, Z)
          if(empty($this->x) || empty($this->y) || empty($this->z))
          {
            $this->error = 1;
            $this->error_coordinates = __('validation.filled', ['attribute' => $this->coordinates_name]);
          }
          else if(!filter_var($this->x, FILTER_VALIDATE_INT) || !filter_var($this->y, FILTER_VALIDATE_INT) || !filter_var($this->z, FILTER_VALIDATE_INT))
          {
            $this->error = 1;
            $this->error_coordinates = __('validation.in', ['attribute' => $this->coordinates_name]);
          }
          else
          {
            $this->error_coordinates = "";
          }

          // Section
          if(empty($this->section))
          {
            $this->error = 1;
            $this->error_section = __('validation.filled', ['attribute' => $this->section_name]);
          }
          else if(strlen($this->section) > 50)
          {
            $this->error = 1;
            $this->error_section = __('validation.lt.numeric', ['attribute' => $this->section_name]);
          }
          else
          {
            $this->error_section = "";
          }

          // Sub-Section
          if(empty($this->sub_section))
          {
            $this->error = 1;
            $this->error_sub_section = __('validation.filled', ['attribute' => $this->sub_section_name]);
          }
          else if(strlen($this->sub_section) > 50)
          {
            $this->error = 1;
            $this->error_sub_section = __('validation.lt.numeric', ['attribute' => $this->sub_section_name]);
          }
          else
          {
            $this->error_sub_section = "";
          }

          // Resolving
          if(!in_array($this->resolving, ['false', 'true']))
          {
            $this->error = 1;
            $this->error_resolving = __('validation.in', ['attribute' => $this->resolving_name]);
          }
          else
          {
            $this->error_resolving = "";
          }

          // Open
          if(!in_array($this->open, ['false', 'true']))
          {
            $this->error = 1;
            $this->error_open = __('validation.in', ['attribute' => $this->open_name]);
          }
          else
          {
            $this->error_open = "";
          }

          // Ticket Manager
          if(empty($this->ticket_manager))
          {
            $this->ticket_manager = "none";
          }
          else if(strlen($this->ticket_manager) > 50)
          {
            $this->error = 1;
            $this->error_ticket_manager = __('validation.lt.numeric', ['attribute' => $this->ticket_manager_name, 'value' => 50]);
          }
          else
          {
            $this->error_ticket_manager = "";
          }

          // How Resolved
          if(empty($this->how_resolved))
          {
            $this->how_resolved = "none";
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

          // Server Name
          if(empty($this->server_name))
          {
            $this->error = 1;
            $this->error_server_name = __('validation.filled', ['attribute' => $this->server_name_name]);
          }
          else if(strlen($this->server_name) > 50)
          {
            $this->error = 1;
            $this->error_server_name = __('validation.lt.numeric', ['attribute' => $this->server_name_name, 'value' => 50]);
          }
          else
          {
            $this->error_server_name = "";
          }

          if($this->error != 1)
          {
            if($this->resolving == "true")
            {
              $this->f_resolving = 1;
            }
            else
            {
              $this->f_resolving = 0;
            }

            if($this->open == "true")
            {
              $this->f_open = 1;
            }
            else
            {
              $this->f_open = 0;
            }

            $this->get_report->update([
              'reported' => $this->reported,
              'reporter' => $this->reporter,
              'reason' => $this->reason,
              'world' => $this->world,
              'x' => $this->x,
              'y' => $this->y,
              'z' => $this->z,
              'section' => $this->section,
              'subSection' => $this->sub_section,
              'resolving' => $this->f_resolving,
              'open' => $this->f_open,
              'ticketManager' => $this->ticket_manager,
              'howResolved' => $this->how_resolved,
              'serverName' => $this->server_name
            ]); 

            return response()->json([
              'error' => 0,
              'message' => __('sentences.report-successfully-edited')
            ]);
          }
          else
          {
            return response()->json([
              'error' => 1,
              'error_reported' => $this->error_reported,
              'error_reporter' => $this->error_reporter,
              'error_reason' => $this->error_reason,
              'error_coordinates' => $this->error_coordinates,
              'error_section' => $this->error_section,
              'error_sub_section' => $this->error_sub_section,
              'error_resolving' => $this->error_resolving,
              'error_open' => $this->error_open,
              'error_ticket_manager' => $this->error_ticket_manager,
              'error_how_resolved' => $this->error_how_resolved,
              'error_server_name' => $this->error_server_name
            ]);
          }
        }
        else
        {
          return abort(404);
        }
      }
      else
      {
        return abort(400);
      }
    }
    else
    {
      return abort(403);
    }
  }
}
