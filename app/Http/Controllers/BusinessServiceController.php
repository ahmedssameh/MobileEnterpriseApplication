<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\business_service;
use Illuminate\Http\Request;

class BusinessServiceController extends Controller
{
    public function getServices(){
        $data = business_service::all();
        return response()->json($data);
    }

}
