<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserApi;
use \Firebase\JWT\JWT;

class LoginController extends Controller
{
    //

    public function index(Request $request){
        $userGet = $request->user??false;
        $passGet = $request->pass??false;

        if(!$userGet || !$passGet){
            return response()->json(['code'=>400,'type'=>'error','message'=>'wron user data']);
        }

        $user = UserApi::where('email',$userGet)->first();

        if($user){
            if(password_verify($passGet,$user->password)){
                $token = array(
                    'iat'   => time(),
                    'exp'   => time() + (60*60*4),
                    'data'  =>  [
                        'user_data' => $user
                    ]
                );
                $encode =  JWT::encode($token, env('JWT_KEY'));
                return response()->json(['code'=>200,'type'=>'success','message'=>'Login Correct',' token'=>$encode]);
            }else{
                return response()->json(['code'=>400,'type'=>'error','message'=>'User data is wron']);
            }
        }else{
            return response()->json(['code'=>400,'type'=>'error','message'=>'User data is wron']);
        }
    }
}
