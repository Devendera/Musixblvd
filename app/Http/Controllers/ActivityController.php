<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Connection;
use App\ProjectEditRequest;
use App\Project;
use App\User;
use App\UserProject;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class ActivityController extends Controller
{

    public function getMyActivities()
    {
        $currentUser = auth('api')->user();

        $activities = Activity::with('user')
                            ->where('user_id', $currentUser['id'])
                            ->orderBy('created_at', 'DESC')
                            ->get();

        return response()->json($activities, 200);
    }

    public function verifyActivity(Request $request){

        $validator = Validator::make($request->all(), [
            'activity_id' => 'required|numeric|exists:activities,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $currentUser = auth('api')->user();

        $activity = Activity::find($request->input('activity_id'));
        $activity->status = true;

        if($activity->type == "project"){

            $userProject = UserProject::find($activity['request_id']);
            $userProject['is_approved'] = true;

            if($userProject->save() && $activity->save()){
                $response = [
                    'msg' => 'Activity Accepted',
                ];

                return response()->json($response, 200);
            }

        }else if($activity->type == "project_edit"){ //Project edit request

            $projectEditRequest = ProjectEditRequest::find($activity['request_id']);
            $projectEditRequest['is_approved'] = true;

            if($projectEditRequest->save() && $activity->save()){

                //Check if Project Edit Request approved by all contributors
                $projectEditRequests = ProjectEditRequest::where([
                    ['project_id', $projectEditRequest['project_id']],
                    ['user_id', $projectEditRequest['user_id']]
                ])->get();

                foreach($projectEditRequests as $key => $projectRequest){
                    if(!$projectRequest['is_approved']){
                        $response = [
                            'msg' => 'Activity Accepted',
                        ];

                        return response()->json($response, 200);
                    }
                }

                //Send Notification to user who created the request
                $this->sendPushNotification($projectEditRequest['project_id'], $projectEditRequest['user_id'],
                    'Project edit request approved by all contributors');

                $response = [
                    'msg' => 'Activity Accepted',
                ];

                return response()->json($response, 200);
            }

        }
        else{ //Connection or management

            $connectionSender = Connection::find($activity['request_id']);
            $connectionSender['is_approved'] = true;

            if($connectionSender->save() && $activity->save()){

                $connectionReceiver = Connection::where([
                    ['connected_user_id', $connectionSender['user_id']],
                    ['user_id', $connectionSender['connected_user_id']],
                    ['type', $connectionSender['type']]
                ])->first();

                $connectionReceiver['is_approved'] = true;
                $connectionReceiver->save();

                $response = [
                    'msg' => 'Activity Accepted',
                ];

                return response()->json($response, 200);

            }

        }


        $response = [
            'msg' => 'An error occurred',
        ];

        return response()->json($response, 401);
    }

    public function delete(Request $request){

        $validator = Validator::make($request->all(), [
            'activity_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 422);
        }

        //-----Check if activity is already created-----//
        $isDeleted = DB::table('activities')
            ->where('id', '=', $request->input('activity_id'))
            ->delete();

        if($isDeleted){
            $response = [
                'msg' => 'Activity deleted',
            ];

            return response()->json($response, 200);
        }else{
            $response = [
                'msg' => 'An error occurred',
            ];

            return response()->json($response, 401);
        }
    }

    private function sendPushNotification($projectId, $receiverUserId, $message){

        // Send Push Notification
        $project = Project::where('id', $projectId)->first();

        $receiverUser = User::find($receiverUserId);

        $client = new Client();
        try {

            $r = $client->request('POST', 'https://onesignal.com/api/v1/notifications', [
                'form_params' => [

                    'app_id' => '823e2e47-6541-4274-a47e-36ea9a899164',

                    'include_player_ids[]' => $receiverUser['notification_token'],

                    'headings' => [
                        'en' => $project['title']
                    ],

                    'contents' => [
                        'en' => $message
                    ],

                    'large_icon' => $project['img'],

                    'android_group' => 'project' . $project['id']
                ]
            ]);

        } catch (GuzzleException $e) {
            return $e->getMessage();
        }


        return "Notification Sent";
    }

}
