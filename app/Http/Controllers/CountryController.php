<?php

namespace App\Http\Controllers;

use App\Country;
use CountryState;

class CountryController extends Controller
{
    public function index()
    {
        $countries = CountryState::getCountries();

        $countriesArr = [];

        foreach($countries as $key => $value)
        {
            $country = new Country([
                "code" => $key,
                "name" => $value
            ]);
            array_push($countriesArr, $country);
        }

        return response()->json($countriesArr, 200);
    }
}
