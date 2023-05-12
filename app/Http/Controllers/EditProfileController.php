<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Contracts\Providers\Auth;
use App\Models\User;




class EditProfileController extends Controller
{

    public function update(Request $request){

        $user = auth()->user();

        $validatedData = Validator::make($request->all(),[
            'name'=>'required',
            'contact_person_name'=>'required',
            'contact_person_phone_number'=> 'required',
            'company_address'=>'required',
            'company_size'=>'required',
            'photo'=>'required',
        ]);



        if($validatedData->fails()){
            $errorString = implode("\n", $validatedData->errors()->all());
            return response()->json(['details'=>$errorString],400)->header('Content-Type', 'application/json');
        }



        $user->update(array_merge($validatedData->validated()));

        return response()->json(['message'=> 'User is updated',
            'user'=> $user
        ],
            201
        );
    }


    public function changePassword(Request $request)
    {
        $user = auth()->user();

        // Validate the request data
        $validatedData = $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        // Verify the old password
        if (!password_verify($validatedData['old_password'], $user->getAuthPassword())) {
            return response()->json(['message' => 'Invalid old password'], 401);
        }

        // Update the user's password
        $user->password = bcrypt($validatedData['new_password']);
        $user->save();

        return response()->json(['message' => 'Password changed successfully']);
    }

    public function getCompany(){
        $user = auth()->user();

        if($user ==null){
            return response()->json(['status' => 'Unauthorized'],401);
        }

        return response()->json(['message' => 'My profile',
                'user' => $user
            ],
                201
            );


    }


}
