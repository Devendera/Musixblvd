<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Connection;
use App\Manager;
use App\Creative;
use App\Studio;
use App\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class ConnectionController extends Controller
{
    public function create(Request $request)
    {

        $currentUser = auth('api')->user();

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        //-----Check if connection is already created-----//
        if(Connection::where([['connected_user_id', $request->input('user_id')],
            ['user_id', $currentUser['id']],
            ['type', 'connection']])->first()){
            $response = [
                'error' => 'Connection already created',
            ];
            return response()->json($response, 401);
        }

        //------------------------------------------------//

        $connectionSender = new Connection();

        $connectionSender['connected_user_id'] = $request->input('user_id');
        $connectionSender['type'] = "connection";
        $connectionSender['user_id'] = $currentUser['id'];

        $connectionReceiver = new Connection();

        $connectionReceiver['connected_user_id'] = $currentUser['id'];
        $connectionReceiver['type'] = "connection";
        $connectionReceiver['user_id'] = $request->input('user_id');

        if($connectionSender->save() && $connectionReceiver->save()) {

            $activity = new Activity();
            $activity['sender_id'] = $currentUser['id'];
            $activity['request_id'] = $connectionSender['id'];
            $activity['message'] = "Requested to connect with you";
            $activity['message_es'] = "Se le solicitó conectarse con usted";
            $activity['message_fr'] = "A demandé à se connecter avec vous";
            $activity['message_zh'] = "請求與您聯繫";
            $activity['type'] = "connection";
            $activity['user_id'] = $request->input('user_id');
            $activity->save();

            // Send Push Notification
            $senderUser = User::where('id', $currentUser['id'])->first();

            $receiverUser = User::where('id', $request->input('user_id'))
                ->first();

            $client = new Client();
            try {

                $r = $client->request('POST', 'https://onesignal.com/api/v1/notifications', [
                    'form_params' => [

                        'app_id' => '823e2e47-6541-4274-a47e-36ea9a899164',

                        'include_player_ids[]' => $receiverUser['notification_token'],

                        'headings' => [
                            'en' => $senderUser['name']
                        ],

                        'contents' => [
                            'en' => $senderUser['name'] . " wants to connect with you."
                        ],

                        'large_icon' => $senderUser['img'],

                        'android_group' => $senderUser['id']
                    ]
                ]);

            } catch (GuzzleException $e) {
                return $e->getMessage();
            }


            $response = [
                'msg' => 'Connection Created',
            ];

            return response()->json($response, 201);
        }

        $response = [
            'msg' => 'An error occurred',
        ];

        return response()->json($response, 401);
    }

    public function delete(Request $request){
        $currentUser = auth('api')->user();

        $validator = Validator::make($request->all(), [
            'connection_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        //-----Check if connection is already created-----//
        $connection = Connection::find($request->input('connection_id'))->first();
        if($connection){
            if($connection->delete()){
                $response = [
                    'msg' => 'Connection Deleted',
                ];

                return response()->json($response, 200);
            }else{
                $response = [
                    'msg' => 'An error occurred',
                ];

                return response()->json($response, 401);
            }

        }else{
            $response = [
                'error' => 'Connection not found',
            ];
            return response()->json($response, 401);
        }
    }

    public function getConnectionRequests(){
        $currentUser = auth('api')->user();

        $connections = DB::table('connections')
            ->select('connections.id', 'users.type AS user_type',
                'users.id AS user_id')
            ->leftJoin('users', 'connections.sender_id',
                '=', 'users.id')
            ->where([['connections.receiver_id', '=', $currentUser['id']],
                ['connections.status', '=', 0]])
            ->get();

        foreach($connections as $key => $value)
        {
            switch ($connections[$key]->user_type){

                case "creative":{
                    $profile = Creative::where('user_id', $connections[$key]->user_id)->first();

                    $connections[$key]->name = $profile['first_name'] . ' ' . $profile['last_name'];
                    $connections[$key]->img = $profile['img'];

                    $connections[$key]->img = URL::to('img_mobile/profiles/' . $connections[$key]->img);
                }
                    break;

                case "manager":{
                    $manager = Manager::where('user_id', $connections[$key]->user_id)->first();

                    $connections[$key]->name = $manager['name'];
                    $connections[$key]->img = $manager['img'];
                    $connections[$key]->img = URL::to('img_mobile/managers/' . $connections[$key]->img);
                }
                    break;

                case "studio":{
                    $studio = Studio::where('user_id', $connections[$key]->user_id)->first();

                    $connections[$key]->name = $studio['name'];

                    $image = DB::table('studio_images')
                        ->select('studio_images.id', 'studio_images.image')
                        ->where('studio_id', '=', $studio['id'])
                        ->first();

                    $connections[$key]->img = $image->image;
                    $connections[$key]->img = URL::to('img_mobile/studios/' . $connections[$key]->img);
                }
                    break;
            }
        }

        return response()->json($connections, 200);
    }
}
