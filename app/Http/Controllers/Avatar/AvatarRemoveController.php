<?php

namespace App\Http\Controllers\Avatar;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CoreController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\User;

class AvatarRemoveController extends Controller
{
  private $user;
  private $token;

  /**
   * Remove's a user avatar from the storage and database
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
        $this->user = $this->get_user->first();

        $this->user_avatar = json_decode($this->user->avatar);

        $this->error = 0;

        if(empty($this->token))
        {
          $this->error = 1;
          $this->error_message = __('validation.filled', ['attribute' => $this->token_name]);
        }
        else
        {
          if($this->token != $this->user->uuid)
          {
            $this->error = 1;
            $this->error_message = __('validation.in', ['attribute' => $this->token_name]);
          }
          else if($this->user_avatar->type == "default")
          {
            $this->error = 1;
            $this->error_message = __('sentences.error-default-avatar');
          }
          else
          {
            $this->error_message = "";
          }
        }

        if($this->error != 1)
        {
          if($this->user_avatar->type == "upload")
          {
            Storage::disk('private')->delete('avatars/'.$this->user_avatar->filename);
          }

          User::where('uuid', $this->user->uuid)->update([
            'avatar' => json_encode([
              'type' => 'default'
            ])
          ]);

          return response()->json([
            'error' => 0,
            'message' => __('sentences.avatar-removed')
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
          'error' => 1,
          'message' => __('validation.in', ['attribute' => $this->token_name])
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
          $this->user = $this->get_user->first();

          $this->user_avatar = json_decode($this->user->avatar);

          $this->error = 0;

          if(empty($this->token))
          {
            $this->error = 1;
            $this->error_message = __('validation.filled', ['attribute' => $this->token_name]);
          }
          else
          {
            if($this->token != $this->user->uuid)
            {
              $this->error = 1;
              $this->error_message = __('validation.in', ['attribute' => $this->token_name]);
            }
            else if($this->user_avatar->type == "default")
            {
              $this->error = 1;
              $this->error_message = __('sentences.error-default-avatar');
            }
            else
            {
              $this->error_message = "";
            }
          }

          if($this->error != 1)
          {
            if($this->user_avatar->type == "upload")
            {
              Storage::disk('private')->delete('avatars/'.$this->user_avatar->filename);
            }

            User::where('uuid', $this->user->uuid)->update([
              'avatar' => json_encode([
                'type' => 'default'
              ])
            ]);

            return response()->json([
              'error' => 0,
              'message' => __('sentences.avatar-removed')
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
            'error' => 1,
            'message' => __('validation.in', ['attribute' => $this->token_name])
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
