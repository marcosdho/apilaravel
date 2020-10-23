<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use \Firebase\JWT\JWT;


class TokenValid
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
        $token = $request->bearerToken();
        if(!$token){
            return response()->json(['code'=>400,'type'=>'error','message'=>'missing token']);
        }
        try{
            $decoded = JWT::decode($token, env('JWT_KEY'), array('HS256'));
            if($decoded){
                return $next($request);
            }else{
                return response()->json(['code'=>400,'type'=>'error','message'=>'Invalid Token']);
            }
        }catch(\Exception $e){
            return response()->json(['code'=>400,'type'=>'error','message'=>$e->getMessage()]);
        }
    }
}
