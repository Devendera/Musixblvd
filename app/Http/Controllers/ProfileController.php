<?php

namespace App\Http\Controllers;

use App\Connection;
use App\Creative;
use App\Manager;
use App\Studio;
use App\User;
use App\UserPlatforms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpKernel\Profiler\Profile;

class ProfileController extends Controller
{

    //Create Profile

    public function createCreative(Request $request)
    {

        $currentUser = auth('api')->user();

        if($currentUser['has_profile'] == true){
            $response = [
                'error' => 'User already has a profile',
            ];

            return response()->json($response, 401);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'img' => 'required',
            'website' => 'required',
            'stage' => 'required',
            'gender' => [
                'required',
                Rule::in(['Male', 'Female']),
            ],
            'type' => 'required',
            'pro' => 'required',
            'craft' => 'required',
            'genre' => 'required',
            'influencers' => 'required',
            'status' => 'required',
//            'platforms' => 'required|array|min:1',
            'platforms.*.id' => 'exists:platforms,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 422);
        }

        $original_img = $request->file('img');
        $extension = $request->file('img')->getClientOriginalExtension();

        $newImgName =  time(). rand() . '.' . $extension;

        $mobileImgPath = public_path("img/users/" . $newImgName);

        $compressedImageMobile = Image::make($original_img)->resize(200, 200);
        $compressedImageMobile->save($mobileImgPath);

        $creative = new Creative();

        $creative['user_id'] = $currentUser['id'];
        $currentUser['name'] = $request->input('name');
        $currentUser['img'] = $newImgName;
        $creative['website'] = $request->input('website');
        $creative['stage'] = $request->input('stage');
        $creative['gender'] = $request->input('gender');
        $creative['type'] = $request->input('type');
        $creative['pro'] = $request->input('pro');
        $creative['craft'] = $request->input('craft');
        $creative['secondary_craft'] = $request->input('secondary_craft');
        $creative['genre'] = $request->input('genre');
        $creative['secondary_genre'] = $request->input('secondary_genre');
        $creative['influencers'] = $request->input('influencers');
        $creative['social_media_links'] = $request->input('social_media_links');
        $creative['status'] = $request->input('status');
//        $creative['platforms'] = $request->input('platforms');

        $currentUser['has_profile'] = true;

        if($creative->save() && $currentUser->save()) {

            //Add Platforms
            if($request->has('platforms')){

                $platforms = $request->input('platforms');

                foreach($platforms as $key => $value)
                {

                    DB::table('user_platforms')
                        ->insert(['user_id' => $currentUser['id'],
                            'platform_id' => $platforms[$key]['id'],
                            'url' => $platforms[$key]['url'],
                            'created_at' => now(),
                            'updated_at' => now()]);
                }

            }

            $response = [
                'message' => 'Profile Created',
            ];

            return response()->json($response, 201);
        }

        $response = [
            'error' => 'An error occurred',
        ];

        return response()->json($response, 404);
    }

    public function createManager(Request $request)
    {

        $currentUser = auth('api')->user();

        if($currentUser['has_profile'] == true){
            $response = [
                'error' => 'User already has a profile',
            ];

            return response()->json($response, 401);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'img' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 422);
        }

        $original_img = $request->file('img');
        $extension = $request->file('img')->getClientOriginalExtension();

        $newImgName =  time(). rand() . '.' . $extension;

        $mobileImgPath = public_path("img/users//" . $newImgName);

        $compressedImageMobile = Image::make($original_img)->resize(200, 200);
        $compressedImageMobile->save($mobileImgPath);

        $manager = new Manager();

        $manager['user_id'] = $currentUser['id'];
        $currentUser['name'] = $request->input('name');
        $manager['management_company'] = $request->input('management_company');
        $currentUser['img'] = $newImgName;
        $manager['facebook'] = $request->input('facebook');
        $manager['instagram'] = $request->input('instagram');
        $manager['twitter'] = $request->input('twitter');
        $currentUser['has_profile'] = true;

        if($manager->save() && $currentUser->save()) {

            $response = [
                'message' => 'Profile Created',
            ];

            return response()->json($response, 201);
        }

        $response = [
            'error' => 'An error occurred',
        ];

        return response()->json($response, 404);
    }

    public function createStudio(Request $request)
    {

        $currentUser = auth('api')->user();

        if($currentUser['has_profile'] == true){
            $response = [
                'error' => 'User already has a profile',
            ];

            return response()->json($response, 401);
        }

        $validator = Validator::make($request->all(), [
                'name' => 'required',
                'address' => 'required',
                'booking_email' => 'required',
                'hourly_rate' => 'required',
                'pro' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
                'type' => ['required', Rule::in(['residential', 'commercial']),],
                'images' => 'required|array|min:1'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 422);
        }

        $currentUser = auth('api')->user();

        $studio = new Studio();
        $currentUser['name'] = $request->input('name');
        $studio['address'] = $request->input('address');
        $studio['booking_email'] = $request->input('booking_email');
        $studio['hourly_rate'] = $request->input('hourly_rate');
        $studio['pro'] = $request->input('pro');
        $studio['latitude'] = $request->input('latitude');
        $studio['longitude'] = $request->input('longitude');
        $studio['type'] = $request->input('type');
        $studio['user_id'] = $currentUser['id'];

        $currentUser['has_profile'] = true;

        if($studio->save()){

            $images = $request->file('images');

            foreach($images as $key => $image){

                $newImgName =  time(). rand() . '.' . $image->getClientOriginalExtension();

                $mobileImgPath = public_path("img/users/" . $newImgName);

                $compressedImageMobile = Image::make($image)->resize(800, 800);
                $compressedImageMobile->save($mobileImgPath);

                DB::table('studio_images')->insert([
                    ['image' => $newImgName, 'studio_id' => $studio['id'],
                        'created_at' => now(), 'updated_at' => now()]
                ]);

                if($key == 0){
                    $currentUser['img'] = $newImgName;
                    $currentUser->save();
                }
            }



            return response()->json(['msg' => 'Profile Created'], 201);
        }

        $response = [
            'error' => __('messages.server_error')
        ];

        return response()->json($response, 500);
    }

    //Update Profiles

    public function updateCreative(Request $request)
    {

        //-----------------Validation--------------------//

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'website' => 'required',
            'stage' => 'required',
            'type' => 'required',
            'craft' => 'required',
            'genre' => 'required',
            'influencers' => 'required',
            'status' => 'required',
            'platforms.*.id' => 'exists:platforms,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 422);
        }

        //-------------------------------------//
        $currentUser = auth('api')->user();

        $profile = Creative::where('user_id', $currentUser['id'])->first();

        if($request->has('img')){

            //------Delete Old Image------//
            $oldImgPathMobile = public_path("img/users/". $profile['img']);

            if(File::exists($oldImgPathMobile)) {
                File::delete($oldImgPathMobile);
            }

            //-----Create New Image-----//
            $original_img = $request->file('img');
            $extension = $request->file('img')->getClientOriginalExtension();

            $newImgName =  time(). rand() . '.' . $extension;

            $mobileImgPath = public_path("img/users/" . $newImgName);

            $compressedImageMobile = Image::make($original_img)->resize(200, 200);
            $compressedImageMobile->save($mobileImgPath);

            $currentUser['img'] = $newImgName;
        }

        $currentUser['name'] = $request->input('name');
        $profile['website'] = $request->input('website');
        $profile['stage'] = $request->input('stage');
        $profile['gender'] = $request->input('gender');
        $profile['type'] = $request->input('type');
//        $profile['city'] = $request->input('city');
//        $profile['state'] = $request->input('state');
        $profile['craft'] = $request->input('craft');
        $profile['secondary_craft'] = $request->input('secondary_craft');
        $profile['genre'] = $request->input('genre');
        $profile['secondary_genre'] = $request->input('secondary_genre');
        $profile['influencers'] = $request->input('influencers');
        $profile['social_media_links'] = $request->input('social_media_links');
        $profile['status'] = $request->input('status');

        if($profile->save() && $currentUser->save()) {

            //Delete Old Platforms
            UserPlatforms::where('user_id', $currentUser['id'])->delete();

            //Add Platforms
            $platforms = $request->input('platforms');

            foreach($platforms as $key => $value)
            {

                DB::table('user_platforms')
                    ->insert(['user_id' => $currentUser['id'],
                        'platform_id' => $platforms[$key]['id'],
                        'url' => $platforms[$key]['url'],
                        'created_at' => now(),
                        'updated_at' => now()]);
            }

            $response = [
                'message' => 'Profile Updated'
            ];

            return response()->json($response, 200);
        }

        $response = [
            'error' => 'An error occurred',
        ];

        return response()->json($response, 404);
    }

    //Get Profile
    public function getMyProfile(){

        $currentUser = auth('api')->user();

        $user = null;

        switch ($currentUser['type']){

            case 'creative':{
                $user = User::where('id', $currentUser['id'])
                    ->with('creative', 'projects', 'platforms')
                    ->withCount('connections')
                    ->first();

                $unique = $user['projects']->unique('id');
                unset($user['projects']);
                $user['projects_count'] = count($unique);
                $user['projects'] = $unique->values()->all();

                $managers = DB::table('connections')
                    ->select('users.id', 'users.name', 'users.img')
                    ->Rightjoin('users', 'connections.connected_user_id', '=', 'users.id')
                    ->where([
                        ['connections.user_id', '=', $currentUser['id']],
                        ['connections.is_approved', '=', '1'],
                        ['connections.type', '=', 'management']])
                    ->get();

                $user['creative']['managers'] = $managers;

                foreach($user['creative']['managers'] as $key => $value)
                {
                    //Format variable
                    $user['creative']['managers'][$key]->img = URL::to('img/users/' . $user['creative']['managers'][$key]->img);
                }
            }
            break;

            case 'manager':{

                $user = User::where('id', $currentUser['id'])
                    ->with('manager')
                    ->withCount('connections')
                    ->first();

                $creatives = DB::table('connections')
                    ->select('users.id', 'users.name', 'users.img')
                    ->Rightjoin('users', 'connections.connected_user_id', '=', 'users.id')
                    ->where([
                        ['connections.user_id', '=', $currentUser['id']],
                        ['connections.is_approved', '=', '1'],
                        ['connections.type', '=', 'management']])
                    ->get();

                $user['manager']['creatives'] = $creatives;

                foreach($user['manager']['creatives'] as $key => $value)
                {
                    //Format variable
                    $user['manager']['creatives'][$key]->img = URL::to('img/users/' . $user['manager']['creatives'][$key]->img);
                }

            }
            break;

            case 'studio':{
                $user = User::where('id', $currentUser['id'])
                    ->with('studio', 'projects')
                    ->withCount('connections')
                    ->first();

                $unique = $user['projects']->unique('id');
                unset($user['projects']);
                $user['projects_count'] = count($unique);
                $user['projects'] = $unique->values()->all();
            }
            break;

        }

        //Check if profile isn't exist
        if($user == null){
            $response = [
                'msg' => 'Profile not found',
            ];

            return response()->json($response, 404);
        }

        return response()->json($user, 200);
    }

    public function getUserProfile(Request $request){

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 422);
        }

        $user = User::find($request->input('user_id'));

        $currentUser = auth('api')->user();

        switch ($user['type']){

            case 'creative':{
                $user = User::where('id', $request->input('user_id'))
                    ->with('creative', 'projects', 'platforms')
                    ->withCount('connections')
                    ->first();

                $unique = $user['projects']->unique('id');
                unset($user['projects']);
                $user['projects_count'] = count($unique);
                $user['projects'] = $unique->values()->all();

                $managers = DB::table('connections')
                    ->select('users.id', 'users.name', 'users.img')
                    ->Rightjoin('users', 'connections.connected_user_id', '=', 'users.id')
                    ->where([
                        ['connections.user_id', '=', $user['id']],
                        ['connections.is_approved', '=', '1'],
                        ['connections.type', '=', 'management']])
                    ->get();

                $user['creative']['managers'] = $managers;

                foreach($user['creative']['managers'] as $key => $value)
                {
                    //Format variable
                    $user['creative']['managers'][$key]->img = URL::to('img/users/' . $user['creative']['managers'][$key]->img);
                }
            }
                break;

            case 'manager':{

                $user = User::where('id', $user['id'])
                    ->with('manager')
                    ->withCount('connections')
                    ->first();

                $creatives = DB::table('connections')
                    ->select('users.id', 'users.name', 'users.img')
                    ->Leftjoin('users', 'connections.connected_user_id', '=', 'users.id')
                    ->where([
                        ['connections.user_id', '=', $user['id']],
                        ['connections.is_approved', '=', true],
                        ['connections.type', '=', 'management']])
                    ->get();

                $user['manager']['creatives'] = $creatives;

                foreach($user['manager']['creatives'] as $key => $value)
                {
                    //Format variable
                    $user['manager']['creatives'][$key]->img = URL::to('img/users/' . $user['manager']['creatives'][$key]->img);
                }

                //Check if he is my manager
                $connection = Connection::where([
                    ['type', 'management'],
                    ['user_id', $currentUser['id']],
                    ['connected_user_id', $user['id']]
                ])->orWhere([
                    ['type', 'management'],
                    ['user_id', $user['id']],
                    ['connected_user_id', $currentUser['id']]
                ])->first();

                if($connection){

                    if($connection['is_approved'] == true){
                        $user['management_status'] = "Connected";
                    }else{
                        $user['management_status'] = "Pending";
                    }

                }else{
                    $user['management_status'] = "Not connected";
                }

            }
                break;

            case 'studio':{

                $user = User::where('id', $request->input('user_id'))
                    ->with('studio', 'projects')
                    ->withCount('connections')
                    ->first();

                $unique = $user['projects']->unique('id');
                unset($user['projects']);
                $user['projects_count'] = count($unique);
                $user['projects'] = $unique->values()->all();
            }
                break;

        }

        $connection = Connection::where([
            ['connected_user_id', $user['id']],
            ['user_id', $currentUser['id']],
            ['type', 'connection']
        ])->first();

        if($connection){

            if($connection['is_approved']){
                $user['connection'] = "Connected";
            }else{
                $user['connection'] = "Pending";
            }

        }else{
            $user['connection'] = "Not connected";
        }

        //Check if profile isn't exist
        if($user == null){
            $response = [
                'msg' => 'Profile not found',
            ];

            return response()->json($response, 404);
        }

        return response()->json($user, 200);
    }

    public function searchByName(Request $request){

        $validator = Validator::make($request->all(), [
            'query' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 422);
        }

        $currentUser = auth('api')->user();
        $searchQuery = $request->input('query');

        //Check user connection status
        $profiles = DB::table('profiles')
            ->select('profiles.first_name', 'profiles.last_name', 'profiles.img', 'profiles.user_id')
            ->where('profiles.first_name', 'LIKE', '%' . $searchQuery . '%')
            ->orWhere('profiles.last_name', 'LIKE', '%' . $searchQuery . '%')
            ->get();

        foreach($profiles as $key => $value){
            $profiles[$key]->img = URL::to('img_mobile/profiles/' . $profiles[$key]->img);
        }

        return response()->json($profiles, 200);
    }

    public function searchUsersByName(Request $request){

        $validator = Validator::make($request->all(), [
            'query' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 422);
        }

        $currentUser = auth('api')->user();
        $searchQuery = $request->input('query');

        //Check user connection status
        $profiles = DB::table('profiles')
            ->Leftjoin('users', 'users.id', '=', 'profiles.user_id')
            ->select('profiles.user_id', 'profiles.id as profile_id', 'profiles.first_name', 'profiles.last_name',
                'users.email', 'profiles.website', 'profiles.img', 'profiles.state', 'profiles.city',
                'profiles.gender', 'profiles.type', 'profiles.stage', 'profiles.craft', 'profiles.genre',
                'profiles.status', 'profiles.influencers')
            ->where([['profiles.user_id', '!=', $currentUser['id']], ['profiles.first_name', 'LIKE', '%' . $searchQuery . '%']])
            ->orWhere([['profiles.user_id', '!=', $currentUser['id']], ['profiles.last_name', 'LIKE', '%' . $searchQuery . '%']])
            ->get();

        foreach($profiles as $key => $value)
        {
            //Count number of connections
            $numberOfConnections = Connection::where([['sender_id', '=', $profiles[$key]->user_id], ['status', '=', '1']])->
            orWhere([['receiver_id', '=', $profiles[$key]->user_id], ['status', '=', '1']])->count();

            $profiles[$key]->numberOfConnections = $numberOfConnections;

            //Count number of projects
            $numberOfProjects = DB::table('user_project')
                ->where([['user_id', '=', $profiles[$key]->user_id], ['status', '=', 1]])
                ->count();

            $profiles[$key]->numberOfProjects = $numberOfProjects;

            //Format variable
            $profiles[$key]->img = URL::to('img_mobile/profiles/' . $profiles[$key]->img);

            //Check user connection status
            $connection = DB::table('connections')
                ->where([['connections.sender_id', '=', $currentUser['id']], ['connections.receiver_id', '=', $profiles[$key]->user_id]])
                ->orWhere([['connections.receiver_id', '=', $currentUser['id']], ['connections.sender_id', '=', $profiles[$key]->user_id]])
                ->first();

            if($connection){

                if($connection->status == 0){
                    $profiles[$key]->connection = "Pending";
                }else if($connection->status == 1){
                    $profiles[$key]->connection = "Connected";
                }

            }else{
                $profiles[$key]->connection = "Not connected";
            }
        }

        return response()->json($profiles, 200);
    }
}
