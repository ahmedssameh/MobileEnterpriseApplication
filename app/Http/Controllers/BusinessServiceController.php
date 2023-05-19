<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\business_service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Illuminate\Support\Facades\DB;


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
    public function getServices()
    {
        $services = business_service::all();

        $data = [];

        foreach ($services as $service) {
            $user = User::find($service->user_id);

            if ($user) {
                $userName = $user->name;
                $userPhoto = $user->photo;

                $data[] = [
                    'service_id' => $service->id,
                    'service_name' => $service->name,
                    'service_description' => $service->description,
                    'Company_name' => $userName,
                    'Company_photo' => $userPhoto,
                ];
            }
        }

        return response()->json([
            'message' => 'Business Services retrieved successfully',
            'services' => $data
        ], 201);
    }


    public function favService(Request $request){

        $validator = Validator::make($request->all(),[
            'service_id'=>'required|exists:business_service,id',
        ]);


        if($validator->fails()){
            $errorString = implode("\n", $validator->errors()->all());
            return response()->json(['details'=>$errorString],400)->header('Content-Type', 'application/json');
        }

        $serviceId = $validator->validated()['service_id'];

        // Check if the service is already in the user's favorite list
        $isAlreadyFavorited = Auth::user()->fav_service()->where('service_id', $serviceId);

        if ($isAlreadyFavorited) {
            $isAlreadyFavorited->delete();
            return response()->json(['message' => 'This service is not favourite anymore'], 201);
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

        if ($user) {
            $favoriteServices = $user->fav_service()->get();

            $data = [];

            foreach ($favoriteServices as $service) {
                    $userName = $user->name;
                    $userPhoto = $user->photo;

                    $myservice= business_service::find($service->service_id);

                    $data[] = [
                        'service_id' => $myservice->id,
                        'service_name' => $myservice->name,
                        'service_description' => $myservice->description,
                        'Company_name' => $userName,
                        'Company_photo' => $userPhoto,
                    ];
                }


            return response()->json([
                'message' => 'Favourite Business Services retrieved successfully',
                'services' => $data
            ], 201);
        } else {
            return response()->json(['error' => 'User not found'], ResponseAlias::HTTP_NOT_FOUND);
        }
    }

    public function getServiceCompany(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:business_service,id',
        ]);


        if ($validator->fails()) {
            $errorString = implode("\n", $validator->errors()->all());
            return response()->json(['details' => $errorString], 400)->header('Content-Type', 'application/json');
        }

        $businessService = business_service::where($validator->validated())->first();
        //$user=$businessService->user_id;
        if ($businessService) {

            return response()->json(['message' => 'The company profile of this service',
                'Company Profile' =>$businessService->user
            ],
                201
            );
        }

        return response()->json(['error' => 'User not found'], ResponseAlias::HTTP_NOT_FOUND);

    }



    public function getCompanyServices(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:users,id',
        ]);


        if ($validator->fails()) {
            $errorString = implode("\n", $validator->errors()->all());
            return response()->json(['details' => $errorString], 400)->header('Content-Type', 'application/json');
        }

        $user = User::find($validator->validated()['id']);
        //$user=$businessService->user_id;
        if ($user) {

            return response()->json(['message' => 'The services of the company',
                'Services' =>$user->business_service
            ],
                201
            );
        }

        return response()->json(['error' => 'User not found'], ResponseAlias::HTTP_NOT_FOUND);

    }


    public function getAllCompanies(){

        $users = User::all();
        return response()->json(['message' => 'All companies',
            'Companies' =>$users
        ],
            201
        );
    }

    public function calculateDistance(Request $request)
    {
        // Validate request parameters
        $validator = Validator::make($request->all(), [
            'lat' => 'required|numeric',
            'lon' => 'required|numeric',
            'service_id' => 'required|exists:business_service,id',
        ]);

        if ($validator->fails()) {
            $errorString = implode("\n", $validator->errors()->all());
            return response()->json(['details' => $errorString], 400);
        }

        // Get user's latitude and longitude from the request
        $userLat = $request->input('lat');
        $userLon = $request->input('lon');

        // Get the company's latitude and longitude based on the provided service ID
        $serviceId = $request->input('service_id');
        $company = DB::table('business_service')
            ->where('id', $serviceId)
            ->select('user_id')
            ->first();

        if (!$company) {
            return response()->json(['error' => 'Service not found'], 404);
        }
        $user = User::find($company->user_id);

        // Calculate the distance using the Haversine formula
        $distance = $this->calculateDistanceBetweenPoints($userLat, $userLon, $user->lat, $user->lang);

        // Return the distance in the API response
        return response()->json(['distance' => $distance], 200);
    }

// Helper function to calculate distance between two points using the Haversine formula
    private function calculateDistanceBetweenPoints($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Radius of the Earth in kilometers

        $lat1Rad = deg2rad($lat1);
        $lon1Rad = deg2rad($lon1);
        $lat2Rad = deg2rad($lat2);
        $lon2Rad = deg2rad($lon2);

        $deltaLat = $lat2Rad - $lat1Rad;
        $deltaLon = $lon2Rad - $lon1Rad;

        $a = sin($deltaLat / 2) ** 2 + cos($lat1Rad) * cos($lat2Rad) * sin($deltaLon / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;

        return $distance;
    }



}
