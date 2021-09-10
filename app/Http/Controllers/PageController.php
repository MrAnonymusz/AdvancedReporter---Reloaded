<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Report;
use App\User;
use App\Role;

class PageController extends Controller
{
  private $token;

  /**
   * Account Deactivated Page
   * 
   */

  public function account_deactivated_page()
  {
    $core = new CoreController;

    return view('user.deactivated')->with(['core' => $core, 'page' => 'user-deactivated']);
  }

  /*
    >> Auth Pages
     >>> Login Page
  */

  public function auth_login()
  {
    $core = new CoreController;

    return view('auth.login')->with(['core' => $core, 'page' => 'auth-login']);
  }

  /*
    >>> Register Page
  */

  public function auth_register()
  {
    $core = new CoreController;

    if($core->setting('enable_registration') == 1)
    {
      return view('auth.register')->with(['core' => $core, 'page' => 'auth-register']);
    }
    else
    {
      return redirect('auth/login');
    }
  }

  /*
    >> Pages
  */

  // Home
  public function home(Request $request)
  {
    $core = new CoreController;
    $this->user = Auth::user();

    if(!empty($request->show_all_auth_logs) && $request->show_all_auth_logs == "true")
    {
      $this->auth_logs = DB::table('auth_logs')->where([['visible', '=', 1], ['user', '=', $this->user->uuid]])->orderBy('created_at', 'desc')->get();
    }
    else
    {
      $this->auth_logs = DB::table('auth_logs')->where([['visible', '=', 1], ['user', '=', $this->user->uuid]])->offset(0)->limit(20)->orderBy('created_at', 'desc')->get();
    }

    return view('pages.home')->with(['core' => $core, 'user' => $this->user, 'auth_logs' => $this->auth_logs, 'page' => 'home']);
  }

  // Report List
  public function report_list()
  {
    $core = new CoreController;
    $this->user = Auth::user();

    $this->reports = Report::orderBy('id', 'desc')->paginate(20);

    return view('reports.list')->with(['core' => $core, 'user' => $this->user, 'reports' => $this->reports, 'page' => 'report-list']);
  }

  // Report List (My Reports)
  public function report_list_my_reports()
  {
    $core = new CoreController;
    $this->user = Auth::user();

    $this->reports = Report::where('ticketManager', $this->user->username)->orderBy('id', 'desc')->paginate(20);

    return view('reports.list')->with(['core' => $core, 'user' => $this->user, 'reports' => $this->reports, 'page' => 'report-list-my-reports']);
  }

  // Report Create
  public function report_create()
  {
    $core = new CoreController;
    $this->user = Auth::user();

    if($core->hasPermission('can_create_report'))
    {
      return view('reports.create')->with(['core' => $core, 'user' => $this->user, 'page' => 'report-create']);
    }
    else
    {
      return abort(403);
    }
  }
  
  // Report Resolve
  public function report_resolve($token)
  {
    $core = new CoreController;
    $this->user = Auth::user();

    $this->token = $token;

    $this->error = 0;

    if(empty($this->token))
    {
      $this->error = 1;
    }
    else
    {
      $this->get_report = Report::where('id', $this->token);

      if($this->get_report->count() != 1)
      {
        $this->error = 1;
      }
    }

    if($this->error != 1)
    {
      $this->report = $this->get_report->first();

      if($core->hasPermission('can_take_report') && $this->report->ticketManager == $this->user->username)
      {
        if($this->report->resolving == 1 && $this->report->open == 0)
        {
          return view('reports.resolve')->with(['core' => $core, 'user' => $this->user, 'report' => $this->report, 'page' => 'report-resolve']);
        }
        else
        {
          return abort(403);
        }
      }
      else
      {
        return abort(403);
      }
    }
    else
    {
      return abort(500);
    }
  }

  // Edit Report
  public function report_edit($token)
  {
    $core = new CoreController;
    $this->user = Auth::user();

    $this->error = 0;

    if($core->hasPermission('can_edit_report'))
    {
      $this->token = $token;

      if(empty($this->token))
      {
        $this->error = 1;
      }
      else
      {
        $this->get_report = Report::where('id', $this->token);

        if($this->get_report->count() != 1)
        {
          $this->error = 1;
        }
      }

      if($this->error != 1)
      {
        $this->report = $this->get_report->first();

        return view('reports.edit')->with(['core' => $core, 'user' => $this->user, 'report' => $this->report, 'page' => 'report-edit']);
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

  /*
    >> User Pages
    >>> Account (Settings)
  */

  public function user_account_settings()
  {
    $core = new CoreController;
    $this->user = Auth::user();

    return view('user.account')->with(['core' => $core, 'user' => $this->user, 'page' => 'user-account-settings']);
  }

  /*
    >> Admin Pages
    >>> Site Settings
  */

  public function admin_site_settings()
  {
    $core = new CoreController;
    $this->user = Auth::user();

    if($core->hasPermission('can_update_site_settings'))
    {
      return view('admin.site-settings')->with(['core' => $core, 'user' => $this->user, 'page' => 'admin-site-settings']);
    }
    else
    {
      return abort(403);
    }
  }

  /*
    >>> User List
  */

  public function admin_user_list()
  {
    $core = new CoreController;
    $this->user = Auth::user();

    if($core->hasPermission('can_see_users'))
    {
      $this->user_list = User::where('uuid', '!=', $this->user->uuid)->orderBy('created_at', 'desc')->paginate(10);

      return view('admin.user-list')->with(['core' => $core, 'user' => $this->user, 'user_list' => $this->user_list, 'page' => 'admin-user-list']);
    }
    else
    {
      return abort(403);
    }
  }

  /*
    >>> User Create
  */

  public function admin_user_create()
  {
    $core = new CoreController;
    $this->user = Auth::user();

    if($core->hasPermission('can_create_user'))
    {
      return view('admin.user-create')->with(['core' => $core, 'user' => $this->user, 'page' => 'admin-user-create']);
    }
    else
    {
      return abort(403);
    }
  }

  /*
    >>> User Edit
  */

  public function admin_user_edit($token)
  {
    $core = new CoreController;
    $this->user = Auth::user();

    $this->token = $token;

    if($core->hasPermission('can_edit_user'))
    {
      $this->get_user = User::where([['uuid', '=', $this->token], ['uuid', '!=', $this->user->uuid]]);

      if($this->get_user->count() == 1)
      {
        return view('admin.user-edit')->with(['core' => $core, 'euser' => $this->get_user->first(), 'user' => $this->user, 'page' => 'admin-user-edit']);
      }
      else
      {
        return abort(403);
      }
    }
    else
    {
      return abort(403);
    }
  }

  /*
    >>> Role List
  */

  public function admin_role_list()
  {
    $core = new CoreController;
    $this->user = Auth::user();

    if($core->hasPermission('can_see_roles'))
    {
      $this->role_list = Role::orderBy('id', 'desc')->paginate(10);

      return view('admin.role-list')->with(['core' => $core, 'role_list' => $this->role_list, 'user' => $this->user, 'page' => 'admin-role-list']);
    }
    else
    {
      return abort(403);
    }
  }

  /*
    >>> Role Create
  */

  public function admin_role_create()
  {
    $core = new CoreController;
    $this->user = Auth::user();

    if($core->hasPermission('can_create_role'))
    {
      return view('admin.role-create')->with(['core' => $core, 'user' => $this->user, 'page' => 'admin-role-create']);
    }
    else
    {
      return abort(403);
    }
  }

  /*
    >>> Role Edit
  */

  public function admin_role_edit($token)
  {
    $core = new CoreController;
    $this->user = Auth::user();

    $this->token = $token;

    if($core->hasPermission('can_edit_role'))
    {
      $this->get_role = Role::where('special_id', $this->token);

      if($this->get_role->count() == 1)
      {
        return view('admin.role-edit')->with(['core' => $core, 'role' => $this->get_role->first(), 'user' => $this->user, 'page' => 'admin-user-edit']);
      }
      else
      {
        return abort(403);
      }
    }
    else
    {
      return abort(403);
    }
  }
}
