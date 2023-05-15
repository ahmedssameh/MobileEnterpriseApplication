<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\business_service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;


class BusinessServiceController extends Controller
{
    public function createService(Request $request){

        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'description'=>'required',


        ]);


        if($validator->fails()){
            $errorString = implode("\n", $validator->errors()->all());
            return response()->json(['details'=>$errorString],400)->header('Content-Type', 'application/json');
        }

        $businessService = Auth::user()->business_service()->create(array_merge($validator->validated(),
        ));

        return response()->json(['message'=> 'business Service is created',
            'business Service'=> $businessService
        ],
            201
        );
    }
    public function getServices(){
        $data = business_service::all();
        return response()->json($data);
    }

    public function favService(Request $request){

        $validator = Validator::make($request->all(),[
            'service_id'=>'required|exists:business_service,id',
        ]);


        if($validator->fails()){
            $errorString = implode("\n", $validator->errors()->all());
            return response()->json(['details'=>$errorString],400)->header('Content-Type', 'application/json');
        }

        $businessService = Auth::user()->fav_service()->create(array_merge($validator->validated(),
        ));

        return response()->json(['message'=> 'business Service is put in favourite',
            'business Service'=> $businessService
        ],
            201
        );

    }

    public function getFavoriteServices()
    {
        $user = Auth::user();

        if (!is_null($user)) {
            $favoriteServices = $user->fav_service()->get();
            return response()->json($favoriteServices);
        } else {
            return response()->json(['error' => 'User not found'], ResponseAlias::HTTP_NOT_FOUND);
        }
    }

    public function getServiceCompany(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'service_id' => 'required|exists:business_service,id',
        ]);


        if ($validator->fails()) {
            $errorString = implode("\n", $validator->errors()->all());
            return response()->json(['details' => $errorString], 400)->header('Content-Type', 'application/json');
        }

        $businessService = business_service::find(array_merge($validator->validated(),
        ));
        $user=$businessService->User;
        if ($businessService) {

            return response()->json(['message' => 'The company profile of this service',
                'Company Profile' =>$user
            ],
                201
            );
        }

        return response()->json(['error' => 'User not found'], ResponseAlias::HTTP_NOT_FOUND);

    }




}
