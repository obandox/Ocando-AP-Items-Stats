<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class Authenticate
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
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
        $headers = apache_request_headers();

        if(!isset($headers['Authorization']))
            $headers['Authorization'] = 'none';
/*
        if ($this->auth->guest() && $headers['Authorization'] != 'secret_ocando') {

            return response('Unauthorized.', 401);

        }
*/
        return $next($request);
    }
}
