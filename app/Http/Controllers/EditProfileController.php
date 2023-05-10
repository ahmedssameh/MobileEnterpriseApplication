<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Contracts\Providers\Auth;
use App\Models\User;

class EditProfileController extends Controller
{
    public function _construct(){
        $this->middleware('auth:api',['except'=>['update']]);
    }
    public function update(Request $request)
    {

        $validatedData = Validator::make($request->all(),[
            'name'=>'required',
            'contact_person_name'=>'required',
            'contact_person_phone_number'=> 'required',
            'company_address'=>'required',
            'company_size'=>'required',
        ]);



        if($validatedData->fails()){
            $errorString = implode("\n", $validatedData->errors()->all());
            return response()->json(['details'=>$errorString],400)->header('Content-Type', 'application/json');
        }

        $user = auth()->user();

        $user->update(array_merge($validatedData->validated()));

        return response()->json(['message'=> 'User is updated',
            'user'=> $user
        ],
            201
        );
    }
}
