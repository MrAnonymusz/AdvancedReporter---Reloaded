<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CoreController;
use Illuminate\Http\Request;
use App\Setting;

class SiteSettingsController extends Controller
{
  private $settings;

  /**
   * Update's the site settings
   * 
   * @param \Request $request
   * 
   * @return string
   */

  public function action(Request $request)
  {
    $core = new CoreController;

    if($core->hasPermission('can_update_site_settings'))
    {
      $this->settings = Setting::all();

      foreach($this->settings as $item)
      {
        if($item->type == "list")
        {
          if(!empty($request->{$item->special_id}))
          {
            $this->list_item = [];

            foreach(explode(',', $request->{$item->special_id}) as $sitem)
            {
              $this->list_item[] = $sitem;
            }

            Setting::where('special_id', $item->special_id)->update([
              'value' => json_encode($this->list_item),
              'updated_at' => $request->{$item->special_id} != $item->value ? time() : NULL
            ]);
          }
          else
          {
            Setting::where('special_id', $item->special_id)->update([
              'value' => $request->{$item->special_id},
              'updated_at' => $request->{$item->special_id} != $item->value ? time() : NULL
            ]);
          }
        }
        else
        {
          Setting::where('special_id', $item->special_id)->update([
            'value' => $request->{$item->special_id},
            'updated_at' => $request->{$item->special_id} != $item->value ? time() : NULL
          ]);
        }
      }

      return response()->json([
        'error' => 0,
        'message' => __('sentences.s-setting-success')
      ]);
    }
    else
    {
      return response()->json([
        'error' => 2,
        'message' => __('sentences.no-perm')
      ]);
    }
  }
}
