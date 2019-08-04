<?php

namespace App\Http\Middleware;

use Closure;

class CheckRights
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /*if (\Auth::check()) {
            \Auth::logout();
            session()->flash('message_error', 'Доступ закрыт');
            session()->save();
            return back();
        }*/

        return $next($request);
    }
}
