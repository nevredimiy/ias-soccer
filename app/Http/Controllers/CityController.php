<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;

class CityController extends Controller
{
    public function index() 
    {
        $cities = City::all(); 
        
        return view('pages.cities', compact('cities'));
    }
}
