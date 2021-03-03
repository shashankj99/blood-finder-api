<?php

namespace App\Http\Middleware;

use App\Models\Token;
use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Http\Request;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var Auth
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param Auth $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // get the access token
        $accessToken = ($request->bearerToken()) ? $request->bearerToken() : $request->access_token;

        // return 401 error if access token is missing
        if (!$accessToken)
            return response()->json([
                'status' => 401,
                'message' => 'Authorization header parameter is required'
            ], 401);

        // search for the token in the DB
        $token = Token::whereToken($accessToken)->first();

        // return unauthorized error if unable to find the token data
        if (!$token)
            return response()->json([
                'status' => 401,
                'message' => 'Unauthorized'
            ], 401);

        // return the request
        return $next($request);
    }
}
