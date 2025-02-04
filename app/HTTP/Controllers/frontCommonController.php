<?php

namespace App\Http\Controllers\Font;

use App\Http\Controllers\Controller;
use App\Services\CityProvinceService;
use Illuminate\Http\Request;

class frontCommonController extends Controller
{
    //
    protected $cityProvinceService;

    public function __construct(CityProvinceService $cityProvinceService)
    {
        $this->cityProvinceService = $cityProvinceService;
    }
    public function home(){
       $fetchProvince=$this->cityProvinceService->getAllProvincesWithTheirCities(); 
    //    dd($fetchProvince);
        return view ('frontLayout.home', compact('fetchProvince'));
    }
}
