<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class EditProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = auth()->user();

        $validatedData = Validator::make($request->all(),[
            'name'=>'required',
            'contact_person_name'=>'required',
            'contact_person_phone_number'=> 'required',
            'email'=>'required|string|email|unique:users',
            'company_address'=>'required',
            'company_size'=>'required',
            'password'=>'required|string|confirmed',
        ]);

        unset($validatedData['email']);
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
}
