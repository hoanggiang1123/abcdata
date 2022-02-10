<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermision
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

        //return response(['action' => $request->route()->getAction()['controller']]);
        // return $next($request);

        $controller = $request->route()->getAction()['controller'];

        list ($control, $method) = explode('@', $controller);

        $control = str_replace("App\\Http\\Controllers\\Admin\\", "", $control);

        $checkPemision =  $control. '@' .$method;

        $roles = $request->user()->roles;

        foreach ($roles as $role) {

            $permisions = $role->permisions;

            if ($permisions->contains('controller', $checkPemision)) {
                return $next($request);
            }
        }

        return response(['message' => 'Unauthorized'], 401);

    }
}
