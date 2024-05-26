<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\emailverificationcodes;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function logout(Request $request)
    { 
   if (Auth::check()) {
    $accessToken = Auth::user()->token();
    if ($accessToken) {
        $accessToken->revoke();
    }
    }
        return response()->json([
            'message' => "You've successfully logged out"
        ]);
    }
    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required|email|unique:users',
            'name' => 'required|max:255',
          
            'user_name'=>'required|max:255|unique:users',
            'password' => 'required|confirmed',
          

        ]);
        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()], 422);
          //  return $validator->errors();
        }
    
        $data = $request->all();
     
        $data['password'] = bcrypt($request->password);
        $user=  User::create($data);
        $token= $user->createToken("MyApp")->accessToken;

             
        $loginCode= rand(111111, 999999);
        $currentTime = Carbon::now();
        $emailverificationcode = emailverificationcodes::create(
            [ 
             'email'=>$user->email,
            'otp'=>bcrypt($loginCode),
            'userID'=>$user->id,
            'expires_at'=>$currentTime->addMinutes(15)
            ]
            );

        
        $emailverificationcode ->save();
     //   Mail::to($user->email)->send(new VerificationCodeMail($loginCode));
        
     //   event(new Registered($user));
       // $user->sendEmailVerificationNotification();
       
    //   $token = $user->createToken('api_token')->accessToken;
        return response()->json(['user' => $user, "message"=>"Mensaje enviado a $request->email, favor verificar"]);
    }

    public function login(Request $request){
        $validator= Validator::make($request->all(), [
            'email' => 'email|required',
            'password'=>'required'
        ]);
        if($validator->fails()){
            return $validator->errors();
        }
        $data = $request->all();


        if(!auth()->attempt($data)){
            return Response::json(['error_message'=>'Incorrect details, Try again']);
        }
    

        $token = auth()->user()->createToken('api_token')->accessToken;
        return Response::json(['user'=>auth()->user(), 'token'=>$token])->cookie('auth_token', $token, 60, '/', null, true, true); // 60 minutes expiration, HTTP-only, Secure;
    }
}
