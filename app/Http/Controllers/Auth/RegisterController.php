<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Role;

class RegisterController extends Controller
{
  private $username;
  private $email;
  private $password;
  private $terms_and_conditions;
  private $privacy_of_policy;

  /*
    >> Create's a user account
  */

  public function action(Request $request)
  {
    $core = new CoreController;

    if($core->setting('enable_registration') == 1)
    {
      $this->username = $request->username;
      $this->email = $request->email;
      $this->password = $request->password;
      $this->terms_and_conditions = $request->terms_and_conditions;
      $this->privacy_of_policy = $request->privacy_of_policy;

      $this->username_name = mb_strtolower(__('words.username'));
      $this->email_name = mb_strtolower(__('words.email-address'));
      $this->password_name = mb_strtolower(__('words.password'));
      $this->terms_and_conditions_name = __('words.terms-and-conditions');
      $this->privacy_of_policy_name = __('words.privacy-of-policy');

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

      if(!in_array($this->terms_and_conditions, [0, 1]))
      {
        $this->error = 1;
        $this->error_terms_and_conditions = __('validation.in', ['attribute' => $this->terms_and_conditions_name]);
      }
      else
      {
        $this->error_terms_and_conditions = "";
      }

      if(!in_array($this->privacy_of_policy, [0, 1]))
      {
        $this->error = 1;
        $this->error_privacy_of_policy = __('validation.in', ['attribute' => $this->privacy_of_policy_name]);
      }
      else
      {
        $this->error_privacy_of_policy = "";
      }

      if($this->error != 1)
      {
        if($this->terms_and_conditions == 1 && $this->privacy_of_policy == 1)
        {
          $this->default_role = Role::where([['enabled', '=', 1], ['default', '=', 1]])->first();
          $this->uuid = $core->generateID();

          // Create User
          User::insert([
            'username' => $this->username,
            'email' => $this->email,
            'password' => $core->hash($this->password),
            'ip_address' => $core->ip_query()->query,
            'avatar' => json_encode(['type' => 'default']),
            'role' => $this->default_role->special_id,
            'timezone' => $core->setting('site_timezone'),
            'is_active' => 0,
            'permissions' => '[""]',
            'created_at' => time(),
            'uuid' => $this->uuid
          ]);

          // Create LOG
          DB::table('auth_logs')->insert([
            'id' => $core->generateID(),
            'user' => $this->uuid,
            'ip_address' => $core->ip_query()->query,
            'action' => 'register',
            'created_at' => time()
          ]);

          return response()->json([
            'error' => 0,
            'message' => __('sentences.account-created')
          ]);
        }
        else
        {
          return response()->json([
            'error' => 2,
            'message' => __('validation.custom.you-must-accept', ['attribute' => $this->terms_and_conditions_name.' and '.$this->privacy_of_policy_name])
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
          'error_terms_and_conditions' => $this->error_terms_and_conditions,
          'error_privacy_of_policy' => $this->error_privacy_of_policy
        ]);
      }
    }
    else
    {
      return abort(403);
    }
  }
}
