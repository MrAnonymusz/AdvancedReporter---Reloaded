<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\User;

class LogsController extends Controller
{
  private $token;

  /**
   * Clear's all the logs for the user
   * 
   * @param \Request $request
   * 
   * @return string
   */

  public function clear_all_logs(Request $request)
  {
    $this->user = Auth::user();
    $this->token = $request->user;

    $this->token_name = __('words.user-id');

    $this->error = 0;

    if(empty($this->user))
    {
      $this->error = 1;
      $this->error_message = __('validation.filled', ['attribute' => $this->token_name]);
    }
    else
    {
      $this->get_user = User::where('uuid', $this->token);

      if($this->get_user->count() != 1 && $this->get_user->first()->username != $this->user->username)
      {
        $this->error = 1;
        $this->error_message = __('validation.custom.user-not-found');
      }
      else
      {
        $this->error_message = "";
      }
    }

    if($this->error != 1)
    {
      DB::table('auth_logs')->where([['visible', '=', 1], ['user', '=', $this->token]])->update([
        'visible' => 0
      ]);

      return response()->json([
        'error' => 0,
        'message' => __('sentences.logs-cleared')
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
}
