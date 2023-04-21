<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Project;
use App\ProjectEditRequest;
use App\User;
use App\UserProject;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;

class ProjectController extends Controller
{

    private $projectId;

    public function create(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'img' => 'required',
            'release_date' => 'required',
            'contributors.*.id' => 'required|exists:users,id',
            'contributors.*.role' => [
                'required',
                Rule::in(['artist', 'featured_artist', 'composer', 'producer',
                    'engineer', 'studio']),
            ],
            'platform' => [
                'required',
                Rule::in(['musixblvd', 'youtube', 'spotify']),
            ]
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $currentUser = auth('api')->user();

        $project = new Project();

        $project['title'] = $request->input('title');

        $project['release_date'] = $request->input('release_date');

        //Check Platform Type
        if($request->input('platform') == 'musixblvd'){
            $original_img = $request->file('img');
            $extension = $request->file('img')->getClientOriginalExtension();

            $newImgName =  time(). rand() . '.' . $extension;

            $mobileImgPath = public_path("img/projects/" . $newImgName);

            $compressedImageMobile = Image::make($original_img)->resize(200, 200);
            $compressedImageMobile->save($mobileImgPath);

            $project['img'] = $newImgName;
        }else{
            $project['img'] = $request->input('img');
        }

        if($request->has('apple_music') && !empty($request->input('apple_music')) && $this->isDuplicateUrl("apple_music", $request->input('apple_music'))){
            return $this->returnDuplicateError("Apple Music");
        }
        $project['apple_music'] = $this->urlToDomain($request->input('apple_music'));

        if($request->has('spotify') && !empty($request->input('spotify')) && $this->isDuplicateUrl("spotify", $request->input('spotify'))){
            return $this->returnDuplicateError("Spotify");
        }
        $project['spotify'] = $this->urlToDomain($request->input('spotify'));

        if($request->has('pandora') && !empty($request->input('pandora')) && $this->isDuplicateUrl("pandora", $request->input('pandora'))){
            return $this->returnDuplicateError("Pandora");
        }
        $project['pandora'] = $this->urlToDomain($request->input('pandora'));

        if($request->has('google') && !empty($request->input('google')) && $this->isDuplicateUrl("google", $request->input('google'))){
            return $this->returnDuplicateError("Google");
        }
        $project['google'] = $this->urlToDomain($request->input('google'));

        if($request->has('amazon') && !empty($request->input('amazon')) && $this->isDuplicateUrl("amazon", $request->input('amazon'))){
            return $this->returnDuplicateError("Amazon");
        }
        $project['amazon'] = $this->urlToDomain($request->input('amazon'));

        if($request->has('deezer') && !empty($request->input('deezer')) && $this->isDuplicateUrl("deezer", $request->input('deezer'))){
            return $this->returnDuplicateError("Deezer");
        }
        $project['deezer'] = $this->urlToDomain($request->input('deezer'));

        if($request->has('rhapsody') && !empty($request->input('rhapsody')) && $this->isDuplicateUrl("rhapsody", $request->input('rhapsody'))){
            return $this->returnDuplicateError("Rhapsody");
        }
        $project['rhapsody'] = $this->urlToDomain($request->input('rhapsody'));

        if($request->has('tidal') != '' && !empty($request->input('tidal')) && $this->isDuplicateUrl("tidal", $request->input('tidal'))){
            return $this->returnDuplicateError("Tidal");
        }
        $project['tidal'] = $this->urlToDomain($request->input('tidal'));

        if($request->has('youtube') && !empty($request->input('youtube')) && $this->isDuplicateUrl("youtube", $request->input('youtube'))){
            return $this->returnDuplicateError("Youtube");
        }
        $project['youtube'] = $this->urlToDomain($request->input('youtube'));

        if($request->has('xbox_music') != '' && !empty($request->input('xbox_music')) && $this->isDuplicateUrl("xbox_music", $request->input('xbox_music'))){
            return $this->returnDuplicateError("Xbox Music");
        }
        $project['xbox_music'] = $this->urlToDomain($request->input('xbox_music'));

        if($request->has('sound_cloud') && !empty($request->input('sound_cloud')) && $this->isDuplicateUrl("sound_cloud", $request->input('sound_cloud'))){
            return $this->returnDuplicateError("Sound Cloud");
        }
        $project['sound_cloud'] = $this->urlToDomain($request->input('sound_cloud'));

        //Check if audio file uploaded
        if($request->hasFile('audio')){
            $original_audio = $request->file('audio');
            $extension = $request->file('audio')->getClientOriginalExtension();
            $newAudioName =  time(). rand() . '.' . $extension;
            $original_audio->storeAs('audio/projects', $newAudioName, 'public');
            $project['audio'] = $newAudioName;
        }

        if($project->save()) {

            //Associate project to owner
            $userProject = new UserProject([
                "user_id" => $currentUser['id'],
                "project_id" => $project['id'],
                "role" => "creator"
            ]);

            $userProject['is_approved'] = true;
            $userProject->save();

            //Associate project to contributors
            if($request->has('contributors')){

                $contributors = $request->input('contributors');

                foreach($contributors as $key => $contributor)
                {
                    //Associate project to contributors
                    $userProject = new UserProject([
                        "user_id" => $contributor['id'],
                        "project_id" => $project['id'],
                        "role" => $contributor['role']
                    ]);

                    if($currentUser['id'] == $contributor['id'])
                        $userProject['is_approved'] = true;

                    $userProject->save();

                    if($currentUser['id'] != $contributor['id']){
                        //Save activity
                        $activity = new Activity();
                        $activity['sender_id'] = $currentUser['id'];
                        $activity['request_id'] = $userProject['id'];
                        $activity['message'] = "Requested to add you on his project \"" . $project['title'] . "\" as a ". strtolower($contributor['role']);
                        $activity['message_es'] = "Se le solicitó que lo agregue a su proyecto \"" . $project['title'] . "\" como producción de ". strtolower($contributor['role']);
                        $activity['message_fr'] = "A demandé de vous ajouter sur son projet \"" . $project['title'] . "\" en tant que ". strtolower($contributor['role']);
                        $activity['message_zh'] = "要求將您添加到他的項目 \"" . $project['title'] . "\" 中，作為 ". strtolower($contributor['role']);
                        $activity['type'] = "project";
                        $activity['user_id'] = $contributor['id'];
                        $activity->save();

                        //Send Push Notification
                        $this->sendPushNotification($currentUser['id'], $contributor['id'],
                            $activity['message']);
                    }
                }

            }

            $response = [
                'msg' => 'Project Created',
            ];

            return response()->json($response, 201);

        }

        $response = [
            'msg' => 'An error occurred',
        ];

        return response()->json($response, 404);
    }

    public function checkUpdateApproval(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $currentUser = auth('api')->user();

        $project = Project::find($request->input('project_id'));

        //Check if project has contributors
        $projectContributors = UserProject::where([
            ['project_id', $request->input('project_id')],
            ['user_id', '!=', $currentUser['id']],
            ['is_approved', 1]
        ])->get();

        if(count($projectContributors) == 0){

            $response = [
                'msg' => 'No request required you can update the project'
            ];

            return response()->json($response, 200);

        }else{

            //Check if edit request approved

            $projectEditRequests = ProjectEditRequest::where([
                ['project_id', $request->input('project_id')],
                ['user_id', $currentUser['id']]
            ])->get();

            if(count($projectEditRequests) > 0){ //Check if all contributors approved the edit

                foreach($projectEditRequests as $key => $projectRequest){
                    if(!$projectRequest['is_approved']){
                        $response = [
                            'msg' => 'Edit request not approved by all contributors yet',
                        ];

                        return response()->json($response, 422);
                    }
                }

                $response = [
                    'msg' => 'Request approved you can edit the project'
                ];

                return response()->json($response, 200);

            }

            //Add New edit request
            foreach($projectContributors as $key => $contributor){

                if(!ProjectEditRequest::where([
                    ['project_id', $request->input('project_id')],
                    ['user_id', $currentUser['id']],
                    ['contributor_id', $contributor['user_id']]
                ])->first()){

                    $projectEditRequests = new ProjectEditRequest([
                        'project_id' => $request->input('project_id'),
                        'user_id' => $currentUser['id'],
                        'contributor_id' => $contributor['user_id'],
                    ]);

                    $projectEditRequests->save();

                    //Save activity
                    $activity = new Activity();
                    $activity['sender_id'] = $currentUser['id'];
                    $activity['request_id'] = $projectEditRequests['id'];
                    $activity['message'] = "Requested to update the project \"" . $project['title'] . "\"";
                    $activity['message_es'] = "Solicito actualizar el proyecto \"" . $project['title'] . "\"";
                    $activity['message_fr'] = "A demandé de mettre à jour le projet \"" . $project['title'] . "\"";
                    $activity['message_zh'] = "要求更新項目 \"" . $project['title'] . "\"";
                    $activity['type'] = "project_edit";
                    $activity['user_id'] = $contributor['user_id'];
                    $activity->save();

                    //Send Push Notification
                    $this->sendPushNotification($currentUser['id'], $contributor['user_id'],
                        $activity['message']);
                }

            }

            $response = [
                'msg' => 'Project edit request created. You will be notified once it is approved by all other contributors',
            ];

            return response()->json($response, 201);
        }
    }

    public function update(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'contributors.*.id' => 'required|exists:users,id',
            'contributors.*.role' => [
                'required',
                Rule::in(['artist', 'featured_artist', 'composer', 'producer',
                    'engineer', 'studio']),
            ]
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $currentUser = auth('api')->user();

        $project = Project::where('id', $request->input('project_id'))->first();

        //============Check if project has contributors============//
        $projectContributors = UserProject::where([
            ['project_id', $request->input('project_id')],
            ['user_id', '!=', $currentUser['id']],
            ['is_approved', true]
        ])->get();

        if(count($projectContributors) > 0){

            //============Check If Project edit request is valid==========//
            $projectEditRequests = ProjectEditRequest::where([
                ['project_id', $project['id']],
                ['user_id', $currentUser['id']]
            ])->get();

            if(count($projectEditRequests) > 0){ //Check if all contributors approved the edit

                foreach($projectEditRequests as $key => $projectRequest){
                    if(!$projectRequest['is_approved']){
                        $response = [
                            'error' => 'Edit request not approved by all contributors yet',
                        ];

                        return response()->json($response, 422);
                    }
                }

            }else{

                $response = [
                    'error' => 'You need to request to update project first!',
                ];

                return response()->json($response, 422);

            }

        }

        //Delete Edit Project Request
        ProjectEditRequest::where([
            ['project_id', $project['id']],
            ['user_id', $currentUser['id']]
        ])->delete();

        //-------Update Contributors-------//
        $contributors = $request->input('contributors');

        //Check deleted contributors
        $oldContributors = UserProject::where('project_id', $project['id'])->get();

        foreach($oldContributors as $key => $oldContributor){

            $isContributorExists = false;

            foreach($contributors as $innerKey => $contributor){

                if($oldContributor->user_id ==
                    $contributor['id'] &&
                    $oldContributor->role ==
                    $contributor['role']){
                    $isContributorExists = true;
                    break;
                }
            }

            if(!$isContributorExists)
                UserProject::where('id', $oldContributor->id)->delete();

        }

        //Associate project to contributors

        foreach($contributors as $key => $contributor)
        {
            //Check if contributor already exists
            if(!UserProject::where([
                ['project_id', $project['id']],
                ['user_id', $contributor['id']],
                ['role', $contributor['role']]
            ])->first()){

                //Associate project to contributors
                $userProject = new UserProject([
                    "user_id" => $contributor['id'],
                    "project_id" => $project['id'],
                    "role" => $contributor['role']
                ]);

                if($currentUser['id'] == $contributor['id']){
                    $userProject['is_approved'] = true;
                }

                $userProject->save();

                if($currentUser['id'] != $contributor['id']){
                    //Save activity
                    $activity = new Activity();
                    $activity['sender_id'] = $currentUser['id'];
                    $activity['request_id'] = $userProject['id'];
                    $activity['message'] = "Requested to add you on his project \"" . $project['title'] . "\" as a ". strtolower($contributor['role']);
                    $activity['message_es'] = "Se le solicitó que lo agregue a su proyecto \"" . $project['title'] . "\" como producción de ". strtolower($contributor['role']);
                    $activity['message_fr'] = "A demandé de vous ajouter sur son projet \"" . $project['title'] . "\" en tant que ". strtolower($contributor['role']);
                    $activity['message_zh'] = "要求將您添加到他的項目 \"" . $project['title'] . "\" 中，作為 ". strtolower($contributor['role']);
                    $activity['type'] = "project";
                    $activity['user_id'] = $contributor['id'];
                    $activity->save();

                    //Send Push Notification
                    $this->sendPushNotification($currentUser['id'], $contributor['id'],
                        $activity['message']);
                }

            }


        }

        $response = [
            'msg' => 'Project Updated',
        ];

        return response()->json($response, 200);
    }

    public function combine(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $currentUser = auth('api')->user();

        $project = Project::where('id', $request->input('project_id'))->first();

        if($request->input('apple_music') != '' && $this->isDuplicateUrlUpdate($project->id, "apple_music", $request->input('apple_music'))){
            return $this->returnDuplicateError("Apple Music");
        }
        $project->apple_music = $this->urlToDomain($request->input('apple_music'));

        if($request->input('spotify') != '' && $this->isDuplicateUrlUpdate($project->id,"spotify", $request->input('spotify'))){
            return $this->returnDuplicateError("Spotify");
        }
        $project->spotify = $this->urlToDomain($request->input('spotify'));

        if($request->input('pandora') != '' && $this->isDuplicateUrlUpdate($project->id,"pandora", $request->input('pandora'))){
            return $this->returnDuplicateError("Pandora");
        }
        $project->pandora = $this->urlToDomain($request->input('pandora'));

        if($request->input('google') != '' && $this->isDuplicateUrlUpdate($project->id,"google", $request->input('google'))){
            return $this->returnDuplicateError("Google");
        }
        $project->google = $this->urlToDomain($request->input('google'));

        if($request->input('amazon') != '' && $this->isDuplicateUrlUpdate($project->id,"amazon", $request->input('amazon'))){
            return $this->returnDuplicateError("Amazon");
        }
        $project->amazon = $this->urlToDomain($request->input('amazon'));

        if($request->input('deezer') != '' && $this->isDuplicateUrlUpdate($project->id,"deezer", $request->input('deezer'))){
            return $this->returnDuplicateError("Deezer");
        }
        $project->deezer = $this->urlToDomain($request->input('deezer'));

        if($request->input('rhapsody') != '' && $this->isDuplicateUrlUpdate($project->id,"rhapsody", $request->input('rhapsody'))){
            return $this->returnDuplicateError("Rhapsody");
        }
        $project->rhapsody = $this->urlToDomain($request->input('rhapsody'));

        if($request->input('tidal') != '' && $this->isDuplicateUrlUpdate($project->id,"tidal", $request->input('tidal'))){
            return $this->returnDuplicateError("Tidal");
        }
        $project->tidal = $this->urlToDomain($request->input('tidal'));

        if($request->input('youtube') != '' && $this->isDuplicateUrlUpdate($project->id,"youtube", $request->input('youtube'))){
            return $this->returnDuplicateError("Youtube");
        }
        $project->youtube = $this->urlToDomain($request->input('youtube'));

        if($request->input('xbox_music') != '' && $this->isDuplicateUrlUpdate($project->id,"xbox_music", $request->input('xbox_music'))){
            return $this->returnDuplicateError("Xbox Music");
        }
        $project->xbox_music = $this->urlToDomain($request->input('xbox_music'));

        if($request->input('sound_cloud') != '' && $this->isDuplicateUrlUpdate($project->id,"sound_cloud", $request->input('sound_cloud'))){
            return $this->returnDuplicateError("Sound Cloud");
        }
        $project->sound_cloud = $this->urlToDomain($request->input('sound_cloud'));

        if($project->save()) {

            $response = [
                'msg' => 'Project Combined',
            ];

            return response()->json($response, 201);

        }

        $response = [
            'msg' => 'An error occurred',
        ];

        return response()->json($response, 404);
    }

    public function isDuplicateUrl($attribute, $value){

        $project = Project::where($attribute, $this->urlToDomain($value))->first();

        if($project){
            $this->projectId = $project->id;
            return true;
        }

        return false;

    }

    public function isDuplicateUrlUpdate($projectId, $attribute, $value){

        $project = Project::where([
            [$attribute, $this->urlToDomain($value)],
            ['id', '!=', $projectId]
        ])->first();

        if($project){
            $this->projectId = $project->id;
            return true;
        }

        return false;

    }

    public function returnDuplicateError($attribute){
        $response = [
            'error' => 'Duplicate Streaming link For ' . $attribute,
            'projectId' => $this->projectId
        ];
        return response()->json($response, 422);
    }

    public function getAllProjects(){

        $projects = Project::paginate(30);

        return response()->json($projects, 200);
    }

    public function getProjectById(Request $request){

        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $project = Project
            ::with('artists', 'featured_artists', 'composers', 'producers', 'engineers',
                            'studios', 'contributors')
            ->where('id', $request->input('project_id'))
            ->first();

        return response()->json($project, 200);
    }

    public function getProjectByIdForUpdate(Request $request){

        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $project = Project::with('allUsers')
            ->where('id', $request->input('project_id'))
            ->first();

        foreach($project->allUsers as $key => $value)
        {
            switch ($value->role){

                case "artist": $project->allUsers[$key]["role"] = "Artist";
                break;

                case "featured_artist": $project->allUsers[$key]["role"] = "Featured Artist";
                    break;

                case "composer": $project->allUsers[$key]["role"] = "Composer";
                    break;

                case "producer": $project->allUsers[$key]["role"] = "Producer";
                    break;

                case "Engineer": $project->allUsers[$key]["role"] = "Engineer";
                    break;

                case "contributor": $project->allUsers[$key]["role"] = "Contributor";
                    break;

                case "studio": $project->allUsers[$key]["role"] = "Studio Production";
                    break;

            }
        }

        return response()->json($project, 200);
    }

    public function getMyProjects(){
        $currentUser = auth('api')->user();

        $projects = DB::table('user_project')
            ->select('projects.id', 'projects.title', 'projects.title', 'projects.img', 'projects.release_date',
                'projects.artist', 'projects.artist_id',
                'projects.featured_artist', 'projects.featured_artist_id',
                'projects.writer', 'projects.writer_id', 'projects.producer',
                'projects.producer_id', 'projects.engineer',
                'projects.engineer_id', 'projects.contributor', 'projects.contributor_id',
                'projects.studio', 'projects.studio_id', 'projects.apple_music',
                'projects.spotify', 'projects.pandora',
                'projects.google',
                'projects.amazon', 'projects.deezer', 'projects.rhapsody',
                'projects.tidal', 'projects.youtube', 'projects.xbox_music', 'projects.sound_cloud')
            ->Leftjoin('projects', 'user_project.project_id', '=', 'projects.id')
            ->where([['user_project.user_id', '=', $currentUser['id']],
                ['user_project.status', '=', 1]])
            ->get();

        foreach($projects as $key => $value)
        {
            //Format variable
            $projects[$key]->img = URL::to('img_mobile/projects/' . $projects[$key]->img);
        }

        return response()->json($projects, 200);
    }

    public function getUserProjectById(Request $request){

        $projects = DB::table('user_project')
            ->select('projects.id', 'projects.title', 'projects.title', 'projects.img', 'projects.release_date',
                'projects.artist', 'projects.artist_id',
                'projects.featured_artist', 'projects.featured_artist_id',
                'projects.writer', 'projects.writer_id', 'projects.producer',
                'projects.producer_id', 'projects.engineer',
                'projects.engineer_id', 'projects.contributor', 'projects.contributor_id',
                'projects.studio', 'projects.studio_id',
                'projects.apple_music',
                'projects.spotify', 'projects.pandora',
                'projects.google',
                'projects.amazon', 'projects.deezer', 'projects.rhapsody',
                'projects.tidal', 'projects.youtube', 'projects.xbox_music', 'projects.sound_cloud')
            ->Leftjoin('projects', 'user_project.project_id', '=', 'projects.id')
            ->where([['user_project.user_id', '=', $request->input('user_id')],
                    ['user_project.status', '=', '1']])
            ->get();

        foreach($projects as $key => $value)
        {
            //Format variable
            $projects[$key]->img = URL::to('img_mobile/projects/' . $projects[$key]->img);
        }

        return response()->json($projects, 200);
    }

    public function getProjectRequests(){
        $currentUser = auth('api')->user();

        $projects = DB::table('user_project')
            ->select('user_project.id', 'projects.title',
                'projects.img')
            ->leftJoin('projects', 'user_project.project_id',
                '=', 'projects.id')
            ->where([['user_project.user_id', '=', $currentUser['id']],
                ['user_project.status', '=', 0],
                ['user_project.type', '!=', 'project_request']])
            ->get();

        foreach($projects as $key => $value)
        {
            //Format variable
            $projects[$key]->img = URL::to('img_mobile/projects/' . $projects[$key]->img);
        }

        return response()->json($projects, 200);
    }

    public function verify(Request $request){
        $currentUser = auth('api')->user();

        $validator = Validator::make($request->all(), [
            'request_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        //-----Check if request is already created-----//
        $item = DB::table('user_project')
        ->where('id', '=', $request->input('request_id'))
        ->update(['status' => 1]);

        if($item){
            $response = [
                'msg' => 'Project Verified',
            ];

            return response()->json($response, 200);
        }else{
            $response = [
                'msg' => 'An error occurred',
            ];

            return response()->json($response, 404);
        }
    }

    public function decline(Request $request){
        $currentUser = auth('api')->user();

        $validator = Validator::make($request->all(), [
            'request_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        //-----Check if request is already created-----//
        $item = DB::table('user_project')
            ->where('id', '=', $request->input('request_id'))
            ->delete();

        if($item){
            $response = [
                'msg' => 'Request deleted',
            ];

            return response()->json($response, 200);
        }else{
            $response = [
                'msg' => 'An error occurred',
            ];

            return response()->json($response, 404);
        }
    }

    public function delete(Request $request){

        $validator = Validator::make($request->all(), [
            'project_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        //-----Check if request is already created-----//


        if(Project::find($request->input('project_id'))->delete()){
            $response = [
                'msg' => 'Project deleted',
            ];

            return response()->json($response, 200);
        }else{
            $response = [
                'msg' => 'An error occurred',
            ];

            return response()->json($response, 404);
        }
    }

    public function urlToDomain($url) {

        /*// in case scheme relative URI is passed, e.g., //www.google.com/
        $input = trim($url, '/');

        // If scheme not included, prepend it
        if (!preg_match('#^http(s)?://#', $input)) {
            $input = 'http://' . $input;
        }

        $urlParts = parse_url($input);

        // remove www
        $domain = preg_replace('/^www\./', '', $urlParts['host']);*/

        return $url;
    }

    public function claimCredits(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'role' => [
                'required',
                Rule::in(['artist', 'featured_artist', 'composer', 'producer',
                    'engineer', 'studio']),
            ]
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $currentUser = auth('api')->user();

        //Check if user already added before on same role
        $userProjectCheck = UserProject::where([['user_id', '=', $currentUser['id']],
                ['project_id', '=', $request->input('project_id')],
                ['role', '=', $request->input('role')]
                    ])
            ->first();

        if($userProjectCheck){

            switch($userProjectCheck['is_approved']){
                case false:{

                    $response = [
                        'error' => 'You request to be added on that project still pending approval',
                    ];

                    return response()->json($response, 401);

                }

               case true: {

                    $response = [
                        'error' => 'You\'re already member of that project as ' . $userProjectCheck['role'],
                    ];

                    return response()->json($response, 401);

               }
            }
        }


        //Associate project to user
        $userProject = new UserProject([
            "user_id" => $currentUser['id'],
            "project_id" => $request->input('project_id'),
            "role" => $request->input('role')
        ]);

        $userProject->save();

        $project = Project::find($request->input('project_id'));

        $userProjectCreator = UserProject::where([
            ['project_id', $project['id']],
            ['role', 'creator']
        ])->first();

        $activity = new Activity();
        $activity['sender_id'] = $currentUser['id'];
        $activity['request_id'] = $userProject['id'];
        $activity['message'] = "Requested to be added on project \"" . $project['title'] . "\" as a ". strtolower($request->input('role'));
        $activity['message_es'] = "Solicitado para ser agregado al proyecto \"" . $project['title'] . "\" como un ". strtolower($request->input('role'));
        $activity['message_fr'] = "A demandé à être ajouté au projet \"" . $project['title'] . "\" comme un ". strtolower($request->input('role'));
        $activity['message_zh'] = "A demandé à être ajouté au projet \"" . $project['title'] . "\" 作為一個 ". strtolower($request->input('role'));
        $activity['type'] = "project";
        $activity['user_id'] = $userProjectCreator['user_id'];
        $activity->save();

        $response = [
            'msg' => 'Request sent',
        ];

        return response()->json($response, 200);
    }

    public function verifyProjectContributorRequest(Request $request){

        $validator = Validator::make($request->all(), [
            'activity_id' => 'required|exists:activities,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

//        $currentUser = auth('api')->user();

        $activity = Request::find($request->input('activity_id'));

        $item = DB::table('user_project')
            ->where('id', '=', $activity['request_id'])
            ->update(['status' => 1]);

        if($item){
            $response = [
                'msg' => 'Project Verified',
            ];

            return response()->json($response, 200);
        }else{
            $response = [
                'msg' => 'An error occurred',
            ];

            return response()->json($response, 404);
        }
    }

    private function sendPushNotification($senderUserId, $receiverUserId, $message){

        // Send Push Notification
        $senderUser = User::find($senderUserId);

        $receiverUser = User::find($receiverUserId);

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
                        'en' => $senderUser['name'] . ' ' . $message
                    ],

                    'large_icon' => $senderUser['img'],

                    'android_group' => $senderUser['id']
                ]
            ]);

        } catch (GuzzleException $e) {
            return $e->getMessage();
        }


        return "Notification Sent";
    }

    public function contributorSearch(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 422);
        }

        $searchQuery = $request->input('name');

        $users = User::where([
            ['name', 'LIKE', '%' . $searchQuery . '%'],
            ['type', '!=', 'manager'],
            ['has_profile', true]
        ])
            ->get();

        return response()->json($users, 200);
    }

}
