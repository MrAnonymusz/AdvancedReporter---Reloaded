<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;

class VerifyPasswordController extends Controller
{
  private $password;

  /**
   * Verifies a user password
   * 
   * @param Request $request
   * 
   * @return string
   */

  public function action(Request $request)
  {
    $core = new CoreController;
    $this->user = Auth::user();

    $this->password = $request->password;

    $this->password_name = mb_strtolower(__('words.password'));

    $this->error = 0;

    if(empty($this->password))
    {
      $this->error = 1;
      $this->error_message = __('validation.filled', ['attribute' => $this->password_name]);
    }
    else
    {
      $this->error_message = "";
    }

    if($this->error != 1)
    {
      if($core->verifyPassword($this->password, $this->user->password))
      {
        return response()->json(['error' => 0]);
      }
      else
      {
        return response()->json([
          'error' => 1,
          'error_message' => __('validation.password')
        ]);
      }
    }
    else
    {
      return response()->json([
        'error' => 1,
        'error_message' => $this->error_message
      ]);
    }
  }
}
