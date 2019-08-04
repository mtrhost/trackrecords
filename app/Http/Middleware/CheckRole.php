<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole
{
    /**
     * Обработка входящего запроса.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string $role
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        if (!\Auth::check() || (!\Auth::user()->hasRole($role) && !\Auth::user()->isAdmin())) {
            session()->flash('message_error', 'Данное действие недоступно для Вашей роли пользователя');
            session()->save();
            return back()->with('message_error', 'Данное действие недоступно для Вашей роли пользователя');
        }

        return $next($request);
    }

}