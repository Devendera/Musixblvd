<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;
use App\User;
use Hash;
use App\Manager;
use App\Creative;
use App\Studio;
use App\Genre;
use App\Gender;
use App\Craft;
use App\Type;
use App\Platform;
use App\UserPlatforms;
use App\Country;
use App\Connection;
use App\Activity;
use App\Project;
use App\State;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use DB;
use File;
use URL;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
class ConnectionsWebController extends Controller
{
    protected 
        $redirectTo = 'login',
        $guard = 'web';
    public function createConnections(Request $request)
    {
        $response = array();
        if (Auth::guard($this->guard)->check()) {
            $user = Auth::guard($this->guard)->user();
            $userId = $user->id;

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['status'=>false, 'message'=>config('constants.InvalidId')]);
        }
        //-----Check if connection is already created-----//
        if(Connection::where([['connected_user_id', $request->user_id],
            ['user_id', $userId],
            ['type', 'connection']])->first()){
            return response()->json(['status'=>false, 'message'=>config('constants.ConnectionExist')]);
        }

        //------------------------------------------------//

        $connectionSender = new Connection();

        $connectionSender->connected_user_id = $request->input('user_id');
        $connectionSender->type = "connection";
        $connectionSender->user_id = $userId;
        
        if($connectionSender->save()) {
            $activity = new Activity();
            $activity['sender_id'] = $userId;
            $activity['request_id'] = $connectionSender['id'];
            $activity['message'] = config('constants.RequestAlert');
            $activity['message_es'] = config('constants.RequestAlertES');
            $activity['message_fr'] = config('constants.RequestAlertFR');
            $activity['message_zh'] = config('constants.RequestAlertZH');
            $activity['type'] = "connection";
            $activity['user_id'] = $request->input('user_id');
            $activity->save();
            return response()->json(['status'=>true, 'message'=>config('constants.ConnectionCreated')]);
        }else {
            return response()->json(['status'=>false, 'message'=>config('constants.SomethingWrong')]);
        }    
    }else {
            return response()->json(['status'=>false, 'message'=>config('constants.Unauthenticate')]);
        }
    }

    public function getAllCreatives(){
        if (Auth::guard($this->guard)->check()) {

            $user = Auth::guard($this->guard)->user();
            $userId = $user->id;
        } else {
            $userId = '';
        }
        $creatives = User::where([
                    ['type', '1'],
                    ['has_profile', true]
                        ]);
                    if($userId != ''){
                        $creatives = $creatives->where('id', '!=', $userId);
                    }
        $creatives = $creatives->with('creative')
                    ->with('sender')
                    ->with('receiver')
                    ->withCount('connections')
                    ->withCount('projects')
                    ->paginate(config('constants.PaginateArray.1'));

        foreach($creatives as $key => $value)
        {
            if(!empty($value['sender']) || !empty($value['receiver'])){
                 if(isset($value['sender']['is_approved']) || isset($value['receiver']['is_approved'])){
                    $creatives[$key]->connection = config('constants.Connected');
                }else{
                    $creatives[$key]->connection = config('constants.Pending');
                }

            }else{
                $creatives[$key]->connection = config('constants.NotConnected');
            }
        }
        return view('web.creative_view', compact('creatives'));   
    }

    public function getAllManagers(){

        if (Auth::guard($this->guard)->check()) {

            $user = Auth::guard($this->guard)->user();
            $userId = $user->id;
        } else {
            $userId = '';
        }
        $managers = User::where([
            ['type', "2"],
            ['has_profile', true]
        ]);
        if($userId != ''){
            $managers = $managers->where('id', '!=', $userId);
        }
        $managers = $managers->with('manager')
        ->with('sender')
        ->with('receiver')
        ->withCount('connections')
        ->withCount('projects')
        ->paginate(config('constants.PaginateArray.1'));

        foreach($managers as $key => $value)
        {
            if(!empty($value['sender']) || !empty($value['receiver'])){
                 if(isset($value['sender']['is_approved']) || isset($value['receiver']['is_approved'])){
                    $managers[$key]->connection = config('constants.Connected');
                }else{
                    $managers[$key]->connection = config('constants.Pending');
                }
            }else{
                $managers[$key]->connection = config('constants.NotConnected');
            }
        }
		
        return view('web.manager_view', compact('managers'));
    }
    /*******************Get all studios*****/
    public function getAllStudios(){
        if (Auth::guard($this->guard)->check()) {

            $user = Auth::guard($this->guard)->user();
            $userId = $user->id;
        } else {
            $userId = '';
        }
        $studios = User::where([
                                ['type', '3'],
                                ['has_profile', true]
                            ]);
        if($userId != ''){
            $studios = $studios->where('id', '!=', $userId); 
        }
        $studios = $studios->with('studio')
                            ->with('sender')
                            ->with('receiver')
                            ->withCount('connections')
                            ->withCount('projects')
                            ->paginate(config('constants.PaginateArray.3'));
        foreach($studios as $key => $value)
        {
            if(!empty($value['sender']) || !empty($value['receiver'])){
                 if(isset($value['sender']['is_approved']) || isset($value['receiver']['is_approved'])){
                    $studios[$key]->connection = config('constants.Connected');
                }else{
                    $studios[$key]->connection = config('constants.Pending');
                }

            }else{
                $studios[$key]->connection = config('constants.NotConnected');
            }
        }
        return view('web.studio_view', compact('studios'));
    }
    /****************************Get all connected users****/
    public function getConnectedUsers(){
        if (Auth::guard($this->guard)->check()) {
            $user = Auth::guard($this->guard)->user();
            $userId = $user->id;
            $connectedUsers = Connection::with('senderConnectedUser')
                                        ->with('receiverConnectedUser')
                                        ->where([
                                                ['connected_user_id', $userId],
                                                ['is_approved', true]
                                            ])
                                        ->orWhere([
                                                ['user_id', $userId],
                                                ['is_approved', true]
                                            ])
                                        ->paginate(config('constants.PaginateArray.1'));
            foreach($connectedUsers as $key=>$value) {
                if($value['senderConnectedUser']['id'] == $userId){
                    $connectedUsers[$key]['connectedUser'] = $connectedUsers[$key]['receiverConnectedUser'];
                    unset($connectedUsers[$key]['senderConnectedUser']);
                    unset($connectedUsers[$key]['receiverConnectedUser']);
                } elseif($value['receiverConnectedUser']['id'] == $userId){
                    $connectedUsers[$key]['connectedUser'] = $connectedUsers[$key]['senderConnectedUser'];
                    unset($connectedUsers[$key]['receiverConnectedUser']);
                    unset($connectedUsers[$key]['senderConnectedUser']);
                }
            }
            return view('web.connection', compact('connectedUsers'));
           
        }
        else {
            return redirect('/login')->with('flash_message_error', config('constants.AuthExpire'));
        }
    }
    /*********Function to accept/approved request****/
    public function confirmRequest($id){
        if (Auth::guard($this->guard)->check()) {
            $user = Auth::guard($this->guard)->user();
            $userId = $user->id;
            if($id =='') {
                return redirect('/my-profile')->with('flash_message_error', config('constants.SomethingWrong'));
            } else {
                $connection = Connection::where('id', $id)->first();
                if(!empty($connection)){
                    $connection->is_approved = true;
                    if($connection->save()){
                        return redirect('/my-profile')->with('flash_message_success', config('constants.RequestUpdated'));
                    }else {
                        return redirect('/my-profile')->with('flash_message_error', config('constants.SomethingWrong')); 
                    }
                }else{
                    return redirect('/my-profile')->with('flash_message_error', config('constants.RequestNotFound'));
                }
            }
        }else {
            return redirect('/login')->with('flash_message_error', config('constants.AuthExpire'));
        }
    }
    /***************Create management request *********/
    public function createRequestManagement(Request $request)
    {
        $response = array();
        if (Auth::guard($this->guard)->check()) {
            $user = Auth::guard($this->guard)->user();
            $userId = $user->id;
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id'
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>false, 'message'=>config('constants.InvalidId')]);
        }
        //-----Check if management is already created-----//
        if(Connection::where([['connected_user_id', $request->input('user_id')],
            ['user_id', $userId],
            ['type', 'management']])->first()){
             return response()->json(['status'=>false, 'message'=>config('constants.ManagementExist')]);
        }
        //------------------------------------------------//
        $connectionSender = new Connection();
        $connectionSender->connected_user_id = $request->input('user_id');
        $connectionSender->type = "management";
        $connectionSender->user_id = $userId;
        if($connectionSender->save()) {
            $activity = new Activity();
            $activity['sender_id'] = $userId;
            $activity['request_id'] = $connectionSender['id'];
            if($user->type == Config::get('constants.Manager')){
                $activity['message'] = config('constants.RequestManagmentAlert');
                $activity['message_es'] = config('constants.RequestManagmentAlertES');;
                $activity['message_fr'] = config('constants.RequestManagmentAlertFR');;
                $activity['message_zh'] = config('constants.RequestManagmentAlertZH');;
            }
            else if($user->type == Config::get('constants.Creative')){
                $activity['message'] = config('constants.RequestClientAlert');
                $activity['message_es'] = config('constants.RequestClientAlertES');
                $activity['message_fr'] = config('constants.RequestClientAlertFR');
                $activity['message_zh'] = config('constants.RequestClientAlertZH');
            }
            $activity['type'] = "management";
            $activity['user_id'] = $request->input('user_id');
            $activity->save();
            return response()->json(['status'=>true, 'message'=>config('constants.ManagementCreated')]);
        }else {
            return response()->json(['status'=>false, 'message'=>config('constants.SomethingWrong')]);
        }
    }else {
             return response()->json(['status'=>false, 'message'=>config('constants.Unauthenticate')]);
        }
    }
}
