<?php

namespace App\Http\Controllers;

use App\Connection;
use App\Studio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;

class StudioController extends Controller
{
    public function index(){
        $currentUser = auth('api')->user();

        $studios = DB::table('studios')
            ->Leftjoin('users', 'users.id', '=', 'studios.user_id')
            ->select( 'studios.id', 'studios.name', 'studios.type', 'studios.address',
                'studios.booking_email',
                'studios.hourly_rate', 'studios.latitude',
                'studios.longitude', 'studios.user_id')
            ->get();

        foreach($studios as $key => $value)
        {
            //Count number of connections
            $numberOfConnections = Connection::where([['sender_id', '=', $studios[$key]->user_id], ['status', '=', '1']])->
            orWhere([['receiver_id', '=', $studios[$key]->user_id], ['status', '=', '1']])->count();

            $studios[$key]->numberOfConnections = $numberOfConnections;

            //Check user connection status
            $connection = DB::table('connections')
                ->where([['connections.sender_id', '=', $currentUser['id']], ['connections.receiver_id', '=', $studios[$key]->user_id]])
                ->orWhere([['connections.receiver_id', '=', $currentUser['id']], ['connections.sender_id', '=', $studios[$key]->user_id]])
                ->first();

            if($connection){

                if($connection->status == 0){
                    $studios[$key]->connection = "Pending";
                }else if($connection->status == 1){
                    $studios[$key]->connection = "Connected";
                }

            }else{
                $studios[$key]->connection = "Not connected";
            }

            //Get Studio Images
            $images = DB::table('studio_images')
                ->select( 'studio_images.id', 'studio_images.image')
                ->where('studio_images.studio_id', '=', $studios[$key]->id)
                ->get();

            foreach($images as $key1 => $value1)
            {
                //Format variable
                $images[$key1]->image = URL::to('img_mobile/studios/' . $images[$key1]->image);
            }

            $studios[$key]->images = $images;

        }

        return response()->json($studios, 200);
    }

    public function me(){

        $currentUser = auth('api')->user();

        $studio = DB::table('studios')
            ->Leftjoin('users', 'users.id', '=', 'studios.user_id')
            ->select( 'studios.id', 'studios.name', 'studios.type', 'studios.address',
                'studios.booking_email',
                'studios.hourly_rate', 'studios.latitude',
                'studios.longitude', 'studios.user_id')
            ->where('studios.user_id', '=', $currentUser['id'])
            ->first();

        //Check if profile isn't exist
        if($studio == null){
            $response = [
                'msg' => 'Studio profile not found',
            ];

            return response()->json($response, 404);
        }

        //Count number of connections
        $numberOfConnections = Connection::
        where([['sender_id', '=', $currentUser['id']], ['status', '=', '1']])->
        orWhere([['receiver_id', '=', $currentUser['id']], ['status', '=', '1']])->count();

        $studio->numberOfConnections = $numberOfConnections;

        //Count number of creatives
        /*$numberOfCreatives = DB::table('manager_creative')
            ->where([
                ['manager_id', '=', $currentUser['id']],
                ['status', '=', 1]
            ])
            ->count();

        $manager->numberOfCreatives = $numberOfCreatives;*/

//        $manager->img = URL::to('img_mobile/managers/' . $manager->img);

        //Get Studio Images
        $images = DB::table('studio_images')
            ->select( 'studio_images.id', 'studio_images.image')
            ->where('studio_images.studio_id', '=', $studio->id)
            ->get();

        foreach($images as $key => $value)
        {
            //Format variable
            $images[$key]->image = URL::to('img_mobile/studios/' . $images[$key]->image);
        }

        $studio->images = $images;

        return response()->json($studio, 200);
    }

    public function getOtherStudioProfile($id){

        $currentUser = auth('api')->user();

        $studio = DB::table('studios')
            ->Leftjoin('users', 'users.id', '=', 'studios.user_id')
            ->select( 'studios.id', 'studios.name', 'studios.type', 'studios.address',
                'studios.booking_email',
                'studios.hourly_rate', 'studios.latitude',
                'studios.longitude', 'studios.user_id')
            ->where('studios.user_id', '=', $id)
            ->first();

        //Check if profile isn't exist
        if($studio == null){
            $response = [
                'msg' => 'Studio profile not found',
            ];

            return response()->json($response, 404);
        }

        //Count number of connections
        $numberOfConnections = Connection::
        where([['sender_id', '=', $id], ['status', '=', '1']])->
        orWhere([['receiver_id', '=', $id], ['status', '=', '1']])->count();

        $studio->numberOfConnections = $numberOfConnections;

        //Count number of creatives
        /*$numberOfCreatives = DB::table('manager_creative')
            ->where([
                ['manager_id', '=', $currentUser['id']],
                ['status', '=', 1]
            ])
            ->count();

        $manager->numberOfCreatives = $numberOfCreatives;*/

//        $manager->img = URL::to('img_mobile/managers/' . $manager->img);

        //Get Studio Images
        $images = DB::table('studio_images')
            ->select( 'studio_images.id', 'studio_images.image')
            ->where('studio_images.studio_id', '=', $studio->id)
            ->get();

        foreach($images as $key => $value)
        {
            //Format variable
            $images[$key]->image = URL::to('img_mobile/studios/' . $images[$key]->image);
        }

        $studio->images = $images;

        //Check user connection status
        $connection = DB::table('connections')
            ->where([['connections.sender_id', '=', $currentUser['id']], ['connections.receiver_id', '=', $studio->user_id]])
            ->orWhere([['connections.receiver_id', '=', $currentUser['id']], ['connections.sender_id', '=', $studio->user_id]])
            ->first();

        if($connection){

            if($connection->status == 0){
                $studio->connection = "Pending";
            }else if($connection->status == 1){
                $studio->connection = "Connected";
            }

        }else{
            $studio->connection = "Not connected";
        }

        return response()->json($studio, 200);
    }

    public function searchStudioByName(Request $request){

        $validator = Validator::make($request->all(), [
            'query' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 422);
        }

        $searchQuery = $request->input('query');

        //Check user connection status
        $studios = DB::table('studios')
            ->select('studios.id', 'studios.name', 'studios.user_id')
            ->where('studios.name', 'LIKE', '%' . $searchQuery . '%')
            ->get();

        foreach($studios as $key => $value)
        {
            //Get Studio Images
            $images = DB::table('studio_images')
                ->select( 'studio_images.id', 'studio_images.image')
                ->where('studio_images.studio_id', '=', $studios[$key]->id)
                ->get();

            foreach($images as $key1 => $value1)
            {
                //Format variable
                $images[$key1]->image = URL::to('img_mobile/studios/' . $images[$key1]->image);
            }

            $studios[$key]->images = $images;

        }

        return response()->json($studios, 200);
    }
}
