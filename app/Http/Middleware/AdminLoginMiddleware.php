<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Illuminate\Support\Facades\Auth;

class AdminLoginMiddleware
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
        try {
            
            $token = JWTAuth::getToken();
            //$api = JWTAuth::getPayload($token)->toArray();
             $user = JWTAuth::toUser($request->bearerToken());
            //dd(  $user );
        }catch (JWTException $e) {
            if($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json(['error' => 'Token Expired', 'status_code' => 401]);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json( ['error' => 'Token invalid.', 'status_code' => 401] );
            }else{
                return response()->json(['error'=>'Token must be required']);
            }
        }
        return $next($request);
    }
}
