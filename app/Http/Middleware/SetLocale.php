<?php
namespace App\Http\Middleware;

use Illuminate\Support\Facades\Cookie;
use Closure;
use App\Locale;

class SetLocale
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    $this->cookie_name = 'site_locale';

    $this->cookie = mb_strtolower(Cookie::get($this->cookie_name));

    if(!empty($this->cookie))
    {
      $this->get_locales = Locale::where('enabled', 1)->get();

      foreach($this->get_locales as $item)
      {
        $this->locale_list[] = $item->code;
      }

      if(in_array($this->cookie, $this->locale_list))
      {
        Cookie::queue(Cookie::forever($this->cookie_name, $this->cookie));
      }
      else
      {
        $this->default_locale = Locale::where([['default', '=', 1], ['enabled', '=', 1]])->first();

        Cookie::queue(Cookie::forever($this->cookie_name, $this->default_locale->code));
      }
    }
    else
    {
      $this->default_locale = Locale::where([['default', '=', 1], ['enabled', '=', 1]])->first();

      Cookie::queue(Cookie::forever($this->cookie_name, $this->default_locale->code));
    }

    return $next($request);
  }
}