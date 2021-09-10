<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CoreController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\User;

class LoginController extends Controller
{
  private $email;
  private $password;
  private $remember_me;

  public function action(Request $request)
  {
    $core = new CoreController;

    $this->error = 0;

    $this->email = $request->email;
    $this->password = $request->password;
    $this->remember_me = $request->remember_me;

    $this->email_name = mb_strtolower(__('words.email-address'));
    $this->password_name = mb_strtolower(__('words.password'));
    $this->remember_me_name = __('pages.auth.login.remember-me');

    if(empty($this->email))
    {
      $this->error = 1;
      $this->error_email = __('validation.filled', ['attribute' => $this->email_name]);
    }
    else if(strlen($this->email) > 128)
    {
      $this->error = 1;
      $this->error_email = __('validation.lt.numeric', ['attribute' => $this->email_name, 'value' => 128]);
    }
    else if(strlen($this->email) < 4)
    {
      $this->error = 1;
      $this->error_email = __('validation.gt.numeric', ['attribute' => $this->email_name, 'value' => 4]);
    }
    else if(!filter_var($this->email, FILTER_VALIDATE_EMAIL))
    {
      $this->error = 1;
      $this->error_email = __('validation.in', ['attribute' => $this->email_name]);
    }
    else
    {
      $this->get_user = User::where('email', $this->email);

      if($this->get_user->count() != 1)
      {
        $this->error = 1;
        $this->error_email = __('passwords.user');
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
      $this->error_password = __('validation.gt.numeric', ['attribute' => $this->password_name, 'value' => 6]);
    }
    else if(strlen($this->password) > 128)
    {
      $this->error = 1;
      $this->error_password = __('validation.lt.numeric', ['attribute' => $this->password_name, 'value' => 128]);
    }
    else
    {
      $this->error_password = "";
    }

    if(!in_array($this->remember_me, [0, 1]))
    {
      $this->error = 1;
      $this->error_remember_me = __('validation.in', ['attribute' => $this->remember_me_name]);
    }
    else
    {
      $this->error_remember_me = ""; 
    }

    if($this->error != 1)
    {
      $this->user = $this->get_user->first();

      if($this->user->is_active == 1)
      {
        $this->credentials = [
          'email' => $this->email,
          'password' => $this->password,
          'is_active' => 1
        ];

        if(Auth::attempt($this->credentials, $this->remember_me == 1 ? true : false))
        {
          // Log        
          DB::table('auth_logs')->insert([
            'id' => $core->generateID(),
            'user' => $this->user->uuid,
            'ip_address' => $core->ip_query()->query,
            'payload' => json_encode([
              'info' => [
                'country' => $core->ip_query()->country,
                'city' => $core->ip_query()->city,
                'lat' => $core->ip_query()->lat,
                'lon' => $core->ip_query()->lon
              ]
            ]),
            'action' => 'login',
            'created_at' => time()
          ]);

          // Update IP Address
          $this->get_user->update([
            'ip_address' => $core->ip_query()->query
          ]);

          return response()->json([
            'error' => 0,
            'message' => __('sentences.login-successful')
          ]);
        }
        else
        {
          return response()->json([
            'error' => 2,
            'message' => __('sentences.failed-to-login')
          ]);
        }
      }
      else
      {
        return response()->json([
          'error' => 2,
          'message' => __('validation.custom.account-not-active')
        ]);
      }
    }
    else
    {
      return response()->json([
        'error' => 1,
        'error_email' => $this->error_email,
        'error_password' => $this->error_password,
        'error_remember_me' => $this->error_remember_me
      ]);
    }
  }
}
