<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Contracts\Providers\Auth;
use App\Models\User;




class EditProfileController extends Controller
{

    public function update(Request $request)
    {
        $user = auth()->user();

        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required',
            'contact_person_name' => 'required',
            'contact_person_phone_number' => 'required',
            'company_address' => 'required',
            'company_size' => 'required',
        ]);

        // Update the user's information
        $user->name = $validatedData['name'];
        $user->contact_person_name = $validatedData['contact_person_name'];
        $user->contact_person_phone_number = $validatedData['contact_person_phone_number'];
        $user->company_address = $validatedData['company_address'];
        $user->company_size = $validatedData['company_size'];
        // ...

        // Save the updated user record
        $user->save();

        // Return a response indicating success
        return response()->json(['message' => 'User information updated successfully']);
    }

}
