<?php

namespace App\Http\Controllers;

use App\MediaProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class MediaProviderController extends Controller
{
    public function addProvider(Request $request)
    {
        //Validation for login
        $validator = Validator::make($request->all(), [
            'provider_key' => 'required',
            'provider_type' => [
                'required',
                Rule::in(['spotify', 'youtube', 'soundcloud']),
            ]
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 422);
        }

        //Check Duplicate id
        $mediaProvider = MediaProvider::
        where('provider_key', $request->input('provider_key'))->first();

        if($mediaProvider){
            return response()->json(['error'=> 'Media provider already connected with another account'], 422);
        }

        //==================//

        $currentUser = auth('api')->user();

        $mediaProvider = MediaProvider::where([
            ['provider_type', $request->input('provider_type')],
            ['user_id', $currentUser['id']]
        ])->first();

        if(!$mediaProvider){
            $mediaProvider = new MediaProvider();
        }

        $mediaProvider->provider_key = $request->input('provider_key');
        $mediaProvider->provider_type = $request->input('provider_type');
        $mediaProvider->img = $request->input('img');
        $mediaProvider->username = $request->input('username');
        $mediaProvider->followers = $request->input('followers');
        $mediaProvider->songs = $request->input('songs');
        $mediaProvider->user_id = $currentUser['id'];

        if($mediaProvider->save()){
            return response()->json(['message'=> 'Media Platform Added'], 200);
        }

        $response = [
            'error' => 'An error occured'
        ];

        return response()->json($response, 404);

    }

    public function index()
    {
        $currentUser = auth('api')->user();

        $mediaProvider = MediaProvider::where('user_id', $currentUser['id'])->get();

        return response()->json($mediaProvider, 200);
    }


}
