<?php

namespace App\Http\Controllers;

use App\State;
use CountryState;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StateController extends Controller
{

    public function index(Request $request)
    {
        //Validation
        $validator = Validator::make($request->all(), [
            'country_code' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 422);
        }

        $states = CountryState::getStates($request->input('country_code'));

        $statesArr = [];

        foreach($states as $key => $value)
        {
            $state = new State([
                "code" => $key,
                "name" => $value
            ]);
            array_push($statesArr, $state);
        }

        return response()->json($statesArr, 200);
    }

}
