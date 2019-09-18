<?php

namespace App\Http\Middleware;

use Closure;

class Premium
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
        $user = $request->user();
        if ($user->can('premium-access')) {
            return $next($request);
        }
        return redirect('/members/upgrade');
        //abort(404);
    }
}
