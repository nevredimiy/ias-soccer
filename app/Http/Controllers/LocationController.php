<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\District;
use App\Models\Location;
use App\Models\League;
use Illuminate\Http\Request;

class LocationController extends Controller
{

    public function getDistricts(Request $request)
    {
        $districts = District::where('city_id', $request->city_id)->get();
        return response()->json($districts);
    }

    public function getLocations(Request $request)
    {
        $locations = Location::where('district_id', $request->district_id)->get();
        return response()->json($locations);
    }

    public function getLeagues(Request $request)
    {
        $leagues = League::where('location_id', $request->location_id)->get();
        return response()->json($leagues);
    }
}
