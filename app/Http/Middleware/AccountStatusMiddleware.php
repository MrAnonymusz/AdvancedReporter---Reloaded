<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;

class AccountStatusMiddleware
{
  private $path;

  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
  */
    
  public function handle($request, Closure $next)
  {
    if(Auth::check())
    {
      $this->user = Auth::user();

      if($this->user->is_active == 1)
      {
        return $next($request);
      }
      else
      {
        Auth::logout();
      }
    }
    else
    {
      return $next($request);
    }
  }
}
