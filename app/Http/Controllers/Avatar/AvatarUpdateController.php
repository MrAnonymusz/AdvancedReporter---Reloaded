<?php

namespace App\Http\Controllers\Avatar;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CoreController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\User;

class AvatarUpdateController extends Controller
{
  private $user;
  private $token;

  /**
   * Update the user's avatar
   * 
   * @param Request $request
   * 
   * @param $token
   * 
   * @return string
   */

  public function action(Request $request, $token)
  {
    $core = new CoreController;

    $this->token = $token;
    $this->admin = $request->admin;

    $this->token_name = __('words.uuid');

    if(empty($this->admin) || $this->admin == 0)
    {
      $this->get_user = User::where('uuid', $this->token);

      if($this->get_user->count() == 1 && $this->token == Auth::user()->uuid)
      {
        $this->user = User::where('uuid', $this->token)->first();

        $this->type = $request->type;

        $this->type_name = mb_strtolower(__('words.type'));

        $this->allowed_types = ['crafatar'];

        $this->error = 0;

        if(!in_array($this->type, $this->allowed_types))
        {
          $this->error = 1;
          $this->error_message = __('validation.in', ['attribute' => $this->type_name]);
        }
        else
        {
          $this->error_message = "";
        }

        if($this->error != 1)
        {
          switch($this->type)
          {
            case 'crafatar':
              $this->mojang_api = file_get_contents('https://api.mojang.com/users/profiles/minecraft/'.$this->user->username);

              if(empty($this->mojang_api) && preg_match('/error+/', $this->mojang_api))
              {
                $this->mc_username = 'Alex';
              }

              User::where('uuid', $this->user->uuid)->update([
                'avatar' => json_encode([
                  'type' => 'crafatar',
                  'username' => $this->user->username
                ])
              ]);
              break;
          }

          return response()->json([
            'error' => 0,
            'message' => __('sentences.avatar-updated')
          ]);
        }
        else
        {
          return response()->json([
            'error' => 1,
            'error_message' => $this->error_message
          ]);
        }
      }
      else
      {
        return response()->json([
          'error' => 1,
          'error_message' => __('validation.in', ['attribute' => $this->token_name])
        ]);
      }
    }
    else
    {
      if($core->hasPermission('can_edit_user'))
      {
        $this->get_user = User::where('uuid', $this->token);

        if($this->get_user->count() == 1)
        {
          $this->user = User::where('uuid', $this->token)->first();

          $this->type = $request->type;

          $this->type_name = mb_strtolower(__('words.type'));

          $this->allowed_types = ['crafatar'];

          $this->error = 0;

          if(!in_array($this->type, $this->allowed_types))
          {
            $this->error = 1;
            $this->error_message = __('validation.in', ['attribute' => $this->type_name]);
          }
          else
          {
            $this->error_message = "";
          }

          if($this->error != 1)
          {
            switch($this->type)
            {
              case 'crafatar':
                $this->mojang_api = file_get_contents('https://api.mojang.com/users/profiles/minecraft/'.$this->user->username);

                if(empty($this->mojang_api) && preg_match('/error+/', $this->mojang_api))
                {
                  $this->mc_username = 'Alex';
                }

                User::where('uuid', $this->user->uuid)->update([
                  'avatar' => json_encode([
                    'type' => 'crafatar',
                    'username' => $this->user->username
                  ])
                ]);
                break;
            }

            return response()->json([
              'error' => 0,
              'message' => __('sentences.avatar-updated')
            ]);
          }
          else
          {
            return response()->json([
              'error' => 1,
              'error_message' => $this->error_message
            ]);
          }
        }
        else
        {
          return response()->json([
            'error' => 1,
            'error_message' => __('validation.in', ['attribute' => $this->token_name])
          ]);
        }
      }
      else
      {
        return response()->json([
          'error' => 1,
          'error_message' => __('words.no-permissions')
        ]);
      }
    }
  }
}
