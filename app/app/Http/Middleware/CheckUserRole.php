<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param $role string
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {

        if (!Auth::check()) {
            return redirect('login')->with('not_authenticated', url()->current());
        }
        $user_role = $request->user()->user_role;
        $val = intval($role);
        $page_role = UserRole::getInstance($val);

        if (!UserRole::checkPermission($page_role, $user_role)) {
            return redirect('/home')->with('status', 'Você não tem permissão para ver a pagina que solicitou!');
        }

        return $next($request);
    }
}
