<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ChangeLanguage
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle(Request $request, Closure $next)
  {
    // get the selected locale (language) code from session
    if (session()->has('currentLocaleCode')) {
      $locale = session()->get('currentLocaleCode');

      // now, set the selected locale (language) as system locale
      App::setLocale($locale);
    }

    /**
     * if session does not have any locale code,
     * then laravel will set the default locale code from config/app.php file
     * and, then redirect to the next request.
     */
    return $next($request);
  }
}
