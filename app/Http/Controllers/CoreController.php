<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Setting;
use App\User;
use App\Role;
use Carbon;

class CoreController extends Controller
{
  private $value;
  private $option;
  private $output;

  /*
    >> Random String Generator
  */

  public function randomStringGenerate($value = 16, $option = "")
  {
    $this->value = $value;
    $this->option = $option;

    if(empty($this->option))
    {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    }
    else
    {
      $characters = $this->option;
    }

    $charactersLength = strlen($characters);
    $randomString = '';

    for ($i = 0; $i < $this->value; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }

    return $randomString;
  }

  /*
    >> Generate's an ID
  */

  public function generateID($value = "")
  {
    $this->value = $value;

    $this->explode = explode('.', uniqid('', true));
    $this->suffix = str_shuffle($this->explode[0].$this->explode[1]);

    $this->output = "";

    if(empty($this->value))
    {
      $this->output = $this->suffix;
    }
    else
    {
      $this->output = $this->value.'_'.$this->suffix;
    }

    return $this->output;
  }

  /*
    >> Hash's a string
  */

  public function hash($value, $option = [])
  {
    $this->value = $value;
    $this->option = $option;

    $this->output = "";

    if(empty($this->option[0]))
    {
      $this->hash = Hash::make($this->value);
    }
    else
    {
      $this->hash = Hash::make($this->value, $this->option);
    }

    $this->output = $this->hash;

    return $this->output;
  }

  public function verifyPassword($value, $option)
  {
    $this->value = $value;
    $this->option = $option;

    if(Hash::check($this->value, $this->option))
    {
      return true;
    }
    else
    {
      return false;
    }
  }

  /*
    >> Return's a setting from the database!
  */

  public function setting($value, $option = "")
  {
    $this->value = $value;
    $this->option = $option;

    $this->query = Setting::where('special_id', $this->value)->first();

    $this->output = "";

    if(empty($this->option))
    {
      $this->output = $this->query->value;
    }
    else
    {
      $this->output = $this->query->{$this->option};
    }

    return $this->output;
  }

  /*
    >> Asset URL
  */

  public function asset_url($value = "", $option = [1, 1])
  {
    $this->value = $value;
    $this->option = $option;

    $this->output = "";

    if(empty($this->value))
    {
      $this->suffix = '';
    }
    else
    {
      $this->suffix = $this->value;
    }

    if($this->option[0] == 1)
    {
      $this->version_string = 'version='.time();

      if(!preg_match('/(\?)*/', $this->suffix))
      {
        $this->vs_delimiter = '&';
      }
      else
      {
        $this->vs_delimiter = '?';
      }
    }
    else
    {
      $this->version_string = "";
      $this->vs_delimiter = "";
    }

    if($this->option[1] == 1)
    {
      $this->folder = 'main';
    }
    else
    {
      $this->folder = 'admin';
    }

    $this->output = asset('assets/'.$this->folder.'/'.$this->suffix.$this->vs_delimiter.$this->version_string);

    return $this->output;
  }

  /*
    >> Avatar URL
  */

  public function avatar_url($value = "", $option = "")
  {
    $this->value = $value;
    $this->option = empty($option) ? false : $option;

    $this->output = "";

    if(empty($this->value))
    {
      $this->user = Auth::user();
    }
    else
    {
      $this->user = User::where('username', $this->value)->orWhere('email', $this->value)->orWhere('uuid', $this->value)->first();
    }

    $this->avatar_url = url('avatar/render/'.$this->user->uuid);

    if($this->option == true)
    {
      $this->version_string = '?version='.time();
    }
    else
    {
      $this->version_string = '';
    }

    $this->output = $this->avatar_url.$this->version_string;

    return $this->output;
  }

  /*
    >> IP Query
  */

  public function ip_query($value = "")
  {
    $this->value = $value;

    $this->provider = 'http://ip-api.com/json';

    $this->output = "";

    if(empty($this->query))
    {
      $this->query = json_decode(file_get_contents($this->provider));
    }
    else
    {
      $this->query = json_decode(file_get_contents($this->provider.'/'.$this->value));
    }

    $this->output = $this->query;

    return $this->output;
  }

  /*
    >> Role
  */

  public function role($value = "")
  {
    $this->value = $value;

    $this->output = "";

    if(empty($this->value))
    {
      $this->role = Role::where('special_id', Auth::user()->role)->first();
    }
    else
    {
      $this->role = Role::where('special_id', $this->value)->first();
    }

    $this->output = $this->role;

    return $this->output;
  }

  /*
    >> Check if the user has permissions
  */

  public function hasPermission($value)
  {
    $this->value = $value;

    $this->user = Auth::user();

    $this->role_permissions = json_decode(Role::where('special_id', $this->user->role)->first()->permissions);

    $this->user_permissions = json_decode($this->user->permissions);

    $this->output = "";

    foreach(array_merge($this->role_permissions, $this->user_permissions) as $item)
    {
      if(!empty($item))
      {
        $this->permissions_list[] = $item;
      }
    }

    if(!in_array('superadmin', $this->permissions_list))
    {
      if(in_array($this->value, $this->permissions_list))
      {
        $this->output = true;
      }
      else
      {
        $this->output = false;
      }
    }
    else
    {
      $this->output = true;
    }

    return $this->output;
  }

  /*
    >> Check if the user has multiple permissions
  */

  public function hasPermissions($value, $option = false)
  {
    $this->value = $value;
    $this->option = $option;

    $this->output = "";

    foreach($this->value as $item)
    {
      if($this->hasPermission($item))
      {
        $this->permissions_list[] = 1;
      }
      else
      {
        $this->permissions_list[] = 0;
      }
    }

    if($this->option == false)
    {
      if(!in_array(0, $this->permissions_list))
      {
        $this->output = true;
      }
      else
      {
        $this->output = false;
      }
    }
    else
    {
      if(in_array(1, $this->permissions_list))
      {
        $this->output = true;
      }
      else
      {
        $this->output = false;
      }
    }

    return $this->output;
  }

  /*
    >> DT Format
  */

  public function dt_format($value = "", $option = "")
  {
    $this->t_value = !empty($value) ? $value : time();
    $this->option = $option;

    $this->user = Auth::user();

    $this->output = "";

    if(!Auth::check())
    {
      $this->timezone = $this->setting('site_timezone');
    }
    else
    {
      $this->timezone = $this->user->timezone;
    }

    if(!empty($this->option))
    {
      $this->t_format = $this->option;
    }
    else
    {
      $this->t_format = $this->setting('site_time_format');
    }

    $this->output = Carbon::createFromTimestamp($this->t_value)->setTimezone($this->timezone)->format($this->t_format);

    return $this->output;
  }
}
