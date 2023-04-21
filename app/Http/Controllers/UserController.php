<?php

namespace App\Http\Controllers;

use App\Activity;
use App\AuthenticationProvider;
use App\Connection;
use App\Mail\SendMail;
use App\Creative;
use App\Manager;
use App\Studio;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $currentUser = auth('api')->user();

        $user = null;

        switch ($currentUser['type']){

            case 'creative':{
                $user = User::where('id', $currentUser['id'])
                    ->with('creative')
                    ->first();
            }
                break;

            case 'manager':{
                $user = User::where('id', $currentUser['id'])
                    ->with('manager')
                    ->first();
            }
                break;

            case 'studio':{
                $user = User::where('id', $currentUser['id'])
                    ->with('studio')
                    ->first();
            }
                break;

        }

        return response()->json($user);
    }

    public function verifyUserNewEmail($verification_code)
    {
        $check = DB::table('user_verifications')->where('token', $verification_code)->first();
        if(!is_null($check)){
            $user = User::find($check->user_id);
            $user['email'] = $check->email;
            $user['email_verified_at'] = time();
            $user->save();
            DB::table('user_verifications')->where('token',$verification_code)->delete();
            return response()->view('email.email_verified');
        }else{
            return response()->view('email.invalid');
        }

    }

    public function updateEmail(Request $request){

        $currentUser = auth('api')->user();

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 422);
        }

        $email = $request->input('email');
        $verification_code = Str::random(30); //Generate verification code
        DB::table('user_verifications')->insert(['user_id'=>$currentUser['id'],'token'=>$verification_code, 'email'=>$email]);

        $data = array(
            'verification_code' => $verification_code
        );

        Mail::to($email)->send(new SendMail($data, 2));

        return response()->json(['message'=> 'Please check your new email address'], 200);
    }

    public function updatePassword(Request $request){

        $currentUser = auth('api')->user();

        $validator = Validator::make($request->all(), [
            'password' => 'required|min:5'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 422);
        }

        $user = User::find($currentUser['id'])->first();
        $user['password'] = bcrypt($request->input('password'));
        if($user->save()){
            return response()->json(['message'=> 'Password updated successfully'], 200);
        }

        $response = [
            'msg' => 'An error occurred',
        ];

        return response()->json($response, 404);
    }

    public function updateNotificationToken(Request $request){

        $validator = Validator::make($request->all(), [
            'notification_token' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 422);
        }

        $currentUser = auth('api')->user();

        $currentUser['notification_token'] = $request->input('notification_token');

        if($currentUser->save()){
            return response()->json(['message'=> 'Notification token updated'], 200);
        }

        $response = [
            'msg' => 'An error occurred',
        ];

        return response()->json($response, 404);
    }

    public function addLegalAndDistributionFor(Request $request){

        $validator = Validator::make($request->all(), [
            'type' => [
                'required',
                Rule::in(['legal', 'distribution']),
            ],
            'point1' => 'required',
            'point2' => 'required',
            'point3' => 'required',
            'point4' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 422);
        }

        $currentUser = auth('api')->user();

        DB::table('legal_distribution')->insert([
            ['created_at' => now(), 'updated_at' => now(),
                'type' => $request->input('type'),
                'point1' => $request->input('point1'), 'point2' => $request->input('point2'),
                'point3' => $request->input('point3'), 'point4' => $request->input('point4'),
                'user_id' => $currentUser['id']
            ]]);

        $response = [
            'msg' => 'Form saved',
        ];

        return response()->json($response, 201);
    }

    public function getAllCreatives(){
        $currentUser = auth('api')->user();

        $creatives = User::where([
                            ['id', '!=',$currentUser['id']],
                            ['type','creative'],
                            ['has_profile', true]
                                ])
            ->with('creative')
            ->withCount('connections')
            ->withCount('projects')
            ->paginate(5);

        foreach($creatives as $key => $value)
        {
            $connection = Connection::where([
                ['connected_user_id', $creatives[$key]->id],
                ['user_id', $currentUser['id']],
                ['type', 'connection']
            ])->first();

            if($connection){

                if($connection['is_approved']){
                    $creatives[$key]->connection = "Connected";
                }else{
                    $creatives[$key]->connection = "Pending";
                }

            }else{
                $creatives[$key]->connection = "Not connected";
            }
        }

        return response()->json($creatives, 200);
    }

    public function getAllManagers(){
        $currentUser = auth('api')->user();

        $managers = User::where([
            ['id', '!=',$currentUser['id']],
            ['type','manager'],
            ['has_profile', true]
        ])
            ->with('manager')
            ->withCount('connections')
            ->withCount('projects')
            ->paginate(30);

        foreach($managers as $key => $value)
        {
            $connection = Connection::where([
                ['connected_user_id', $managers[$key]->id],
                ['user_id', $currentUser['id']],
                ['type', 'connection']
            ])->first();

            if($connection){

                if($connection['is_approved']){
                    $managers[$key]->connection = "Connected";
                }else{
                    $managers[$key]->connection = "Pending";
                }

            }else{
                $managers[$key]->connection = "Not connected";
            }
        }

        return response()->json($managers, 200);
    }

    public function getAllStudios(){
        $currentUser = auth('api')->user();

        $studios = User::where([
            ['id', '!=',$currentUser['id']],
            ['type','studio'],
            ['has_profile', true]
        ])
            ->with('studio')
            ->withCount('connections')
            ->withCount('projects')
            ->get();

        foreach($studios as $key => $value)
        {
            $connection = Connection::where([
                ['connected_user_id', $studios[$key]->id],
                ['user_id', $currentUser['id']],
                ['type', 'connection']
            ])->first();

            if($connection){

                if($connection['is_approved']){
                    $studios[$key]->connection = "Connected";
                }else{
                    $studios[$key]->connection = "Pending";
                }

            }else{
                $studios[$key]->connection = "Not connected";
            }
        }

        return response()->json($studios, 200);
    }

    public function filterUsers(Request $request){

        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'artistry' => 'required',
            'genre' => 'required',
            'gender' => 'required',
            'state' => 'required',
            'city' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 422);
        }

        $currentUser = auth('api')->user();

        $creatives = User::where([
            ['id', '!=',$currentUser['id']],
            ['type','creative'],
            ['has_profile', true]
        ])
            ->with('creative')
            ->withCount('connections')
            ->withCount('projects')
            ->whereHas('creative', function ($query) use ($request) {

                $conditions = array();

                if($request->input('type') !== 'All')
                    array_push($conditions, ['type', '=', $request->input('type')]);

                if($request->input('artistry') !== 'All')
                    array_push($conditions, ['craft', '=', $request->input('artistry')]);

                if($request->input('genre') !== 'All')
                    array_push($conditions, ['genre', '=', $request->input('genre')]);

                if($request->input('gender') !== 'All')
                    array_push($conditions, ['gender', '=', $request->input('gender')]);

                if($request->input('country') !== 'Country')
                    array_push($conditions, ['country', '=', $request->input('country')]);

                if($request->input('state') !== 'State')
                    array_push($conditions, ['state', '=', $request->input('state')]);

                if($request->input('city') !== 'City')
                    array_push($conditions, ['city', '=', $request->input('city')]);

                $query->where($conditions);

                /*$query->where([
                    ['type', '=', $request->input('type')],
                    ['craft', '=', $request->input('artistry')],
                    ['genre', '=', $request->input('genre')],
                    ['gender', '=', $request->input('gender')],
                    ['state', '=', $request->input('state')],
                    ['city', '=', $request->input('city')]
                ]);*/

            })
            ->orderyBy('city', 'ASC')
            ->get();

        foreach($creatives as $key => $value)
        {
            $connection = Connection::where([
                ['connected_user_id', $creatives[$key]->id],
                ['user_id', $currentUser['id']],
                ['type', 'connection']
            ])->first();

            if($connection){

                if($connection['is_approved']){
                    $creatives[$key]->connection = "Connected";
                }else{
                    $creatives[$key]->connection = "Pending";
                }

            }else{
                $creatives[$key]->connection = "Not connected";
            }
        }

        return response()->json($creatives, 200);
    }

    public function search(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 422);
        }

        $currentUser = auth('api')->user();
        $searchQuery = $request->input('name');

        $users = User::with('creative', 'manager', 'studio')
            ->withCount('connections')
            ->withCount('projects')
            ->where([
                ['id', '!=', $currentUser['id']],
                ['has_profile', true]
            ])
            ->whereHas('creative', function ($query) use ($searchQuery) {
                $query->where('name', 'LIKE', '%' . $searchQuery . '%');
            })
            ->orWhereHas('manager', function ($query) use ($searchQuery) {
                $query->where('name', 'LIKE', '%' . $searchQuery . '%');
            })
            ->orWhereHas('studio', function ($query) use ($searchQuery) {
                $query->where('name', 'LIKE', '%' . $searchQuery . '%');
            })
            ->get();

        foreach($users as $key => $value)
        {
            $connection = Connection::where([
                ['connected_user_id', $users[$key]->id],
                ['user_id', $currentUser['id']],
                ['type', 'connection']
            ])->first();

            if($connection){

                if($connection['is_approved']){
                    $users[$key]->connection = "Connected";
                }else{
                    $users[$key]->connection = "Pending";
                }

            }else{
                $users[$key]->connection = "Not connected";
            }

            switch ($users[$key]->type){

                case 'creative':{
                    unset($users[$key]->manager);
                    unset($users[$key]->studio);
                }
                break;

                case 'manager':{
                    unset($users[$key]->creative);
                    unset($users[$key]->studio);
                }
                    break;

                case 'studio':{
                    unset($users[$key]->creative);
                    unset($users[$key]->manager);
                }
                    break;
            }
        }

        return response()->json($users, 200);
    }

    public function getConnectedUsers(){

        $currentUser = auth('api')->user();

        $connectedUsers = Connection::with('connectedUser')
                            ->where([
                                ['user_id', $currentUser['id']],
                                ['type', 'connection'],
                                ['is_approved', true]
                            ])->get();

//        $connectedUsers = User::find($currentUser['id'])->with('connections')->get();

        /*$connectedUsers = DB::table('connections')
            ->select('users.id', 'users.name', 'users.img')
            ->Rightjoin('users', 'connections.connected_user_id', '=', 'users.id')
            ->where([
                ['connections.user_id', '=', $currentUser['id']],
                ['connections.is_approved', '=', '1'],
                ['connections.type', '=', 'connection']])
            ->get();*/

        /*$users = User::with('creative', 'manager', 'studio')
            ->withCount('connections')
            ->withCount('projects')
            ->where([
                ['id', '!=', $currentUser['id']],
                ['has_profile', true]
            ])
            ->whereHas('creative', function ($query) use ($searchQuery) {
                $query->where('name', 'LIKE', '%' . $searchQuery . '%');
            })
            ->orWhereHas('manager', function ($query) use ($searchQuery) {
                $query->where('name', 'LIKE', '%' . $searchQuery . '%');
            })
            ->orWhereHas('studio', function ($query) use ($searchQuery) {
                $query->where('name', 'LIKE', '%' . $searchQuery . '%');
            })
            ->get();*/

        /*foreach($users as $key => $value)
        {
            $connection = Connection::where([
                ['connected_user_id', $users[$key]->id],
                ['user_id', $currentUser['id']],
                ['type', 'connection']
            ])->first();

            if($connection){

                if($connection['is_approved']){
                    $users[$key]->connection = "Connected";
                }else{
                    $users[$key]->connection = "Pending";
                }

            }else{
                $users[$key]->connection = "Not connected";
            }

            switch ($users[$key]->type){

                case 'creative':{
                    unset($users[$key]->manager);
                    unset($users[$key]->studio);
                }
                    break;

                case 'manager':{
                    unset($users[$key]->creative);
                    unset($users[$key]->studio);
                }
                    break;

                case 'studio':{
                    unset($users[$key]->creative);
                    unset($users[$key]->manager);
                }
                    break;
            }
        }*/

        return response()->json($connectedUsers, 200);
    }

    public function searchCreatives(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 422);
        }

        $searchQuery = $request->input('name');

        $users = User::where([
            ['name', 'LIKE', '%' . $searchQuery . '%'],
            ['type', 'creative'],
            ['has_profile', true]
        ])
            ->get();

        return response()->json($users, 200);
    }

    public function searchStudios(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 422);
        }

        $searchQuery = $request->input('name');

        $users = User::where([
            ['name', 'LIKE', '%' . $searchQuery . '%'],
            ['type', 'studio'],
            ['has_profile', true]
        ])
            ->get();

        return response()->json($users, 200);
    }

    public function requestManagement(Request $request)
    {

        $currentUser = auth('api')->user();

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        //-----Check if connection is already created-----//
        if(Connection::where([['connected_user_id', $request->input('user_id')],
            ['user_id', $currentUser['id']],
            ['type', 'management']])->first()){
            $response = [
                'error' => 'Management is already exists between the two users',
            ];
            return response()->json($response, 401);
        }

        //------------------------------------------------//

        $connectionSender = new Connection();

        $connectionSender['connected_user_id'] = $request->input('user_id');
        $connectionSender['type'] = "management";
        $connectionSender['user_id'] = $currentUser['id'];

        $connectionReceiver = new Connection();

        $connectionReceiver['connected_user_id'] = $currentUser['id'];
        $connectionReceiver['type'] = "management";
        $connectionReceiver['user_id'] = $request->input('user_id');

        if($connectionSender->save() && $connectionReceiver->save()) {

            $activity = new Activity();
            $activity['sender_id'] = $currentUser['id'];
            $activity['request_id'] = $connectionSender['id'];

            if($currentUser['type'] == "manager"){
                $activity['message'] = "Requested to be your manager";
                $activity['message_es'] = "Requerido para ser su gerente";
                $activity['message_fr'] = "A demandé à être votre manager";
                $activity['message_zh'] = "要求成為您的經理";
            }
            else if($currentUser['type'] == "creative"){
                $activity['message'] = "Requested to be added as your client";
                $activity['message_es'] = "Solicitado para ser agregado como su cliente";
                $activity['message_fr'] = "A demandé à être ajouté en tant que client";
                $activity['message_zh'] = "請求添加為您的客戶";
            }

            $activity['type'] = "management";
            $activity['user_id'] = $request->input('user_id');
            $activity->save();

            // Send Push Notification
            /*$senderUser = User::where('id', $currentUser['id'])->first();

            switch ($senderUser->type){

                case "creative":{

                    $receiverUser = User::where('id', $request->input('user_id'))
                        ->first();

                    $profile = Creative::where('user_id', $senderUser['id'])->first();

                    $profile->img = URL::to('img_mobile/profiles/' . $profile->img);

                    $client = new Client();
                    try {

                        $r = $client->request('POST', 'https://onesignal.com/api/v1/notifications', [
                            'form_params' => [

                                'app_id' => '823e2e47-6541-4274-a47e-36ea9a899164',

                                'include_player_ids[]' => $receiverUser['notification_token'],

                                'headings' => [
                                    'en' => $profile['first_name'] . ' ' . $profile['last_name']
                                ],

                                'contents' => [
                                    'en' => $profile['first_name'] . " wants to connect with you."
                                ],

                                'large_icon' => $profile['img'],

                                'android_group' => $senderUser['id']
                            ]
                        ]);

                    } catch (GuzzleException $e) {
                        return $e->getMessage();
                    }

                }
                    break;

                case "manager":{

                    $receiverUser = User::where('id', $request->input('user_id'))
                        ->first();

                    $manager = Manager::where('user_id', $senderUser['id'])->first();

                    $manager->img = URL::to('img_mobile/managers/' . $manager->img);

                    $client = new Client();
                    try {

                        $r = $client->request('POST', 'https://onesignal.com/api/v1/notifications', [
                            'form_params' => [

                                'app_id' => '823e2e47-6541-4274-a47e-36ea9a899164',

                                'include_player_ids[]' => $receiverUser['notification_token'],

                                'headings' => [
                                    'en' => $manager['name']
                                ],

                                'contents' => [
                                    'en' => $manager['name'] . " wants to connect with you."
                                ],

                                'large_icon' => $manager['img'],

                                'android_group' => $senderUser['id']
                            ]
                        ]);

                    } catch (GuzzleException $e) {
                        return $e->getMessage();
                    }

                }
                    break;

                case "studio":{
                    $receiverUser = User::where('id', $request->input('user_id'))
                        ->first();

                    $studio = Studio::where('user_id', $senderUser['id'])->first();

                    $studio->img = URL::to('img_mobile/studios/' . $studio->img);

                    $client = new Client();
                    try {

                        $r = $client->request('POST', 'https://onesignal.com/api/v1/notifications', [
                            'form_params' => [

                                'app_id' => '823e2e47-6541-4274-a47e-36ea9a899164',

                                'include_player_ids[]' => $receiverUser['notification_token'],

                                'headings' => [
                                    'en' => $studio['name']
                                ],

                                'contents' => [
                                    'en' => $studio['name'] . " wants to connect with you."
                                ],

                                'large_icon' => $studio['img'],

                                'android_group' => $senderUser['id']
                            ]
                        ]);

                    } catch (GuzzleException $e) {
                        return $e->getMessage();
                    }
                }
                    break;
            }*/

            $response = [
                'msg' => 'Management Created',
            ];

            return response()->json($response, 201);
        }

        $response = [
            'msg' => 'An error occurred',
        ];

        return response()->json($response, 401);
    }

    public function logout()
    {
        $currentUser = auth('api')->user();
        $user = User::find($currentUser['id']);
        $user->notification_token = "";
        $user->save();
        auth('api')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

}
