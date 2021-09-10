<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Role;
use App\User;
use App\Permission;

class RoleController extends Controller
{
  private $display_name;
  private $special_id;
  private $css_class;
  private $permissions;
  private $enabled;
  private $token;

  /**
   * Create's a new role
   * 
   * @param \Request $request
   * 
   * @return string
   */

  public function create_role(Request $request)
  {
    $core = new CoreController;
    $this->user = Auth::user();
  
    if($core->hasPermission('can_create_role') == 1)
    {
      $this->display_name = $request->display_name;
      $this->special_id = $request->special_id;
      $this->css_class = $request->css_class;
      $this->permissions = $request->permissions;
      $this->enabled = $request->enabled;

      $this->display_name_name = mb_strtolower(__('words.name'));
      $this->special_id_name = __('words.special-id');
      $this->css_class_name = mb_strtolower(__('words.css-class'));
      $this->permissions_name = mb_strtolower(__('words.permission.pl'));
      $this->enabled_name = mb_strtolower(__('words.enabled'));

      $this->error = 0;

      if(empty($this->display_name))
      {
        $this->error = 1;
        $this->error_display_name = __('validation.filled', ['attribute' => $this->display_name_name]);
      }
      elseif(strlen($this->display_name) > 128)
      {
        $this->error = 1;
        $this->error_display_name = __('validation.lt.numeric', ['attribute' => $this->display_name_name, 'value' => 128]);
      }
      elseif(strlen($this->display_name) < 3)
      {
        $this->error = 1;
        $this->error_display_name = __('validation.gt.numeric', ['attribute' => $this->display_name_name, 'value' => 3]);
      }
      elseif(!preg_match('/^[a-zA-Z0-9_ ]+$/', $this->display_name))
      {
        $this->error = 1;
        $this->error_display_name = __('validation.in', ['attribute' => $this->display_name_name]);
      }
      else
      {
        $this->error_display_name = "";
      }

      if(empty($this->special_id))
      {
        $this->error = 1;
        $this->error_special_id = __('validation.filled', ['attribute' => $this->special_id_name]);
      }
      elseif(strlen($this->special_id) > 128)
      {
        $this->error = 1;
        $this->error_special_id = __('validation.lt.numeric', ['attribute' => $this->special_id_name, 'value' => 128]);
      }
      elseif(strlen($this->special_id) < 3)
      {
        $this->error = 1;
        $this->error_special_id = __('validation.gt.numeric', ['attribute' => $this->special_id_name, 'value' => 3]);
      }
      elseif(!preg_match('/^[a-zA-Z0-9_]+$/', $this->special_id))
      {
        $this->error = 1;
        $this->error_special_id = __('validation.in', ['attribute' => $this->special_id_name]);
      }
      else 
      {
        $this->error_special_id = "";
      }

      if(!empty($this->css_class))
      {
        if(strlen($this->css_class) > 128)
        {
          $this->error = 1;
          $this->error_css_class = __('validation.lt.numeric', ['attribute' => $this->css_class_name, 'value' => 128]);
        }
        else if(strlen($this->css_class) < 3)
        {
          $this->error = 1;
          $this->error_css_class = __('validation.gt.numeric', ['attribute' => $this->css_class_name, 'value' => 3]);
        }
        else if(!preg_match('/^[a-zA-Z0-9_\-]+$/', $this->css_class))
        {
          $this->error = 1;
          $this->error_css_class = __('validation.in', ['attribute' => $this->css_class_name]);
        }
        else
        {
          $this->error_css_class = "";
        }
      }
      else
      {
        $this->error_css_class = "";
      }

      if(!empty($this->permissions))
      {
        $this->permission_list = json_decode(Permission::all());

        foreach($this->permission_list as $item)
        {
          if(!in_array($item, $this->permission_list))
          {
            $this->error = 1;
            $this->error_permissions = __('validation.in', ['attribute' => $this->permissions_name]);
          }
          else
          {
            $this->error_permissions = "";
          }
        }
      }
      else
      {
        $this->error_permissions = "";
      }

      if(!in_array($this->enabled, [0, 1]))
      {
        $this->error = 1;
        $this->error_enabled = __('validation.in', ['attribute' => $this->enabled_name]);
      }
      else
      {
        $this->error_enabled = "";
      }

      if($this->error != 1)
      {
        $this->special_id = mb_strtolower($this->special_id);

        if(Role::where('special_id', $this->special_id)->count() == 0)
        {
          Role::insert([
            'display_name' => $this->display_name,
            'special_id' => $this->special_id,
            'enabled' => $this->enabled,
            'default' => 0,
            'css_class' => $this->css_class,
            'permissions' => !empty($this->permissions) ? json_encode($this->permissions) : '[""]',
            'created_by' => $this->user->uuid,
            'created_at' => time()
          ]);
  
          return response()->json([
            'error' => 0,
            'message' => __('sentences.role-created')
          ]);
        }
        else
        {
          return response()->json([
            'error' => 2,
            'message' => __('validation.custom.role-exists')
          ]);
        }
      }
      else
      {
        return response()->json([
          'error' => 1,
          'error_display_name' => $this->error_display_name,
          'error_special_id' => $this->error_special_id,
          'error_css_class' => $this->error_css_class,
          'error_permissions' => $this->error_permissions,
          'error_enabled' => $this->error_enabled
        ]);
      }
    }
    else
    {
      return response()->json([
        'error' => 2,
        'message' => __('sentences.no-perm')
      ]);
    }
  }

  /**
   * Update's a role
   * 
   * @param \Request $request
   * 
   * @param $token
   * 
   * @return string
   */

  public function edit_role(Request $request, $token)
  {
    $core = new CoreController;
    $this->user = Auth::user();
  
    $this->token = $token;

    $this->token_name = __('words.special-id');

    if($core->hasPermission('can_create_role') == 1)
    {
      if(!empty($this->token))
      {
        $this->get_role = Role::where('special_id', $this->token);

        if($this->get_role->count() == 1)
        {
          $this->display_name = $request->display_name;
          $this->css_class = $request->css_class;
          $this->permissions = $request->permissions;
          $this->enabled = $request->enabled;

          $this->display_name_name = mb_strtolower(__('words.name'));
          $this->css_class_name = mb_strtolower(__('words.css-class'));
          $this->permissions_name = mb_strtolower(__('words.permission.pl'));
          $this->enabled_name = mb_strtolower(__('words.enabled'));

          $this->error = 0;

          if(empty($this->display_name))
          {
            $this->error = 1;
            $this->error_display_name = __('validation.filled', ['attribute' => $this->display_name_name]);
          }
          elseif(strlen($this->display_name) > 128)
          {
            $this->error = 1;
            $this->error_display_name = __('validation.lt.numeric', ['attribute' => $this->display_name_name, 'value' => 128]);
          }
          elseif(strlen($this->display_name) < 3)
          {
            $this->error = 1;
            $this->error_display_name = __('validation.gt.numeric', ['attribute' => $this->display_name_name, 'value' => 3]);
          }
          elseif(!preg_match('/^[a-zA-Z0-9_ ]+$/', $this->display_name))
          {
            $this->error = 1;
            $this->error_display_name = __('validation.in', ['attribute' => $this->display_name_name]);
          }
          else
          {
            $this->error_display_name = "";
          }

          if(!empty($this->css_class))
          {
            if(strlen($this->css_class) > 128)
            {
              $this->error = 1;
              $this->error_css_class = __('validation.lt.numeric', ['attribute' => $this->css_class_name, 'value' => 128]);
            }
            else if(strlen($this->css_class) < 3)
            {
              $this->error = 1;
              $this->error_css_class = __('validation.gt.numeric', ['attribute' => $this->css_class_name, 'value' => 3]);
            }
            else if(!preg_match('/^[a-zA-Z0-9_\-]+$/', $this->css_class))
            {
              $this->error = 1;
              $this->error_css_class = __('validation.in', ['attribute' => $this->css_class_name]);
            }
            else
            {
              $this->error_css_class = "";
            }
          }
          else
          {
            $this->error_css_class = "";
          }

          if(!empty($this->permissions))
          {
            $this->permission_list = json_decode(Permission::all());

            foreach($this->permission_list as $item)
            {
              if(!in_array($item, $this->permission_list))
              {
                $this->error = 1;
                $this->error_permissions = __('validation.in', ['attribute' => $this->permissions_name]);
              }
              else
              {
                $this->error_permissions = "";
              }
            }
          }
          else
          {
            $this->error_permissions = "";
          }

          if(!in_array($this->enabled, [0, 1]))
          {
            $this->error = 1;
            $this->error_enabled = __('validation.in', ['attribute' => $this->enabled_name]);
          }
          else
          {
            $this->error_enabled = "";
          }

          if($this->error != 1)
          {
            $this->get_role->update([
              'display_name' => $this->display_name,
              'enabled' => $this->enabled,
              'css_class' => $this->css_class,
              'permissions' => !empty($this->permissions) ? json_encode($this->permissions) : '[""]',
              'updated_by' => $this->user->uuid,
              'updated_at' => time()
            ]);
    
            return response()->json([
              'error' => 0,
              'message' => __('sentences.role-updated')
            ]);
          }
          else
          {
            return response()->json([
              'error' => 1,
              'error_display_name' => $this->error_display_name,
              'error_css_class' => $this->error_css_class,
              'error_permissions' => $this->error_permissions,
              'error_enabled' => $this->error_enabled
            ]);
          }
        }
        else
        {
          return response()->json([
            'error' => 2,
            'message' => __('validation.in', ['attribute' => $this->token_name])
          ]);
        }
      }
      else
      {
        return response()->json([
          'error' => 2,
          'message' => __('validation.filled', ['attribute' => $this->token_name])
        ]);
      }
    }
    else
    {
      return response()->json([
        'error' => 2,
        'message' => __('sentences.no-perm')
      ]);
    }
  }

  /**
   * Set Rank as Default
   * 
   * @param $token
   * 
   * @return string
   */

  public function set_role_as_default($token)
  {
    $core = new CoreController;
    $this->user = Auth::user();

    if($core->hasPermission('can_edit_role') == 1)
    {
      $this->token = $token;

      $this->token_name = __('words.special-id');

      $this->error = 0;

      if(empty($this->token))
      {
        $this->error = 1;
        $this->error_message = __('validation.filled', ['attribute' => $this->token_name]);
      }
      else
      {
        $this->get_role = Role::where([['special_id', '=', $this->token], ['default', '=', 0]]);

        if($this->get_role->count() != 1)
        {
          $this->error = 1;
          $this->error_message = __('validation.in', ['attribute' => $this->token_name]);
        }
        else
        {
          $this->error_message = "";
        }
      }

      if($this->error != 1)
      {
        Role::where('default', 1)->update(['default' => 0]);

        $this->get_role->update([
          'default' => 1,
          'updated_by' => $this->user->uuid,
          'updated_at' => time()
        ]);

        return response()->json([
          'error' => 0,
          'message' => __('sentences.role-updated')
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
      return abort(403);
    }
  }

  /**
   * Remove's a role from the database
   * 
   * @param $token
   * 
   * @return string
   */

  public function delete_role($token)
  {
    $core = new CoreController;

    $this->token = $token;

    $this->token_name = __('words.role-id');

    $this->error = 0;

    if($core->hasPermission('can_remove_role') == 1)
    {
      if(empty($this->token))
      {
        $this->error = 1;
        $this->error_message = __('validation.filled', ['attribute' => $this->token_name]);
      }
      else
      {
        $this->get_role = Role::where([['special_id', '=', $this->token], ['default', '!=', 1]]);

        if($this->get_role->count() == 1)
        {
          $this->role = $this->get_role->first();

          $this->user_list = [];

          foreach(User::all() as $item)
          {
            if($item->role == $this->role->special_id)
            {
              $this->user_list[] = $item->uuid;
            }
          }

          if(count($this->user_list) > 0)
          {
            foreach($this->user_list as $item)
            {
              User::where('uuid', $item)->update([
                'role' => Role::where([['role', '=', 1], ['default', '=', 1]])->first()->special_id,
                'updated_at' => time()
              ]);
            }
          }

          $this->error_message = "";
        }
        else
        {
          $this->error = 1;
          $this->error_message = __('validation.in', ['attribute' => $this->token_name]);
        }
      }

      if($this->error != 1)
      {
        $this->get_role->delete();

        return response()->json([
          'error' => 0,
          'message' => __('sentences.role-removed')
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
      return abort(403);
    }
  }
}
