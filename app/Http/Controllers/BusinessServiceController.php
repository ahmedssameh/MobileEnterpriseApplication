<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\business_service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class BusinessServiceController extends Controller
{
    public function createService(Request $request){

        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'description'=>'required',
            'lang',
            'lat',

        ]);


        if($validator->fails()){
            $errorString = implode("\n", $validator->errors()->all());
            return response()->json(['details'=>$errorString],400)->header('Content-Type', 'application/json');
        }

        $businessService = business_service::create(array_merge($validator->validated(),
        ));

        return response()->json(['message'=> 'business Service is registered',
            'business Service'=> $businessService
        ],
            201
        );


    }
    public function getServices(){
        $data = business_service::all();
        return response()->json($data);
    }


}
