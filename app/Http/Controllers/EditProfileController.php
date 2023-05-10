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

        $validatedData = $request->validate([
            'name' => 'required',
            'contact_person_name' => 'required',
            'contact_person_phone_number' => 'required',
            'company_address' => 'required',
            'company_size' => 'required',
        ]);


        $user->update($validatedData);

        return response()->json(['message'=> 'User is updated',
            'user'=> $user
        ],
            201
        );
    }

}
