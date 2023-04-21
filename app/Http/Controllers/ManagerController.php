<?php

namespace App\Http\Controllers;


use App\Connection;
use App\Manager;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class ManagerController extends Controller
{
    public function index(){
        $currentUser = auth('api')->user();

        $managers = DB::table('managers')
            ->Leftjoin('users', 'users.id', '=', 'managers.user_id')
            ->select( 'managers.user_id', 'managers.id as manager_id',
                'managers.img', 'managers.name', 'managers.management_company',
                'managers.facebook', 'managers.instagram', 'managers.twitter')
            ->where('managers.user_id', '!=', $currentUser['id'])
            ->paginate(30);

        foreach($managers as $key => $value)
        {
            //Count number of connections
            $numberOfConnections = Connection::where([['sender_id', '=', $managers[$key]->user_id], ['status', '=', '1']])->
            orWhere([['receiver_id', '=', $managers[$key]->user_id], ['status', '=', '1']])->count();

            $managers[$key]->numberOfConnections = $numberOfConnections;

            //Count number of creatives
            $numberOfCreatives = DB::table('manager_creative')
                ->where([
                    ['manager_id', '=', $managers[$key]->user_id],
                    ['status', '=', 1]
                ])
                ->count();

            $managers[$key]->numberOfCreatives = $numberOfCreatives;

            //Check user connection status
            $connection = DB::table('connections')
                ->where([['connections.sender_id', '=', $currentUser['id']], ['connections.receiver_id', '=', $managers[$key]->user_id]])
                ->orWhere([['connections.receiver_id', '=', $currentUser['id']], ['connections.sender_id', '=', $managers[$key]->user_id]])
                ->first();

            if($connection){

                if($connection->status == 0){
                    $managers[$key]->connection = "Pending";
                }else if($connection->status == 1){
                    $managers[$key]->connection = "Connected";
                }

            }else{
                $managers[$key]->connection = "Not connected";
            }

            //Format variable
            $managers[$key]->img = URL::to('img_mobile/managers/' . $managers[$key]->img);
        }

        return response()->json($managers, 200);
    }

    public function me(){

        $currentUser = auth('api')->user();

        $manager = DB::table('managers')
            ->Leftjoin('users', 'users.id', '=', 'managers.user_id')
            ->select( 'managers.name', 'managers.img', 'users.email',
                'managers.management_company',
                'managers.facebook', 'managers.instagram',
                'managers.twitter')
            ->where('managers.user_id', '=', $currentUser['id'])
            ->first();

        //Check if profile isn't exist
        if($manager == null){
            $response = [
                'msg' => 'Manager profile not found',
            ];

            return response()->json($response, 404);
        }

        //Count number of connections
        $numberOfConnections = Connection::
        where([['sender_id', '=', $currentUser['id']], ['status', '=', '1']])->
        orWhere([['receiver_id', '=', $currentUser['id']], ['status', '=', '1']])->count();

        $manager->numberOfConnections = $numberOfConnections;

        //Count number of creatives
        $numberOfCreatives = DB::table('manager_creative')
            ->where([
                ['manager_id', '=', $currentUser['id']],
                ['status', '=', 1]
            ])
            ->count();

        $manager->numberOfCreatives = $numberOfCreatives;

        $manager->img = URL::to('img_mobile/managers/' . $manager->img);

        return response()->json($manager, 200);
    }

    public function create(Request $request)
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

        $imgPath = public_path("img/managers/". $newImgName);
        $mobileImgPath = public_path("img_mobile/managers//" . $newImgName);

        $compressedImage = Image::make($original_img)->resize(800, 800);
        $compressedImage->save($imgPath);

        $compressedImageMobile = Image::make($original_img)->resize(200, 200);
        $compressedImageMobile->save($mobileImgPath);

        $manager = new Manager();

        $manager['user_id'] = $currentUser['id'];
        $manager['name'] = $request->input('name');
        $manager['management_company'] = $request->input('management_company');
        $manager['img'] = $newImgName;
        $manager['facebook'] = $request->input('facebook');
        $manager['instagram'] = $request->input('instagram');
        $manager['twitter'] = $request->input('twitter');

        if($manager->save()) {

            $currentUser['has_profile'] = true;
            $currentUser->save();

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

    public function addCreative(Request $request){

        $validator = Validator::make($request->all(), [
            'creative_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 422);
        }

        $currentUser = auth('api')->user();

        DB::table('manager_creative')->insert([
            ['created_at' => now(), 'updated_at' => now(),
                'manager_id' => $currentUser['id'], 'creative_id' => $request->input('creative_id'),
                'status' => false]
        ]);

        $managerCreative = DB::table('manager_creative')
            ->where([['creative_id', '=', $request->input('creative_id')],
                ['manager_id', '=', $currentUser['id']]
            ])
            ->first();

        $activity = new Request();
        $activity['message'] = "Requested to be added as your manager";
        $activity['message_es'] = "Solicitó ser agregado como su gerente";
        $activity['message_fr'] = "A demandé à être ajouté en tant que gestionnaire";
        $activity['message_zh'] = "請求添加為您的管理員";
        $activity['type'] = "management_request";
        $activity['sender_id'] = $currentUser['id'];
        $activity['receiver_id'] = $request->input('creative_id');
        $activity['request_id'] = $managerCreative->id;
        $activity->save();

        $response = [
            'message' => 'Request Created',
        ];

        return response()->json($response, 201);
    }

    public function addManager(Request $request){

        $validator = Validator::make($request->all(), [
            'manager_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 422);
        }

        $currentUser = auth('api')->user();

        DB::table('manager_creative')->insert([
            ['created_at' => now(), 'updated_at' => now(),
                'manager_id' => $currentUser['id'], 'creative_id' => $request->input('creative_id'),
                'status' => false]
        ]);

        $managerCreative = DB::table('manager_creative')
            ->where([['creative_id', '=', $request->input('creative_id')],
                ['manager_id', '=', $currentUser['id']]
            ])
            ->first();

        $activity = new Request();
        $activity['message'] = "wants you to be his manager";
        $activity['message_es'] = "quiere que seas su manager";
        $activity['message_fr'] = "veut que tu sois son manager";
        $activity['message_zh'] = "希望你成為他的經理";
        $activity['type'] = "management_request";
        $activity['sender_id'] = $currentUser['id'];
        $activity['receiver_id'] = $request->input('manager_id');
        $activity['request_id'] = $managerCreative->id;
        $activity->save();

        $response = [
            'message' => 'Request Created',
        ];

        return response()->json($response, 201);
    }

    public function getOtherManagerProfile($id){

        $currentUser = auth('api')->user();

        $manager = DB::table('managers')
            ->Leftjoin('users', 'users.id', '=', 'managers.user_id')
            ->select( 'managers.user_id', 'managers.id as manager_id',
                'managers.img', 'managers.name', 'managers.management_company',
                'managers.facebook', 'managers.instagram', 'managers.twitter')
            ->where('managers.user_id', '=', $id)
            ->first();

        //Check if profile isn't exist
        if($manager == null){
            $response = [
                'msg' => 'Manager Profile not found',
            ];

            return response()->json($response, 404);
        }

        //Count number of connections
        $numberOfConnections = Connection::
        where([['sender_id', '=', $id], ['status', '=', '1']])->
        orWhere([['receiver_id', '=', $id], ['status', '=', '1']])->count();

        $manager->numberOfConnections = $numberOfConnections;


        //Count number of creatives
        $numberOfCreatives = DB::table('manager_creative')
            ->where([
                ['manager_id', '=', $manager->user_id],
                ['status', '=', 1]
            ])
            ->count();

        $manager->numberOfCreatives = $numberOfCreatives;

        //Format variables
        $manager->img = URL::to('img_mobile/managers/' . $manager->img);

        //Check user connection status
        $connection = DB::table('connections')
            ->where([['connections.sender_id', '=', $currentUser['id']], ['connections.receiver_id', '=', $manager->user_id]])
            ->orWhere([['connections.receiver_id', '=', $currentUser['id']], ['connections.sender_id', '=', $manager->user_id]])
            ->first();

        if($connection){

            if($connection->status == 0){
                $manager->connection = "Pending";
            }else if($connection->status == 1){
                $manager->connection = "Connected";
            }

        }else{
            $manager->connection = "Not connected";
        }

        return response()->json($manager, 200);
    }

    public function getManagementRequests(){
        $currentUser = auth('api')->user();

        $managerRequests = DB::table('manager_creative')
            ->select('manager_creative.id', 'managers.name',
                'managers.img', 'managers.user_id')
            ->leftJoin('managers', 'manager_creative.manager_id',
                '=', 'managers.user_id')
            ->where([['manager_creative.creative_id', '=', $currentUser['id']],
                ['manager_creative.status', '=', 0]])
            ->get();

        foreach($managerRequests as $key => $value)
        {
            //Format variable
            $managerRequests[$key]->img = URL::to('img_mobile/managers/' . $managerRequests[$key]->img);
        }

        return response()->json($managerRequests, 200);
    }

    public function verifyManagerRequest(Request $request){

        $validator = Validator::make($request->all(), [
            'request_id' => 'required|numeric|exists:manager_creative,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        DB::table('manager_creative')
            ->where('id', $request->input('request_id'))
            ->update(['status' => true]);

        $response = [
            'msg' => 'Request Verified',
        ];

        return response()->json($response, 200);
    }

    public function declineManagerRequest(Request $request){

        $validator = Validator::make($request->all(), [
            'request_id' => 'required|numeric|exists:manager_creative,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        DB::table('manager_creative')
            ->where('id', $request->input('request_id'))
            ->delete();

        $response = [
            'msg' => 'Request Declined',
        ];

        return response()->json($response, 200);
    }

    public function getMyCreatives(){

        $currentUser = auth('api')->user();

        $creatives = DB::table('manager_creative')
            ->select('profiles.user_id', 'profiles.first_name',
                'profiles.last_name', 'profiles.img')
            ->leftJoin('profiles', 'manager_creative.creative_id',
                '=', 'profiles.user_id')
            ->where([['manager_creative.manager_id', '=', $currentUser['id']],
                ['manager_creative.status', '=', 1]])
            ->get();

        foreach($creatives as $key => $value)
        {
            //Format variable
            $creatives[$key]->img = URL::to('img_mobile/profiles/' . $creatives[$key]->img);
        }

        return response()->json($creatives, 200);
    }

    public function getOtherManagerCreatives($id){

        $creatives = DB::table('manager_creative')
            ->select('profiles.user_id', 'profiles.first_name',
                'profiles.last_name', 'profiles.img')
            ->leftJoin('profiles', 'manager_creative.creative_id',
                '=', 'profiles.user_id')
            ->where([['manager_creative.manager_id', '=', $id],
                ['manager_creative.status', '=', 1]])
            ->get();

        foreach($creatives as $key => $value)
        {
            //Format variable
            $creatives[$key]->img = URL::to('img_mobile/profiles/' . $creatives[$key]->img);
        }

        return response()->json($creatives, 200);
    }

}
