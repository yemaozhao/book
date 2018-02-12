<?php

namespace App\Http\Middleware;

use Closure;
use ChromePhp;

class CheckLogin
{
    /**
     * Run the request filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $member = $request->session()->get('member', '');
        if($member == '') {
          $return_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
          // $return_url = $_SERVER['HTTP_REFERER'];
          ChromePhp::log('return_url:');
          ChromePhp::log($return_url);
          return redirect('/login?return_url=' . urlencode($return_url));
        }

        return $next($request);
    }

}
