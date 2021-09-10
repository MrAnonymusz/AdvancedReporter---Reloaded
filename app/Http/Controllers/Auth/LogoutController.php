<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;

class LogoutController extends Controller
{
  private $token;

  /*
    >> Log's out a user
  */

  public function action($token)
  {
    $this->token = $token;

    $this->error = 0;

    if(empty($this->token))
    {
      $this->error = 1;
      $this->msg_code = 404;
    }
    else
    {
      $this->get_user = User::where('uuid', $this->token);

      if($this->get_user->count() == 1)
      {
        $this->user = $this->get_user->first();

        if(Auth::user()->uuid != $this->user->uuid)
        {
          $this->msg_code = 404;
        }
      }
      else
      {
        $this->msg_code = 404;
      }
    }

    if($this->error != 1)
    {
      Auth::logout();

      return redirect('auth/login');
    }
    else
    {
      return abort($this->msg_code);
    }
  }
}
