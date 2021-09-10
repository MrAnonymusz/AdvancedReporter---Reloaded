<?php

namespace App\Http\Controllers\Avatar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\CoreController;
use App\User;
use Image;

class AvatarRenderController extends Controller
{
  private $token;
  private $value;
  private $output;

  /*
    >> Default Avatar Type
  */

  public function render_default()
  {
    $core = new CoreController;

    $this->avatar_path = public_path(str_replace('/', DIRECTORY_SEPARATOR, 'assets/main/'.$core->setting('default_avatar')));

    $img = Image::make($this->avatar_path);

    $img->fit(128, 128);

    $this->output = [
      'value' => $img->encode('jpg'),
      'mime' => 'image/jpeg'
    ];

    return $this->output;
  }

  /*
    >> Crafatar Avatar Type (https://crafatar.com/)
  */

  public function render_crafatar($value)
  {
    $this->value = $value;

    $this->username = !empty($this->value->username) ? $this->value->username : 'Alex';

    $this->query_url = 'https://api.mojang.com/users/profiles/minecraft/'.$this->username;

    $this->mojang_api = json_decode(file_get_contents($this->query_url), true);

    $this->avatar_url = 'https://crafatar.com/avatars/'.$this->mojang_api['id'].'?s=256';

    $img = Image::make($this->avatar_url);

    $img->fit(128, 128);

    $this->output = [
      'value' => $img->encode('jpg'),
      'mime' => 'image/jpeg'
    ];

    return $this->output;
  }

  /*
    >> Render Uploaded Avatars
  */

  public function render_upload($value)
  {
    $this->value = $value;

    $this->file_path = storage_path(str_replace('/', DIRECTORY_SEPARATOR, 'app/private/avatars/'.$this->value->filename));

    $img = Image::make($this->file_path);

    $img->fit(128, 128);

    $this->output = [
      'value' => $img->encode($this->value->extension),
      'mime' => $this->value->type
    ];

    return $this->output;
  }

  /*
    >> Renders the Avatar
  */

  public function render($token)
  {
    $this->token = $token;

    if(empty($this->token))
    {
      $this->error = 1;
    }
    else
    {
      $this->get_user = User::where('username', $this->token)->orWhere('email', $this->token)->orWhere('uuid', $this->token);

      if($this->get_user->count() != 1)
      {
        $this->error = 1;
      }
    }

    $this->error = 0;
    $this->output = "";

    if($this->error != 1)
    {
      $this->user = $this->get_user->first();

      $this->user_avatar = json_decode($this->user->avatar);

      switch($this->user_avatar->type)
      {
        case 'default':
          $this->output = $this->render_default();
          break;
        case 'crafatar':
          $this->output = $this->render_crafatar($this->user_avatar);
          break;
        case 'upload':
          $this->output = $this->render_upload($this->user_avatar);
          break;
        default:
          $this->img_path = public_path(str_replace('/', DIRECTORY_SEPARATOR, 'assets/main/img/error_img.jpg'));

          $img = Image::make($this->img_path);

          $img->resize(128, 128);

          $this->output = [
            'value' => $img->encode('jpg'),
            'mime' => 'image/jpeg'
          ];
          break;
      }

      return response($this->output['value'])->header('Content-Type', $this->output['mime']);
    }
    else
    {
      $this->img_path = public_path(str_replace('/', DIRECTORY_SEPARATOR, 'assets/main/img/error_img.jpg'));

      $img = Image::make($this->img_path);

      $img->resize(128, 128)->encode('jpg');

      return response($img)->header('Content-Type', 'image/jpeg');
    }
  }
}
