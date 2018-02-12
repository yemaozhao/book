<?php

namespace App\Http\Middleware;

use Closure;
use App\Entity\Member;

class RecordLastActivedTime
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
        $member = $request->session()->get('member', '');
        if($member != '')
        {
            Member::recordLastActivedAt();

        }


        return $next($request);
    }
}
