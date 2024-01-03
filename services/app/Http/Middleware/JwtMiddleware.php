<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtMiddleware
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
        try {
            $user = JWTAuth::parseToken()->authenticate();


        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json([
                    'status' => false,
                    'typemsj' => "1",
                    "message"=>'Token is Invalid: Token mal formado',
                    "Logout"=>true],400);

            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {

                /*try {

                    $oldToken = JWTAuth::getToken();
                    $refreshed = JWTAuth::refresh($oldToken);
                    //$request->headers->set('Authorization',$refreshed,true);
                    //echo 1;

                }catch (JWTException $e) {*/

                return response()->json([
                    'status' => false,
                    'typemsj' => "1",
                    "message"=>'Token cannot be refreshed, Inicie sesión de nuevo',
                    "Logout"=>true,
                    "error" => $e->getMessage()
                ],403);
                //}
            }else{
                return response()->json([
                    'status' => false,
                    'typemsj' => "1",
                    "message"=>'Authorization Token not found: Token no registrado',
                    "Logout"=>true],403);
            }
        }
        return $next($request);
    }
}
