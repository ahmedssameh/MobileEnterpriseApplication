<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Contracts\Providers\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
class AuthController extends Controller
{
    public function _construct(){
        $this->middleware('auth:api',['except'=>['login','register']]);
    }
    public function register(Request $request){

        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'contact_person_name'=>'required',
            'contact_person_phone_number'=> 'required',
            'email'=>'required|string|email|unique:users',
            'company_address'=>'required',
            'company_size'=>'required',
            'password'=>'required|string|confirmed',
        ]);


        if($validator->fails()){
           return response()->json($validator->errors()->toJson(),400);
        }

        $user = User::create(array_merge($validator->validated(),
        ['password'=> bcrypt($request->password)]
        ));

        return response()->json(['message'=> 'User is registered',
                'user'=> $user
        ],
            201
        );


    }

    public function login(Request $request){
        $validator = Validator::make($request->all(),[
            'email'=>'required|email',
            'password'=>'required|string',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(),422);
        }
        if(!$token=auth()->attempt($validator->validated())){
            return response()->json(['status' => 'Unauthorized'],401);
        }
        return $this->createNewToken($token);

    }

    public function createNewToken($token){
        return response()->json([
            'access_token'=>$token,
            'token_type'=>'bearer',
            'user'=>auth()->user()
        ]);

    }
}
