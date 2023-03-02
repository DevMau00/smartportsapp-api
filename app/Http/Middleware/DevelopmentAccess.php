<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DevelopmentAccess
{
    /**
     * Client IPs allowed to access the app.
     * Defaults are loopback IPv4 and IPv6 for use in local development.
     *
     * @var array
     */
    protected $ipWhitelist = [
        '127.0.0.1',
        '::1',
    ];

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (app()->environment() != 'production' && $this->clientNotAllowed()) {
            return abort(403, 'You are not authorized to access this');
        }

        return $next($request);
    }

    /**
     * Checks if current request client is allowed to access the app.
     *
     * @return boolean
     */
    protected function clientNotAllowed()
    {
        $isAllowedIP = in_array(request()->ip(), $this->ipWhitelist);

        return (!$isAllowedIP && auth()->guest()) || ($isAllowedIP && !auth()->guest());
    }
}
