<?php

namespace Quantum\base\Http\Middleware;

use Closure;

class Firewall
{
    /**
     * @var \Quantum\base\Services\Firewall
     */
    protected $fw;

    /**
     * Create a new middleware instance
     *
     * @param \Quantum\base\Services\FirewallService $fw
     */
    function __construct(\Quantum\base\Services\FirewallService  $fw)
    {
        $this->fw = $fw;
    }


    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $this->fw->init();
        if($this->fw->is_banned())
        {
            return \Response::view('errors.403', array(), 403);
        }

        return $next($request);
    }
}
