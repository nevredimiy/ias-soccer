<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SiteSetting;
use App\Models\City;
use App\Models\District;
use App\Models\Location;
use App\Models\League;

class HomeController extends Controller
{
    public function index()
    {
        $siteSettings = SiteSetting::latest()->first();

        $cities = City::all();

        $phone = $this->formatPhoneNumber($siteSettings->contacts);
        
        return view('home.index', compact('siteSettings', 'phone', 'cities'));
    }

    public function formatPhoneNumber($phone)  
    {  
        // Удаляем префикс +380 и извлекаем код оператора и номер  
        if (strpos($phone, '+380') === 0) {  
            $operatorCode = substr($phone, 3, 3); // Код оператора  
            $number = substr($phone, 6);          // Остальная часть номера  

            // Форматируем номер  
            return "+38({$operatorCode}) {$number[0]}{$number[1]}{$number[2]} {$number[3]}{$number[4]} {$number[5]}{$number[6]}";  
        }  
        return $phone;  
    }  
}
