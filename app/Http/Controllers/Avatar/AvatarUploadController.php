<?php

namespace App\Http\Controllers\Avatar;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CoreController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\User;
use Image;

class AvatarUploadController extends Controller
{
  private $avatar_file;
  private $token;

  /**
   * Upload's an image and set it as the user's avatar
   * 
   * @param Request $request
   * 
   * @return string
  */

  public function action(Request $request, $token)
  {
    $core = new CoreController;

    $this->admin = $request->admin;
    $this->token = $token;

    $this->token_name = __('words.uuid');

    $this->avatar_file = $request->avatar_file;

    $this->avatar_file_name = mb_strtolower(__('words.avatar-file'));

    $this->file_size = $core->setting('allowed_avatar_size') * 1048576;
    $this->allowed_file_extensions = json_decode($core->setting('allowed_avatar_types'));

    if(!$request->hasFile('avatar_file'))
    {
      $this->error = 1;
      $this->error_avatar_file = __('validation.filled', ['attribute' => $this->avatar_file_name]);
    }
    else if(filesize($this->avatar_file) > $this->file_size)
    {
      $this->error = 1;
      $this->error_avatar_file = __('validation.size.file', ['attribute' => $this->avatar_file_name, 'size' => $this->file_size]);
    }
    else if(!in_array($this->avatar_file->getClientOriginalExtension(), $this->allowed_file_extensions))
    {
      $this->error = 1;
      $this->error_avatar_file = __('validation.in', ['attribute' => $this->avatar_file_name]);
    }
    else
    {
      $this->error_avatar_file = "";
    }

    $this->error = 0;

    if($this->error != 1)
    {
      if(empty($this->admin) || $this->admin == 0)
      {
        $this->get_user = User::where('uuid', $this->token);

        if($this->get_user->count() == 1 && $this->token == Auth::user()->uuid)
        {
          $this->user = $this->get_user->first();

          $this->user_avatar = json_decode($this->user->avatar);

          if($this->user_avatar->type == "upload")
          {
            Storage::disk('private')->delete('avatars/'.$this->user_avatar->filename);
          }

          $this->filename = time().'_'.$this->user->uuid.'.'.$this->avatar_file->getClientOriginalExtension();

          User::where('uuid', $this->user->uuid)->update([
            'avatar' => json_encode([
              'type' => 'upload',
              'filename' => $this->filename,
              'extension' => $this->avatar_file->getClientOriginalExtension(),
              'mime' => $this->avatar_file->getClientMimeType()
            ], JSON_UNESCAPED_SLASHES)
          ]);

          $img = Image::make($this->avatar_file)->fit(128);

          $img->save(storage_path(str_replace('/', DIRECTORY_SEPARATOR, 'app/private/avatars/').$this->filename));

          return response()->json([
            'error' => 0,
            'message' => __('sentences.avatar-uploaded')
          ]);
        }
        else
        {
          return response()->json([
            'error' => 1,
            'error_avatar_file' => __('validation.in', ['attribute' => $this->token_name])
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

            if($this->user_avatar->type == "upload")
            {
              Storage::disk('private')->delete('avatars/'.$this->user_avatar->filename);
            }

            $this->filename = time().'_'.$this->user->uuid.'.'.$this->avatar_file->getClientOriginalExtension();

            User::where('uuid', $this->user->uuid)->update([
              'avatar' => json_encode([
                'type' => 'upload',
                'filename' => $this->filename,
                'extension' => $this->avatar_file->getClientOriginalExtension(),
                'mime' => $this->avatar_file->getClientMimeType()
              ], JSON_UNESCAPED_SLASHES)
            ]);

            $img = Image::make($this->avatar_file)->fit(128);

            $img->save(storage_path(str_replace('/', DIRECTORY_SEPARATOR, 'app/private/avatars/').$this->filename));

            return response()->json([
              'error' => 0,
              'message' => __('sentences.avatar-uploaded')
            ]);
          }
          else
          {
            return response()->json([
              'error' => 1,
              'error_avatar_file' => __('validation.in', ['attribute' => $this->token_name])
            ]);
          }
        }
        else
        {
          return response()->json([
            'error' => 1,
            'error_avatar_file' => __('words.no-permissions')
          ]);
        }
      }
    }
    else
    {
      return response()->json([
        'error' => 1,
        'error_avatar_file' => $this->error_avatar_file
      ]);
    }
  }
}
