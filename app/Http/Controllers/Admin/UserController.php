<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CoreController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\User;
use App\Role;
use App\Permission;

class UserController extends Controller
{
  private $username;
  private $email;
  private $password;
  private $role;
  private $timezone;
  private $permissions;
  private $token;

  /**
   * Create a user
   * 
   * @param \Request $request
   * 
   * @return string
   */

  public function create_user(Request $request)
  {
    $core = new CoreController;

    if($core->hasPermission('can_create_user') == 1)
    {
      $this->username = $request->username;
      $this->email = $request->email;
      $this->password = $request->password;
      $this->role = $request->role;
      $this->timezone = $request->timezone;
      $this->permissions = $request->permissions;

      $this->username_name = mb_strtolower(__('words.username'));
      $this->email_name = mb_strtolower(__('words.email'));
      $this->password_name = mb_strtolower(__('words.password'));
      $this->role_name = mb_strtolower(__('words.role'));
      $this->timezone_name = mb_strtolower(__('words.timezone'));
      $this->permissions_name = mb_strtolower(__('words.permission.pl'));

      $this->user_password = $request->user_password;
      $this->user_password_name = $this->password_name;

      $this->error = 0;

      if(empty($this->username))
      {
        $this->error = 1;
        $this->error_username = __('validation.filled', ['attribute' => $this->username_name]);
      }
      else if(strlen($this->username) < $core->setting('username_min_length'))
      {
        $this->error = 1;
        $this->error_username = __('validation.gt.numeric', ['attribute' => $this->username_name, 'value' => $core->setting('username_min_length')]);
      }
      else if(strlen($this->username) > $core->setting('username_max_length'))
      {
        $this->error = 1;
        $this->error_username = __('validation.lt.numeric', ['attribute' => $this->username_name, 'value' => $core->setting('username_max_length')]);
      }
      else if(!preg_match($core->setting('username_regex'), $this->username))
      {
        $this->error = 1;
        $this->error_username = __('validation.not_regex', ['attribute' => $this->username_name]);
      }
      else
      {
        $this->check_username = User::where('username', $this->username)->count();

        if($this->check_username != 0)
        {
          $this->error = 1;
          $this->error_username = __('validation.unique', ['attribute' => $this->username_name]);
        }
        else
        {
          $this->error_username = "";
        }
      }

      if(empty($this->email))
      {
        $this->error = 1;
        $this->error_email = __('validation.filled', ['attribute' => $this->email_name]);
      }
      else if(!filter_var($this->email, FILTER_VALIDATE_EMAIL))
      {
        $this->error = 1;
        $this->error_email = __('validation.email', ['attribute' => '"'.$this->email_name.'"']);
      }
      else if(strlen($this->email) > 128)
      {
        $this->error = 1;
        $this->error_email = __('validation.lt.numeric', ['attribute' => $this->email_name, 'value' => 128]);
      }
      else
      {
        $this->check_email = User::where('email', $this->email)->count();

        if($this->check_email != 0)
        {
          $this->error = 1;
          $this->error_email = __('validation.unique', ['attribute' => $this->email_name]);
        }
        else
        {
          $this->error_email = "";
        }
      }

      if(empty($this->password))
      {
        $this->error = 1;
        $this->error_password = __('validation.filled', ['attribute' => $this->password_name]);
      }
      else if(strlen($this->password) < 6)
      {
        $this->error = 1;
        $this->error_password = __('validation.gt.numeric', ['attribute' => $this->password_name]);
      }
      else if(!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]+$/', $this->password))
      {
        $this->error = 1;
        $this->error_password = __('validation.not_regex', ['attribute' => $this->password_name]);
      }
      else
      {
        $this->error_password = "";
      }

      if(empty($this->role))
      {
        $this->error = 1;
        $this->error_role = __('validation.filled', ['attribute' => $this->role_name]);
      }
      else
      {
        $this->role_list = [];

        foreach(Role::all() as $item)
        {
          $this->role_list[] = $item->special_id;
        }

        if(!in_array($this->role, $this->role_list))
        {
          $this->error = 1;
          $this->error_role = __('validation.in', ['attribute' => $this->role_name]);
        }
        else
        {
          $this->error_role = "";
        }
      }

      if(empty($this->timezone))
      {
        $this->error = 1;
        $this->error_timezone = __('validation.filled', ['attribute' => $this->timezone_name]);
      }
      else
      {
        $this->timezone_list = json_decode(Storage::disk('private')->get('timezones.json'));

        if(!in_array($this->timezone, $this->timezone_list))
        {
          $this->error = 1;
          $this->error_timezone = __('validation.in', ['attribute' => $this->timezone_name]);
        }
        else
        {
          $this->error_timezone = "";
        }
      }

      if(!empty($this->permissions))
      {
        $this->permission_list = json_decode(Permission::all());

        foreach($this->permission_list as $item)
        {
          if(!in_array($item, $this->permission_list))
          {
            $this->error = 1;
            $this->error_permissions = __('validation.in', ['attribute' => $this->permissions_name]);
          }
          else
          {
            $this->error_permissions = "";
          }
        }
      }
      else
      {
        $this->error_permissions = "";
      }

      if($this->error != 1)
      {
        if($core->verifyPassword($this->user_password, Auth::user()->password))
        {
          User::insert([
            'username' => $this->username,
            'email' => $this->email,
            'password' => $core->hash($this->password),
            'avatar' => json_encode(['type' => 'default']),
            'role' => $this->role,
            'timezone' => $this->timezone,
            'is_active' => 1,
            'permissions' => json_encode(!empty($this->permissions) ? $this->permissions : ['']),
            'created_at' => time(),
            'uuid' => $core->generateID()
          ]);

          return response()->json([
            'error' => 0,
            'message' => __('sentences.user-created')
          ]);
        }
        else
        {
          return response()->json([
            'error' => 2,
            'message' => __('validation.in', ['attribute' => $this->user_password_name])
          ]);
        }
      }
      else
      {
        return response()->json([
          'error' => 1,
          'error_username' => $this->error_username,
          'error_email' => $this->error_email,
          'error_password' => $this->error_password,
          'error_role' => $this->error_role,
          'error_timezone' => $this->error_timezone,
          'error_permissions' => $this->error_permissions
        ]);
      }
    }
    else
    {
      return response()->json([
        'error' => 2,
        'message' => __('sentences.no-perm')
      ]);
    }
  }

  /**
   * Update a user account
   * 
   * @param \Request $request
   * 
   * @param $token
   * 
   * @return string
   */

  public function edit_user(Request $request, $token)
  {
    $core = new CoreController;

    $this->token = $token;

    $this->tokne_name = __('words.uuid');

    if($core->hasPermission('can_edit_user') == 1)
    {
      if(!empty($this->token))
      {
        $this->get_user = User::where('uuid', $this->token);

        if($this->get_user->count() == 1)
        {
          $this->user = $this->get_user->first();

          $this->username = $request->username;
          $this->email = $request->email;
          $this->password = $request->password;
          $this->role = $request->role;
          $this->timezone = $request->timezone;
          $this->permissions = $request->permissions;

          $this->username_name = mb_strtolower(__('words.username'));
          $this->email_name = mb_strtolower(__('words.email'));
          $this->password_name = mb_strtolower(__('words.password'));
          $this->role_name = mb_strtolower(__('words.role'));
          $this->timezone_name = mb_strtolower(__('words.timezone'));
          $this->permissions_name = mb_strtolower(__('words.permission.pl'));

          $this->error = 0;

          if(empty($this->username))
          {
            $this->error = 1;
            $this->error_username = __('validation.filled', ['attribute' => $this->username_name]);
          }
          else if(strlen($this->username) < $core->setting('username_min_length'))
          {
            $this->error = 1;
            $this->error_username = __('validation.gt.numeric', ['attribute' => $this->username_name, 'value' => $core->setting('username_min_length')]);
          }
          else if(strlen($this->username) > $core->setting('username_max_length'))
          {
            $this->error = 1;
            $this->error_username = __('validation.lt.numeric', ['attribute' => $this->username_name, 'value' => $core->setting('username_max_length')]);
          }
          else if(!preg_match($core->setting('username_regex'), $this->username))
          {
            $this->error = 1;
            $this->error_username = __('validation.not_regex', ['attribute' => $this->username_name]);
          }
          else
          {
            $this->check_username = User::where([['username', '=', $this->username], ['uuid', '!=', $this->user->uuid]])->count();

            if($this->check_username != 0)
            {
              $this->error = 1;
              $this->error_username = __('validation.unique', ['attribute' => $this->username_name]);
            }
            else
            {
              $this->error_username = "";
            }
          }

          if(empty($this->email))
          {
            $this->error = 1;
            $this->error_email = __('validation.filled', ['attribute' => $this->email_name]);
          }
          else if(!filter_var($this->email, FILTER_VALIDATE_EMAIL))
          {
            $this->error = 1;
            $this->error_email = __('validation.email', ['attribute' => '"'.$this->email_name.'"']);
          }
          else if(strlen($this->email) > 128)
          {
            $this->error = 1;
            $this->error_email = __('validation.lt.numeric', ['attribute' => $this->email_name, 'value' => 128]);
          }
          else
          {
            $this->check_email = User::where([['email', '=', $this->email], ['uuid', '!=', $this->user->uuid]])->count();

            if($this->check_email != 0)
            {
              $this->error = 1;
              $this->error_email = __('validation.unique', ['attribute' => $this->email_name]);
            }
            else
            {
              $this->error_email = "";
            }
          }

          if(!empty($this->password))
          {
            if(strlen($this->password) < 6)
            {
              $this->error = 1;
              $this->error_password = __('validation.gt.numeric', ['attribute' => $this->password_name]);
            }
            else if(!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]+$/', $this->password))
            {
              $this->error = 1;
              $this->error_password = __('validation.not_regex', ['attribute' => $this->password_name]);
            }
            else
            {
              $this->error_password = "";
            }
          }
          else
          {
            $this->error_password = "";
          }

          if(empty($this->role))
          {
            $this->error = 1;
            $this->error_role = __('validation.filled', ['attribute' => $this->role_name]);
          }
          else
          {
            $this->role_list = [];

            foreach(Role::all() as $item)
            {
              $this->role_list[] = $item->special_id;
            }

            if(!in_array($this->role, $this->role_list))
            {
              $this->error = 1;
              $this->error_role = __('validation.in', ['attribute' => $this->role_name]);
            }
            else
            {
              $this->error_role = "";
            }
          }

          if(empty($this->timezone))
          {
            $this->error = 1;
            $this->error_timezone = __('validation.filled', ['attribute' => $this->timezone_name]);
          }
          else
          {
            $this->timezone_list = json_decode(Storage::disk('private')->get('timezones.json'));

            if(!in_array($this->timezone, $this->timezone_list))
            {
              $this->error = 1;
              $this->error_timezone = __('validation.in', ['attribute' => $this->timezone_name]);
            }
            else
            {
              $this->error_timezone = "";
            }
          }

          if(!empty($this->permissions))
          {
            $this->permission_list = json_decode(Permission::all());

            foreach($this->permission_list as $item)
            {
              if(!in_array($item, $this->permission_list))
              {
                $this->error = 1;
                $this->error_permissions = __('validation.in', ['attribute' => $this->permissions_name]);
              }
              else
              {
                $this->error_permissions = "";
              }
            }
          }
          else
          {
            $this->error_permissions = "";
          }

          if($this->error != 1)
          {
            $this->get_user->update([
              'username' => $this->username,
              'email' => $this->email,
              'password' => !empty($this->password) ? $core->hash($this->password) : $this->user->password,
              'role' => $this->role,
              'timezone' => $this->timezone,
              'permissions' => json_encode(!empty($this->permissions) ? $this->permissions : ['']),
              'updated_at' => time()
            ]);

            return response()->json([
              'error' => 0,
              'message' => __('sentences.user-edited')
            ]);
          }
          else
          {
            return response()->json([
              'error' => 1,
              'error_username' => $this->error_username,
              'error_email' => $this->error_email,
              'error_password' => $this->error_password,
              'error_role' => $this->error_role,
              'error_timezone' => $this->error_timezone,
              'error_permissions' => $this->error_permissions
            ]);
          }
        }
        else
        {
          return response()->jons([
            'error' => 2,
            'message' => __('validation.in', ['attribute' => $this->token_name])
          ]);
        }
      }
      else
      {
        return response()->jons([
          'error' => 2,
          'message' => __('validation.filled', ['attribute' => $this->token_name])
        ]);
      }
    }
    else
    {
      return response()->json([
        'error' => 2,
        'message' => __('sentences.no-perm')
      ]); 
    }
  }

  /**
   * Remove's a user from the database
   * 
   * @param $token
   * 
   * @return string
   */

  public function remove_user($token)
  {
    $core = new CoreController;
    $this->user = Auth::user();

    $this->token = $token;
    $this->token_name = __('words.uuid');

    $this->error = 0;

    if($core->hasPermission('can_remove_user') == 1)
    {
      if(empty($this->token))
      {
        $this->error = 1;
        $this->error_message = __('validation.filled', ['attribute' => $this->token_name]);
      }
      else
      {
        $this->get_user = User::where([['uuid', '=', $this->token], ['uuid', '!=', $this->user->uuid]]);

        if($this->get_user->count() != 1)
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
        $this->get_user->delete();

        return response()->json([
          'error' => 0,
          'message' => __('sentences.user-removed')
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
      return response()->json([
        'error' => 2,
        'message' => __('sentences.no-perm')
      ]);
    }
  }

  /**
   * Activate & Deactivate User Account
   * 
   * @param \Request $request
   * 
   * @return string
   */

  public function dea_act_user(Request $request)
  {
    $core = new CoreController;

    $this->uuid = $request->uuid;
    $this->type = $request->type;

    $this->uuid_name = __('words.uuid');
    $this->type_name = mb_strtolower(__('words.action-type'));

    $this->error = 0;

    if($core->hasPermission('can_edit_user') == 1)
    {
      if(empty($this->uuid))
      {
        $this->error = 1;
        $this->error_message = __('validation.filled', ['attribute' => $this->uuid_name]);
      }
      else
      {
        $this->get_user = User::where([['uuid', '=', $this->uuid], ['email', '!=', Auth::user()->email]]);

        if($this->get_user->count() != 1)
        {
          $this->error = 1;
          $this->error_message = __('validation.in', ['attribute' => $this->uuid_name]);
        }
        else
        {
          $this->error_message = "";
        }

        if(!in_array($this->type, ['activate', 'deactivate']))
        {
          $this->error = 1;
          $this->error_message = __('validation.in', ['attribute' => $this->type_name]);
        }
        else
        {
          $this->error_message = "";
        }
      }

      if($this->error != 1)
      {
        if($this->type == "activate")
        {
          $this->type = 1;
          $this->success_message = __('sentences.user-suc-activated');
        }
        else
        {
          $this->type = 0;
          $this->success_message = __('sentences.user-suc-deactivated');
        }

        $this->get_user->update([
          'is_active' => $this->type
        ]);

        return response()->json([
          'error' => 0,
          'message' => $this->success_message
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
      return response()->json([
        'error' => 2,
        'message' => __('sentences.no-perm')
      ]);
    }
  }
}
