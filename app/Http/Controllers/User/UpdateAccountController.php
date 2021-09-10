<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CoreController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\User;

class UpdateAccountController extends Controller
{
  private $username;
  private $email;
  private $password;
  private $timezone;

  /**
   * Update's a user account
   * 
   * @param Request $request
   * 
   * @return string
   */

  public function action(Request $request)
  {
    $core = new CoreController;
    $this->user = Auth::user();

    $this->username = $request->username;
    $this->email = $request->email;
    $this->password = $request->password;
    $this->timezone = $request->timezone;

    $this->username_name = mb_strtolower(__('words.username'));
    $this->email_name = mb_strtolower(__('words.email'));
    $this->password_name = mb_strtolower(__('words.password'));
    $this->timezone_name = mb_strtolower(__('words.timezone'));

    $this->error = 0;

    if(empty($this->username))
    {
      $this->error = 1;
      $this->error_username = __('validation.filled', ['attribute' => $this->username_name]);
    }
    else if(!preg_match($core->setting('username_regex'), $this->username))
    {
      $this->error = 1;
      $this->error_username = __('validation.regex', ['attribute' => $this->username_name]);
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
      $this->error_email = __('validation.in', ['attribute' => $this->email_name]);
    }
    else if(strlen($this->email) < 4)
    {
      $this->error = 1;
      $this->error_email = __('validation.gt.numeric', ['attribute' => $this->email_name, 4]);
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

    $this->timezone_list = json_decode(Storage::disk('private')->get('timezones.json'));

    if(empty($this->timezone))
    {
      $this->error = 1;
      $this->error_timezone = __('validation.filled', ['attribute' => $this->timezone_name]);
    }
    else if(!in_array($this->timezone, $this->timezone_list))
    {
      $this->error = 1;
      $this->error_timezone = __('validation.in', ['attribute' => $this->timezone_name]);
    }
    else
    {
      $this->error_timezone = "";
    }

    if($this->error != 1)
    {
      $this->final_password = empty($this->password) ? $this->user->password : $core->hash($this->password);

      User::where('uuid', $this->user->uuid)->update([
        'username' => $this->username,
        'email' => $this->email,
        'password' => $this->final_password,
        'timezone' => $this->timezone,
        'updated_at' => time()
      ]);

      return response()->json([
        'error' => 0,
        'message' => __('sentences.account-updated')
      ]);
    }
    else
    {
      return response()->json([
        'error' => 1,
        'error_username' => $this->error_username,
        'error_email' => $this->error_email,
        'error_password' => $this->error_password,
        'error_timezone' => $this->error_timezone
      ]);
    }
  }
}
