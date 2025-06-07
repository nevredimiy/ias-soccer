<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stadium;

class StadiumController extends Controller
{
    public function index() 
    {
        $stadiums = Stadium::all();
        return view('pages.stadiums', compact('stadiums'));
    }
}
