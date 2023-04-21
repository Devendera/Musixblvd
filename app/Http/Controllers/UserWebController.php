<?php
namespace App\Http\Controllers;

use App\Activity;
use App\Advance;
use App\Connection;
use App\ConnectionProjectMapping;
use App\Contact;
use App\Country;
use App\Craft;
use App\Creative;
use App\Gender;
use App\Genre;
use App\Http\Controllers;
use App\Manager;
use App\MediaProvider;
use App\Message;
use App\Platform;
use App\Project;
use App\ProjectEditRequest;
use App\RoleUser;
use App\Studio;
use App\StudioImage;
use App\Type;
use App\User;
use App\UserPlatforms;
use App\UserProject;
use Artisan;
use DB;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Session;
use URL;

class UserWebController extends Controller
{
    protected $redirectTo = 'login',
    $guard = 'web';
/* Function for return view for index page */
    public function index(Request $request)
    {
        return view('index');
    }

/* Function for return view for login page */
    public function login(Request $request)
    {
        $count=0;
        return view('welcome', compact('count'));
    }

/* Function for return view for user register page */
    public function register(Request $request)
    {
        $countries = \DB::table('countries_web')->get();
        return view('web.register', compact('countries'));
    }

/* Function for get state list on ajax call */
    public function getState(Request $request)
    {
        $countryId = $request->country;
        $state = \DB::table('states_web')->where('country_id', '=', $countryId)->get('name');
        foreach ($state as $value) {
            echo "<option>" . $value->name . "</option>";
        }
    }

/* Function for return view for profile page */
    public function profile(Request $request)
    {
        $count = "";
        return view('web.profile', compact('count'));
    }

/* Function for return view for add project page */
    public function addProject(Request $request)
    {
        $projects = \DB::table('projects')->get();

        $user = Auth::guard('web')->user();
        $userId = 0;

        if ($user != null) {
            $userId = $user->id;
        }
        $count = "";
        return view('web.addproject', compact('projects', 'userId', 'count'));
    }

/* Function for return view for create project page */
    public function createProject(Request $request)
    {
        $users = \DB::table('users')->get();
        $roles = \DB::table('roles')->get();
        $platforms = \DB::table('platforms')->get();
        $count = "";
        return view('web.createproject', compact('count', 'users', 'roles', 'platforms'));
    }

/* Function for return view for project information page */
    public function projectInfo(Request $request)
    {
        $detail = \DB::table('projects')->where('id', '=', $request->id)->first();
        $contributors = \DB::table('project_edit_requests')->where('project_id', '=', $detail->id)
            ->join('users', 'users.id', '=', 'project_edit_requests.contributor_id')
            ->where('project_edit_requests.is_approved', '=', 1)
            ->get();
        $contributorsSender = \DB::table('project_edit_requests')->where('project_id', '=', $detail->id)
            ->join('users', 'users.id', '=', 'project_edit_requests.user_id')
            ->where('project_edit_requests.is_approved', '=', 1)
            ->get();

        $platforms = \DB::table('user_projects')
            ->join('user_platforms', 'user_platforms.user_id', '=', 'user_projects.user_id')
            ->where('user_projects.project_id', '=', $detail->id)
            ->get();
        $requestConnections = $this->getConnectionRequests();
        $message = $request->message;
        $roles = \DB::table('roles')->get();
        $count = "";
        return view('web.projectinfo', compact('contributorsSender', 'roles', 'count', 'detail', 'contributors', 'platforms', 'requestConnections', 'message'));
    }

/* Function for return view for creative register page */
    public function creative(Request $request)
    {
        $genres = Genre::select('id', 'title')->get();
        $genders = Gender::select('id', 'title')->get();
        $crafts = Craft::select('id', 'title')->get();
        $types = Type::select('id', 'title')->get();
        $platforms = Platform::select('id', 'title')->get();
        $count = "";
        return view('web.creative', compact('genres', 'genders', 'crafts', 'types', 'platforms', 'count'));
    }

/* Function for return view for studio register page */
    public function studio(Request $request)
    {
        $count = "";
        return view('web.studio', compact('count'));
    }

/* Function for return view for manager register page */
    public function manager(Request $request)
    {
        $count = "";
        return view('web.manager', compact('count'));
    }

/* Function for return view for managers page */
    public function managerView(Request $request)
    {
        $count = "";
        return view('web.manager_view', compact('count'));
    }

    /* Function for return view for studio map page */
    public function studioMapView(Request $request)
    {
        $studioArray = [];
        $studioData = \DB::table('studios')
            ->join('studio_images', 'studio_images.studio_id', '=', 'studios.id')
            ->get();
        foreach ($studioData as $sd) {
            $studioArray[] = [$sd->longitude, $sd->latitude, $sd->address, url('public/studio/' . $sd->image)];
        }
        $studioJsonData = json_encode($studioArray);
        $count = "";
        return view('web.studio_map_view', compact('studioJsonData', 'count'));
    }

    /* Function for return view for studio page */
    public function studioView(Request $request)
    {
        $count = "";
        return view('web.studio_view', compact('count'));
    }

    /* Function for return view for studio login user profile */
    public function studioProfile(Request $request)
    {
        $userId = Auth::guard('web')->user()->id;
        $studioId = \DB::table('studios')->where('user_id', '=', $userId)->first();
        $userData = \DB::table('users')->join('studios', 'studios.user_id', '=', 'users.id')->leftJoin('studio_images', 'studio_images.studio_id', '=', 'studios.id')->where('users.id', '=', $userId)->first();
        $userImg = \DB::table('studios')
            ->join('studio_images', 'studios.id', '=', 'studio_images.studio_id')
            ->where('studios.id', '=', $studioId->id)
            ->get();
        $profileImg = \DB::table('studios')
            ->join('studio_images', 'studios.id', '=', 'studio_images.studio_id')
            ->where('studios.id', '=', $studioId->id)
            ->first();
        $projectData = \DB::table('projects')
            ->join('user_projects', 'projects.id', '=', 'user_projects.project_id')
            ->where('user_projects.user_id', '=', $userId)
            ->get();
        $numberOfConnections = Connection::
            where([['connected_user_id', '=', $userId], ['type', '=', 'connection'], ['is_approved', '=', '1']])->
            orWhere([['user_id', '=', $userId], ['type', '=', 'connection'], ['is_approved', '=', '1']])->count();
        $numberOfProjects = UserProject::
            where([['user_id', '=', $userId]])->count();
        $count = "";
        return view('web.studio_profile', compact('userData', 'userImg', 'count', 'profileImg', 'projectData', 'numberOfConnections', 'numberOfProjects'));
    }

/* Function for return view for managers profile page */
    public function managerProfile(Request $request)
    {
        $clientData = [];
        $user = Auth::guard('web')->user();
        $userId = $user->id;
        $userData = \DB::table('users')
            ->join('managers', 'users.id', '=', 'managers.user_id')
            ->where('users.id', '=', $userId)
            ->first();
        $name = $userData->name;
        $nameArr = explode(" ", $name);
        //get  clients
        $sender = \DB::table('connections')
            ->select('users.id', 'users.name', 'users.img', 'users.type')
            ->Rightjoin('users', 'connections.connected_user_id', '=', 'users.id')
            ->where([
                ['connections.user_id', '=', $userId],
                ['connections.is_approved', '=', '1'],
                ['connections.type', '=', 'management']])
            ->get();
        $reciever = \DB::table('connections')
            ->select('users.id', 'users.name', 'users.img', 'users.type')
            ->Rightjoin('users', 'connections.user_id', '=', 'users.id')
            ->where([
                ['connections.connected_user_id', '=', $userId],
                ['connections.is_approved', '=', '1'],
                ['connections.type', '=', 'management']])
            ->get();
        //count number of connections
        $numberOfConnections = Connection::
            where([['connected_user_id', '=', $userId], ['type', '=', 'connection'], ['is_approved', '=', '1']])->
            orWhere([['user_id', '=', $userId], ['type', '=', 'connection'], ['is_approved', '=', '1']])->count();
        //count number of clients
        $numberOfClients = Connection::
            where([['connected_user_id', '=', $userId], ['type', '=', 'management'], ['is_approved', '=', '1']])->
            orWhere([['user_id', '=', $userId], ['type', '=', 'management'], ['is_approved', '=', '1']])->count();
        $count = "";
        return view('web.manager_profile', compact('userData', 'nameArr', 'count', 'numberOfConnections', 'reciever', 'sender', 'numberOfClients'));
    }

    /**
     * Function for return view for managers view profile page for not login user.
     * */
    public function managerViewProfile(Request $request, $id)
    {
        $status = config('constants.AddConnection');
        $request = config('constants.AddConnection');
        $userData = \DB::table('users')
            ->join('managers', 'users.id', '=', 'managers.user_id')
            ->where('users.id', '=', $id)
            ->first();
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            $userId = $user->id;
            $connection = \DB::table('connections')
                ->where([['connected_user_id', '=', $id], ['user_id', '=', $userId], ['type', '=', "management"]])
                ->orwhere([['connected_user_id', '=', $userId], ['user_id', '=', $id], ['type', '=', "management"]])
                ->first();

            if ($connection != "") {
                if ($connection->is_approved == "1") {
                    $request = config('constants.Connected');
                } elseif ($connection->is_approved == "") {
                    $request = config('constants.Pending');
                } else {
                    $request = config('constants.Request');
                }
            } else {
                $request = config('constants.Request');
            }
            $connectionManagement = \DB::table('connections')
                ->where([['connected_user_id', '=', $id], ['user_id', '=', $userId], ['type', '=', "connection"]])
                ->orwhere([['connected_user_id', '=', $userId], ['user_id', '=', $id], ['type', '=', "connection"]])
                ->first();
            if ($connectionManagement != "") {
                if ($connectionManagement->is_approved == "1") {
                    $status = config('constants.Connected');
                } elseif ($connectionManagement->is_approved == "") {
                    $status = config('constants.Pending');
                } else {
                    $status = config('constants.AddConnection');
                }
            } else {
                $status = config('constants.AddConnection');
            }
        }
        //get  clients
        $sender = \DB::table('connections')
            ->select('users.id', 'users.name', 'users.img', 'users.type')
            ->Rightjoin('users', 'connections.connected_user_id', '=', 'users.id')
            ->where([
                ['connections.user_id', '=', $id],
                ['connections.is_approved', '=', '1'],
                ['connections.type', '=', 'management']])
            ->get();
        $reciever = \DB::table('connections')
            ->select('users.id', 'users.name', 'users.img', 'users.type')
            ->Rightjoin('users', 'connections.user_id', '=', 'users.id')
            ->where([
                ['connections.connected_user_id', '=', $id],
                ['connections.is_approved', '=', '1'],
                ['connections.type', '=', 'management']])
            ->get();
        //count number of connections
        $numberOfConnections = Connection::
            where([['connected_user_id', '=', $id], ['type', '=', 'connection'], ['is_approved', '=', '1']])->
            orWhere([['user_id', '=', $id], ['type', '=', 'connection'], ['is_approved', '=', '1']])->count();
        //count number of connections
        $numberOfClients = Connection::
            where([['connected_user_id', '=', $id], ['type', '=', 'management'], ['is_approved', '=', '1']])->
            orWhere([['user_id', '=', $id], ['type', '=', 'management'], ['is_approved', '=', '1']])->count();
        $count = "";
        return view('web.manager_profile_view', compact('sender', 'count', 'reciever', 'userData', 'status', 'request', 'numberOfConnections', 'numberOfClients'));
    }

/* Function for return view for managers view profile page for not login user */
    public function studioViewProfile(Request $request, $id)
    {
        $status = config('constants.AddConnection');
        $request = config('constants.AddConnection');
        $studioId = \DB::table('studios')->where('user_id', '=', $id)->first();
        $userData = \DB::table('users')
            ->join('studios', 'users.id', '=', 'studios.user_id')
            ->join('studio_images', 'studios.id', '=', 'studio_images.studio_id')
            ->where('users.id', '=', $id)
            ->first();
        $userImg = \DB::table('studios')
            ->join('studio_images', 'studios.id', '=', 'studio_images.studio_id')
            ->where('studios.id', '=', $studioId->id)
            ->get();
        $profileImg = \DB::table('studios')
            ->join('studio_images', 'studios.id', '=', 'studio_images.studio_id')
            ->where('studios.id', '=', $studioId->id)
            ->first();
        $projectData = \DB::table('projects')
            ->join('user_projects', 'projects.id', '=', 'user_projects.project_id')
            ->where('user_projects.user_id', '=', $id)
            ->get();
        $numberOfConnections = Connection::
            where([['connected_user_id', '=', $id], ['type', '=', 'connection'], ['is_approved', '=', '1']])->
            orWhere([['user_id', '=', $id], ['type', '=', 'connection'], ['is_approved', '=', '1']])->count();
        $numberOfProjects = UserProject::
            where([['user_id', '=', $id]])->count();
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            $userId = $user->id;

            $connection = \DB::table('connections')
                ->where([['connected_user_id', '=', $id], ['user_id', '=', $userId]])
                ->orwhere([['connected_user_id', '=', $userId], ['user_id', '=', $id]])
                ->first();
            if ($connection) {
                if ($connection->is_approved == "1") {
                    $status = config('constants.Connected');
                } elseif ($connection->is_approved == "") {
                    $status = config('constants.Pending');
                } else {
                    $status = config('constants.AddConnection');
                }
            } else {
                $status = config('constants.AddConnection');
            }
        }
        $count = "";
        return view('web.studio_profile_view', compact('userData', 'count', 'numberOfConnections', 'numberOfProjects', 'userImg', 'profileImg', 'projectData', 'status'));
    }

/* Function for return view for creative view profile page for not login user */
    public function creativeViewProfile(Request $request, $id)
    {$clientData = [];
        $status = config('constants.AddConnection');
        $request = config('constants.AddConnection');
        $userData = User::select('users.*', 'creatives.type as creativesType', 'creatives.genre as creativesGenre', 'creatives.Craft as creativesCraft', 'creatives.Gender as creativesGnder', 'creatives.website as website', 'creatives.stage as stage', 'creatives.pro as pro', 'creatives.status as status', 'creatives.influencers as creativesInfluencers', 'creatives.social_media_links as links')->join('creatives', 'users.id', '=', 'creatives.user_id')->where('users.id', '=', $id)->with('platforms')->first();

        $genres = Genre::where('id', $userData->creativesGenre)->select('id', 'title')->first();
        $genders = Gender::where('id', $userData->creativesGnder)->select('id', 'title')->first();
        $crafts = Craft::where('id', $userData->creativesCraft)->select('id', 'title')->first();
        $types = Type::where('id', $userData->creativesType)->select('id', 'title')->first();
        $platforms = Platform::select('id', 'title')->get();

        $numberOfConnections = Connection::
            where([['connected_user_id', '=', $id], ['type', '=', 'connection'], ['is_approved', '=', '1']])->
            orWhere([['user_id', '=', $id], ['type', '=', 'connection'], ['is_approved', '=', '1']])->count();
        $numberOfProjects = UserProject::
            where([['user_id', '=', $id]])->count();
        $projectData = \DB::table('projects')
            ->join('user_projects', 'projects.id', '=', 'user_projects.project_id')
            ->where('user_projects.user_id', '=', $id)
            ->get();
        $sender = \DB::table('connections')
            ->select('users.id', 'users.name', 'users.img')
            ->Rightjoin('users', 'connections.connected_user_id', '=', 'users.id')
            ->where([
                ['connections.user_id', '=', $id],
                ['connections.is_approved', '=', '1'],
                ['connections.type', '=', 'management']])
            ->get();
        $reciever = \DB::table('connections')
            ->select('users.id', 'users.name', 'users.img')
            ->Rightjoin('users', 'connections.user_id', '=', 'users.id')
            ->where([
                ['connections.connected_user_id', '=', $id],
                ['connections.is_approved', '=', '1'],
                ['connections.type', '=', 'management']])
            ->get();
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            $userId = $user->id;
            $connection = \DB::table('connections')->where([['connected_user_id', '=', $id], ['user_id', '=', $userId]])->first();

            $connection = \DB::table('connections')
                ->where([['connected_user_id', '=', $id], ['user_id', '=', $userId], ['type', '=', "management"]])
                ->orwhere([['connected_user_id', '=', $userId], ['user_id', '=', $id], ['type', '=', "management"]])
                ->first();

            if ($connection != "") {
                if ($connection->is_approved == "1") {
                    $request = config('constants.Connected');
                } elseif ($connection->is_approved == "") {
                    $request = config('constants.Pending');
                } else {
                    $request = config('constants.Request');
                }
            } else {
                $request = config('constants.Request');
            }
            $connectionManagement = \DB::table('connections')
                ->where([['connected_user_id', '=', $id], ['user_id', '=', $userId], ['type', '=', "connection"]])
                ->orwhere([['connected_user_id', '=', $userId], ['user_id', '=', $id], ['type', '=', "connection"]])
                ->first();
            if ($connectionManagement != "") {
                if ($connectionManagement->is_approved == "1") {
                    $status = config('constants.Connected');
                } elseif ($connectionManagement->is_approved == "") {
                    $status = config('constants.Pending');
                } else {
                    $status = config('constants.AddConnection');
                }
            } else {
                $status = config('constants.AddConnection');
            }
        }
        $count = "";
        return view('web.creative_profile_view', compact('reciever', 'sender', 'count', 'numberOfConnections', 'numberOfProjects', 'userData', 'status', 'projectData', 'genres', 'genders', 'types', 'crafts', 'platforms', 'request'));
    }
    /* Function for return view for contact page */
    public function contact(Request $request)
    {
        $count = "";
        return view('web.contact', compact('count'));
    }

    /* Function for return view for faq page */
    public function faq(Request $request)
    {
        $count = "";
        return view('web.faq', compact('count'));
    }

    /* Function for return view for index page */
    public function userIndex(Request $request)
    {
        $genres = Genre::select('id', 'title')->get();
        $genders = Gender::select('id', 'title')->get();
        $types = Type::select('id', 'title')->get();
        $countries = Country::select('id', 'name')->get();
        $crafts = Craft::select('id', 'title')->get();
        $user = Auth::guard($this->guard)->user();
        $this->updateLocalLanguage();
        $startConnectionRange = '';
        $endConnectionRange = '';
        $startprojectRange = '';
        $endprojectRange = '';
        if (Input::has('connection') && $request->input('connection') != '') {
            $connectionRange = explode('-', $request->input('connection'));
            $startConnectionRange = $connectionRange[0];
            $endConnectionRange = $connectionRange[1];
        }
        if (Input::has('project') && $request->input('project') != '') {
            $projectRange = explode('-', $request->input('project'));
            $startprojectRange = $projectRange[0];
            $endprojectRange = $projectRange[1];
        }
        $searchQuery = $request->input('search');
        $users = User::with('creative', 'manager', 'studio')
            ->with('sender')
            ->with('receiver')
            ->withCount('connections')
            ->withCount('projects')
            ->where('has_profile', true);

        if (isset($user) && !empty($user)) {
            $users = $users->where('id', '!=', $user->id);
        }
        $conditionsCreative = array();
        $conditionsUser = array();
        $conditionStudio = array();
        if (Input::has('type') && $request->input('type') != '') {
            array_push($conditionsCreative, ['type', '=', $request->input('type')]);
            array_push($conditionStudio, ['type', '=', $request->input('type')]);
        }
        if (Input::has('artistry') && $request->input('artistry') != '') {
            array_push($conditionsCreative, ['craft', '=', $request->input('artistry')]);
        }

        if (Input::has('genre') && $request->input('genre') != '') {
            array_push($conditionsCreative, ['genre', '=', $request->input('genre')]);
        }

        if (Input::has('gender') && $request->input('gender') != '') {
            array_push($conditionsCreative, ['gender', '=', $request->input('gender')]);
        }

        if (Input::has('country') && $request->input('country') != '') {
            array_push($conditionsUser, ['country', '=', $request->input('country')]);
        }

        if (Input::has('state') && $request->input('state') != '') {
            array_push($conditionsUser, ['state', '=', $request->input('state')]);
        }

        if (Input::has('city') && $request->input('city') != '') {
            array_push($conditionsUser, ['city', '=', $request->input('city')]);
        }

        if ($searchQuery && $searchQuery != '') {
            $users = $users->whereHas('creative', function ($query) use ($searchQuery, $conditionsCreative) {
                $query->where('name', 'LIKE', '%' . $searchQuery . '%')
                    ->where($conditionsCreative);
            })
                ->orWhereHas('manager', function ($query) use ($searchQuery) {
                    $query->where('name', 'LIKE', '%' . $searchQuery . '%');
                })
                ->orWhereHas('studio', function ($query) use ($searchQuery, $conditionStudio) {
                    $query->where('name', 'LIKE', '%' . $searchQuery . '%')
                        ->where($conditionStudio);
                });
        } elseif (!empty($conditionsCreative)) {
            $users = $users->whereHas('creative', function ($query) use ($conditionsCreative) {
                $query->where($conditionsCreative);
            });
        } elseif (!empty($conditionStudio)) {
            $users = $users->whereHas('studio', function ($query) use ($conditionStudio) {
                $query->where($conditionStudio);
            });
        }
        if (Input::has('connection') && $startConnectionRange != '' && $endConnectionRange != '') {
            $users = $users->has('connections', '>=', $startConnectionRange)
                ->has('connections', '<=', $endConnectionRange)
                ->orderBy('connections_count', 'desc');
        }
        if (Input::has('project') && $startprojectRange != '' && $endprojectRange != '') {
            $users = $users->has('projects', '>=', $startConnectionRange)
                ->has('projects', '<=', $endConnectionRange)
                ->orderBy('projects_count', 'desc');
        }
        if (!empty($conditionsUser)) {
            $users = $users->where($conditionsUser);
        }
        $users = $users->paginate(config('constants.PaginateArray.1'));
        foreach ($users as $key => $value) {
            if (!empty($value['sender']) || !empty($value['receiver'])) {
                if (isset($value['sender']['is_approved']) || isset($value['receiver']['is_approved'])) {
                    $users[$key]->connection = config('constants.Connected');
                } else {
                    $users[$key]->connection = config('constants.Pending');
                }

            } else {
                $users[$key]->connection = config('constants.NotConnected');
            }
            switch ($users[$key]->type) {
                case "Creative":{
                        unset($users[$key]->manager);
                        unset($users[$key]->studio);
                    }
                    break;

                case "Manager":{
                        unset($users[$key]->creative);
                        unset($users[$key]->studio);
                    }
                    break;

                case "Studio":{
                        unset($users[$key]->creative);
                        unset($users[$key]->manager);
                    }
                    break;
            }
        }
        $searchGender = $request->input('gender');
        $searchGenre = $request->input('genre');
        $searchType = $request->input('type');
        $searchState = $request->input('state');
        $searchCountry = $request->input('country');
        $searchArtistry = $request->input('artistry');
        $searchCity = $request->input('city');
        $searchConnection = $request->input('connection');
        $searchProject = $request->input('project');
        $count = "";
        if (Auth::guard('web')->check()) {
            $streamingData = \DB::table('media_providers')->where('user_id', '=', $user->id)->get();
        } else {
            $streamingData = '0';
        }
        if (count($users) > 0) {
            return view('web.user_index', compact('streamingData', 'count', 'users', 'genres', 'genders', 'crafts', 'types', 'countries', 'searchGender', 'searchGenre', 'searchType', 'searchState', 'searchCountry', 'searchArtistry', 'searchCity', 'searchConnection', 'searchProject', 'searchQuery'));
        } else {
            return view('web.user_index', compact('streamingData', 'count', 'users', 'genres', 'genders', 'crafts', 'types', 'countries', 'genres', 'genders', 'crafts', 'types', 'countries', 'searchGender', 'searchGenre', 'searchType', 'searchState', 'searchCountry', 'searchArtistry', 'searchCity', 'searchConnection', 'searchProject', 'searchQuery'))->withErrors(config('constants.MatchRecord'));
        }
    }

    /* Function for return view for edit studio profile page */
    public function studioEditProfile(Request $request)
    {
        $user = Auth::guard('web')->user();
        $userId = $user->id;
        $userData = \DB::table('users')
            ->join('studios', 'users.id', '=', 'studios.user_id')
            ->where('users.id', '=', $userId)
            ->first();
        $count = "";
        return view('web.studio_edit_profile', compact('userData', 'count'));
    }

/* Function for return view for edit manager profile page */
    public function editProfile(Request $request)
    {
        $user = Auth::guard('web')->user();
        $userId = $user->id;
        $userData = \DB::table('users')
            ->join('managers', 'users.id', '=', 'managers.user_id')
            ->where('users.id', '=', $userId)
            ->first();
        $name = $userData->name;
        $nameArr = explode(" ", $name);
        $count = "";
        return view('web.edit_profile', compact('userData', 'nameArr', 'count'));
    }

    /* Function for return view for update manager profile page */
    public function updateManager(Request $request)
    {
        $user = Auth::guard('web')->user();
        $userId = $user->id;
        $firstName = $request->input('firstname');
        $lastName = $request->input('lastname');
        $twitter = $request->input('twitter');
        $facebook = $request->input('facebook');
        $instagram = $request->input('instagram');
        $company = $request->input('company');
        $data = array(
            'management_company' => $company,
            'twitter' => $twitter,
            'facebook' => $facebook,
            'instagram' => $instagram,
        );
        $user = \DB::table('managers')->where('user_id', '=', $userId)->update($data);
        $name = array(
            'name' => $firstName . " " . $lastName,
            'register_flag' => 1,
        );
        $user = \DB::table('users')->where('id', '=', $userId)->update($name);
        if ($files = $request->file('profile')) {
            // Define upload path
            $destinationPath = public_path('img/users/'); // upload path
            $profileImage = date('YmdHis') . "." . $files->getClientOriginalExtension();
            $files->move($destinationPath, $profileImage);

            $insert['image'] = "$profileImage";
            $img = array('img' => $profileImage);

            $userImage = \DB::table('users')->where('id', '=', $userId)->update($img);}
        $userData = \DB::table('users')
            ->join('managers', 'users.id', '=', 'managers.user_id')
            ->where('users.id', '=', $userId)
            ->first();
        $name = $userData->name;
        $nameArr = explode(" ", $name);

        return redirect('/managerProfile');

    }

    /* Function for return view for update studio profile page */
    public function updateStudioProfile(Request $request)
    {
        $user = Auth::guard('web')->user();
        $userId = $user->id;

        $studioname = $request->input('studioname');
        $address = $request->input('address');
        $bookingemail = $request->input('bookingemail');
        $hourlyrate = $request->input('hourlyrate');
        $pro = $request->input('pro');
        $option = $request->input('radio');
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $data = array(
            'address' => $address,
            'booking_email' => $bookingemail,
            'hourly_rate' => $hourlyrate,
            'pro' => $pro,
            'type' => $option,
            'latitude' => $latitude,
            'longitude' => $longitude,
        );
        $user = \DB::table('studios')->where('user_id', '=', $userId)->update($data);
        $name = array(
            'name' => $studioname,
            'register_flag' => 1,
        );
        $user = \DB::table('users')->where('id', '=', $userId)->update($name);
        $studioId = \DB::table('studios')->where('user_id', '=', $userId)->first();
        $userData = \DB::table('users')->join('studios', 'studios.user_id', '=', 'users.id')->leftJoin('studio_images', 'studio_images.studio_id', '=', 'studios.id')->where('users.id', '=', $userId)->first();
        $userImg = \DB::table('studios')
            ->join('studio_images', 'studios.id', '=', 'studio_images.studio_id')
            ->where('studios.id', '=', $studioId->id)
            ->get();
        $profileImg = \DB::table('studios')
            ->join('studio_images', 'studios.id', '=', 'studio_images.studio_id')
            ->where('studios.id', '=', $studioId->id)
            ->first();
        $projectData = \DB::table('projects')
            ->join('user_projects', 'projects.id', '=', 'user_projects.project_id')
            ->where('user_projects.user_id', '=', $userId)
            ->get();
        $numberOfConnections = Connection::
            where([['connected_user_id', '=', $userId], ['type', '=', 'connection'], ['is_approved', '=', '1']])->
            orWhere([['user_id', '=', $userId], ['type', '=', 'connection'], ['is_approved', '=', '1']])->count();
        $numberOfProjects = UserProject::
            where([['user_id', '=', $userId]])->count();
        $count = "";
        return view('web.studio_profile', compact('userData', 'count', 'userImg', 'profileImg', 'numberOfConnections', 'numberOfProjects', 'projectData'));
    }

/* Function for return view for connection page */
    public function connection(Request $request)
    {
        $count = "";
        return view('web.connection', compact('count'));
    }

/* Function for register new manager in web based */
    public function registerManager(Request $request)
    {
        $user = Auth::guard('web')->user();
        $userId = $user->id;
        $firstName = $request->input('firstname');
        $lastName = $request->input('lastname');
        $twitter = $request->input('twitter');
        $facebook = $request->input('facebook');
        $instagram = $request->input('instagram');
        $company = $request->input('company');
        $data = array(
            'name' => $firstName . " " . $lastName,
            'register_flag' => 1,
            'has_profile' => 1,
        );

        $user = \DB::table('users')->where('id', '=', $userId)->update($data);
        if ($files = $request->file('profile')) {
            // Define upload path
            $destinationPath = public_path('img/users/'); // upload path
            $profileImage = date('YmdHis') . "." . $files->getClientOriginalExtension();
            $files->move($destinationPath, $profileImage);

            $insert['image'] = "$profileImage";
            $img = array('img' => $profileImage);

            $userImage = \DB::table('users')->where('id', '=', $userId)->update($img);}
        $manager = new Manager;
        $manager->management_company = $company;
        $manager->twitter = $twitter;
        $manager->facebook = $facebook;
        $manager->instagram = $instagram;
        $manager->user_id = $userId;
        $manager->save();
        return redirect('/user-index')->with('flash_message_success', "Registered Successfully.");
    }

    /* Function for register new studio in web based */
    public function registerStudio(Request $request)
    {
        $user = Auth::guard('web')->user();
        $userId = $user->id;
        $studioname = $request->input('studioname');
        $address = $request->input('address');
        $bookingemail = $request->input('bookingemail');
        $hourlyrate = $request->input('hourlyrate');
        $pro = $request->input('pro');
        $option = $request->input('radio');
        $data = array(
            'name' => $studioname,
            'register_flag' => 1,
            'has_profile' => 1,
        );
        $user = \DB::table('users')->where('id', '=', $userId)->update($data);
        $studio = new Studio;
        $studio->address = $address;
        $studio->booking_email = $bookingemail;
        $studio->hourly_rate = $hourlyrate;
        $studio->pro = $pro;
        $studio->type = $option;
        $studio->latitude = $request->input('latitude');
        $studio->longitude = $request->input('longitude');
        $studio->user_id = $userId;
        $studio->save();
        if ($files = $request->allFiles()) {
            foreach ($_FILES['files']['tmp_name'] as $key => $value) {
                $file_tmpname = $_FILES['files']['tmp_name'][$key];
                $file_name = $_FILES['files']['name'][$key];
                $file_size = $_FILES['files']['size'][$key];
                $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
                $destinationPath = public_path('/studio/'); // upload path
                // Set upload file path
                $filepath = $destinationPath . $file_name;
                $filepath = $destinationPath . time() . $file_name;
                move_uploaded_file($file_tmpname, $filepath);
                $studioImage = new StudioImage;
                $studioImage->image = time() . $file_name;
                $studioImage->studio_id = $studio->id;
                $studioImage->save();
            }
            $profileImg = \DB::table('studios')
                ->join('studio_images', 'studios.id', '=', 'studio_images.studio_id')
                ->where('studios.id', '=', $studio->id)
                ->first();
            $image = $profileImg->image;
            $img = array(
                'img' => $image,

            );
            $userImage = \DB::table('users')->where('id', '=', $userId)->update($img);
        }
        return redirect('/user-index')->with('flash_message_success', "Registered Successfully.");
    }
    /* Function for register new Creative in web based */
    public function registerCreative(Request $request)
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard($this->guard)->user();
            $userId = $user->id;
            if ($user->has_profile == 1) {
                return redirect()->back()->with('flash_message_error', 'User already has a profile')->withInput(Input::all());

            } else {
                $validator = Validator::make($request->all(), [
                    'firstName' => 'required',
                    'lastName' => 'required',
                    'fileUpload' => 'required',
                    'website' => 'required',
                    'stage' => 'required',
                    'gender' => [
                        'required',
                        Rule::in([1, 2]),
                    ],
                    'type' => 'required',
                    'pro' => 'required',
                    'craft' => 'required',
                    'genre' => 'required',
                    'influencers' => 'required',
                    'status' => 'required',
                    'platform.*.id' => 'exists:platforms,id',
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput(Input::all());
                }

                if (!empty($request->input('platform')) && !empty($request->input('link'))) {
                    $platfrm = $request->input('platform');
                    $urls = $request->input('link');
                    array_pop($platfrm);
                    array_pop($urls);
                    $platformData = $this->combineArr($platfrm, $urls);
                } else {
                    $platformData = array();
                }
                $firstName = $request->input('firstName');
                $lastName = $request->input('lastName');
                $stage = $request->input('stage');
                $gender = $request->input('gender');
                $type = $request->input('type');
                $craft = $request->input('craft');
                $pro = $request->input('pro');
                $genre = $request->input('genre');
                $influencers = $request->input('influencers');
                $status = $request->input('status');
                $social_media_links = $request->input('social_media_links');
                $website = $request->input('website');

                $data = array(
                    'name' => $firstName . " " . $lastName,
                    'register_flag' => 1,
                );
                \DB::table('users')->where('id', '=', $userId)->update($data);

                if ($files = $request->file('fileUpload')) {

                    $original_img = $request->file('fileUpload');
                    $extension = $request->file('fileUpload')->getClientOriginalExtension();

                    $profileImage = time() . rand() . '.' . $extension;

                    $destinationPath = public_path("img/users");

                    $files->move($destinationPath, $profileImage);

                    $insert['image'] = $profileImage;
                    $img = array('img' => $profileImage);

                    $userImage = \DB::table('users')->where('id', '=', $userId)->update($img);
                }

                $creative = new Creative;
                $creative->website = $website;
                $creative->stage = $stage;
                $creative->gender = $gender;
                $creative->craft = $craft;
                $creative->genre = $genre;
                $creative->type = $type;
                $creative->influencers = $influencers;
                $creative->social_media_links = $social_media_links;
                $creative->status = $status;
                $creative->pro = $pro;
                $creative->user_id = $userId;

                if ($creative->save()) {
                    $user->has_profile = 1;
                    $user->save();
                }
                if (!empty($platformData) && count($platformData) > 0) {
                    foreach ($platformData as $key => $value) {
                        if ($value != '') {
                            $userPlatform = new UserPlatforms;
                            $userPlatform->platform_id = $key;
                            $userPlatform->user_id = $userId;
                            $userPlatform->url = $value;
                            $userPlatform->created_at = now();
                            $userPlatform->updated_at = now();
                            $userPlatform->save();
                        }
                    }
                }
                return redirect('/user-index')->with('flash_message_success', "Registered Successfully.");
            }
        } else {
            return redirect('/login')->with('flash_message_error', config('constants.AuthExpire'));
        }
    }

    public function combineArr($arr1, $arr2)
    {
        return (array_combine($arr1, $arr2));
    }

    /*************** Creative profile data******/
    public function creativeProfile()
    {
        if (Auth::guard($this->guard)->check()) {
            $userId = Auth::guard($this->guard)->user()->id;
            $user = null;
            $user = User::where('id', $userId)->with('creative', 'projects', 'platforms')->withCount('connections')->first();
            $country = Country::where('id', $user->country)->first();
            $unique = $user['projects']->unique('id');
            unset($user['projects']);
            $user['projects_count'] = count($unique);
            $user['projects'] = $unique->values()->all();

            $managers = DB::table('connections')
                ->select('users.id', 'users.name', 'users.img', 'users.email')
                ->Rightjoin('users', 'connections.connected_user_id', '=', 'users.id')
                ->where([
                    ['connections.user_id', '=', $userId],
                    ['connections.is_approved', '=', '0'],
                    ['connections.type', '=', 'management']])
                ->orWhere([['connections.connected_user_id', '=', $userId],
                    ['connections.is_approved', '=', '0'],
                    ['connections.type', '=', 'management']])
                ->get();
            $user['creative']['managers'] = $managers;

            foreach ($user['creative']['managers'] as $key => $value) {
                //Format variable
                $user['creative']['managers'][$key]->img = URL::to('img/users/' . $user['creative']['managers'][$key]->img);
            }
            $genres = Genre::where('id', $user['creative']['genre'])->select('id', 'title')->first();
            $genders = Gender::where('id', $user['creative']['gender'])->select('id', 'title')->first();
            $crafts = Craft::where('id', $user['creative']['craft'])->select('id', 'title')->first();
            $types = Type::where('id', $user['creative']['type'])->select('id', 'title')->first();
            $platforms = Platform::select('id', 'title')->get();
            if ($this->getUserConnectionRequests()) {
                $requestConnections = $this->getUserConnectionRequests();
            } else {
                $requestConnections = '';
            }
            $sender = \DB::table('connections')
                ->select('users.id', 'users.name', 'users.img', 'users.email')
                ->Rightjoin('users', 'connections.connected_user_id', '=', 'users.id')
                ->where([
                    ['connections.user_id', '=', $userId],
                    ['connections.is_approved', '=', '1'],
                    ['connections.type', '=', 'management']])
                ->get();
            $reciever = \DB::table('connections')
                ->select('users.id', 'users.name', 'users.img', 'users.email')
                ->Rightjoin('users', 'connections.user_id', '=', 'users.id')
                ->where([
                    ['connections.connected_user_id', '=', $userId],
                    ['connections.is_approved', '=', '1'],
                    ['connections.type', '=', 'management']])
                ->get();
            $projectData = \DB::table('projects')
                ->join('user_projects', 'projects.id', '=', 'user_projects.project_id')
                ->where('user_projects.user_id', '=', $userId)
                ->get();
            $numberOfConnections = Connection::
                where([['connected_user_id', '=', $userId], ['type', '=', 'connection'], ['is_approved', '=', '1']])->
                orWhere([['user_id', '=', $userId], ['type', '=', 'connection'], ['is_approved', '=', '1']])->count();
            $numberOfProjects = UserProject::
                where([['user_id', '=', $userId]])->count();
            $count = "";
            return view('web.profile', compact('projectData', 'count', 'numberOfConnections', 'numberOfProjects', 'reciever', 'sender', 'genres', 'genders', 'crafts', 'types', 'platforms', 'user', 'requestConnections', 'country'));
        } else {
            return redirect('/login')->with('flash_message_error', config('constants.AuthExpire'));
        }
    }

    /****************Edit Creative profile data******/
    public function editCreative()
    {
        $genres = Genre::select('id', 'title')->get();
        $genders = Gender::select('id', 'title')->get();
        $crafts = Craft::select('id', 'title')->get();
        $types = Type::select('id', 'title')->get();
        $platforms = Platform::select('id', 'title')->get();
        if (Auth::guard($this->guard)->check()) {
            $userId = Auth::guard($this->guard)->user()->id;
            $user = null;
            $user = User::where('id', $userId)->with('creative', 'projects', 'platforms')->withCount('connections')->first();
            $unique = $user['projects']->unique('id');
            unset($user['projects']);
            $user['projects_count'] = count($unique);
            $user['projects'] = $unique->values()->all();
            $managers = DB::table('connections')->select('users.id', 'users.name', 'users.img')->Rightjoin('users', 'connections.connected_user_id', '=', 'users.id')->where([['connections.user_id', '=', $userId], ['connections.is_approved', '=', '1'], ['connections.type', '=', 'management']])->get();
            $user['creative']['managers'] = $managers;
            foreach ($user['creative']['managers'] as $key => $value) {
                //Format variable
                $user['creative']['managers'][$key]->img = URL::to('img/users/' . $user['creative']['managers'][$key]->img);
            }
            $countries = \DB::table('countries_web')->get();
            $state = \DB::table('states_web')->where('country_id', '=', $user->country)->get('name');
            $count = "";
            return view('web.update_creative', compact('genres', 'genders', 'crafts', 'types', 'platforms', 'user', 'countries', 'state', 'count'));
        } else {
            return redirect('/login')->with('flash_message_error', config('constants.AuthExpire'));
        }
    }
/****************Update Creative profile data******/
    public function updateCreative(Request $request)
    {
        if (Auth::guard($this->guard)->check()) {
            $user = Auth::guard($this->guard)->user();
            $userId = $user->id;
            $validator = Validator::make($request->all(), [
                'firstName' => 'required',
                'lastName' => 'required',
                'website' => 'required',
                'stage' => 'required',
                'type' => 'required',
                'craft' => 'required',
                'genre' => 'required',
                'influencers' => 'required',
                'status' => 'required',
                'platform.*.id' => 'exists:platforms,id',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput(Input::all());
            }
            $profile = Creative::where('user_id', $userId)->first();
            if ($request->has('fileUpload')) {
                $files = $request->file('fileUpload');
                //--Delete Old Image---//
                $oldImgPath = public_path("img/users/" . $profile['img']);
                if (File::exists($oldImgPath)) {
                    File::delete($oldImgPath);
                }
                //---Create New Image--//
                $original_img = $request->file('fileUpload');
                $extension = $request->file('fileUpload')->getClientOriginalExtension();
                $profileImage = time() . rand() . '.' . $extension;
                $destinationPath = public_path("img/users");
                $files->move($destinationPath, $profileImage);
                $insert['image'] = $profileImage;
                $img = array('img' => $profileImage);
                $userImage = \DB::table('users')->where('id', '=', $userId)->update($img);
            }
            //array for updating
            $user->name = $request->input('firstName') . " " . $request->input('lastName');
            $user->country = $request->input('country');
            $user->state = $request->input('state');
            $user->city = $request->input('city');
            $profile->website = $request->input('website');
            $profile->stage = $request->input('stage');
            $profile->gender = $request->input('gender');
            $profile->type = $request->input('type');
            $profile->pro = $request->input('pro');
            $profile->craft = $request->input('craft');
            $profile->genre = $request->input('genre');
            $profile->influencers = $request->input('influencers');
            $profile->social_media_links = $request->input('social_media_links');
            $profile->status = $request->input('status');
            $platformData = array();
            if (!empty($request->input('platform')) && !empty($request->input('link'))) {
                $platfrm = $request->input('platform');
                $urls = $request->input('link');
                array_pop($platfrm);
                array_pop($urls);
                $platformData = $this->combineArr($platfrm, $urls);
            }
            if ($profile->save() && $user->save()) {
                if ($request->has('platform')) {
                    //Delete Old Platforms
                    UserPlatforms::where('user_id', $userId)->delete();

                    if (!empty($platformData)) {
                        foreach ($platformData as $key => $value) {
                            if (!empty($value)) {
                                $userPlatform = new UserPlatforms;
                                $userPlatform->platform_id = $key;
                                $userPlatform->user_id = $userId;
                                $userPlatform->url = $value;
                                $userPlatform->created_at = now();
                                $userPlatform->updated_at = now();
                                $userPlatform->save();
                            }
                        }
                    }
                }
                return redirect('/my-profile')->with('flash_message_success', config('constants.ProfileUpdate'));
            } else {
                return redirect()->back()->with('flash_message_error', config('constants.SomethingWrong'));
            }
            return redirect('/my-profile')->with('flash_message_success', config('constants.ProfileUpdate'));

        } else {
            return redirect('/login')->with('flash_message_error', config('constants.AuthExpire'));
        }

    }

    /* Function for return view for getadvance */
    public function getAdvance(Request $request)
    {
        $count = "";
        return view('web.get_advance', compact('count'));
    }

    /* Function for Submit Data for getadvance */
    public function advance(Request $request)
    {
        $user = Auth::guard('web')->user();
        $userId = 0;

        if ($user != null) {
            $userId = $user->id;
        }
        $advance = new Advance;
        $advance->type = $request->legal;
        $advance->is_decisionmaker = $request->is_decisionmaker;
        $advance->is_musicprimary = $request->is_musicprimary;
        $advance->is_entertainmentprimary = $request->is_entertainmentprimary;
        $advance->amount_period = $request->amount_period;
        $advance->period = $request->period;

        $advance->save();

        return redirect('/user-index')->with('flash_message_success', Config::get('constants.AdvanceRegister'));
    }

    public function getUserConnectionRequests()
    {

        if (Auth::guard($this->guard)->check()) {
            $user = Auth::guard($this->guard)->user();
            $userId = $user->id;
            $connections = DB::table('connections')
                ->select('connections.id', 'users.type AS user_type',
                    'users.id AS user_id')
                ->leftJoin('users', 'connections.user_id',
                    '=', 'users.id')
                ->where([['connections.connected_user_id', '=', $userId],
                    ['connections.is_approved', '=', 0]])
            // ->orWhere([['connections.connected_user_id', '=', $userId],
            //     ['connections.is_approved', '=', 0],
            // ])
                ->get();
            $typeArray = array('1' => 'Creative', '2' => 'Manager', '3' => 'Studio');
            foreach ($connections as $key => $value) {
                $profile = User::where('id', $connections[$key]->user_id)->first();

                $connections[$key]->name = $profile['name'];
                $connections[$key]->type = $typeArray[$connections[$key]->user_type];
                $connections[$key]->img = $profile->img != '' ? $profile->img : '';
            }
            return $connections;
        }
    }

    /* Function for create new project in web based */
    public function saveProject(Request $request)
    {
        $projectImage = "";
        $projectAudio = "";
        $projectData = array();
        if ($request->file('projectImage') != null) {
            $file = $request->file('projectImage');
            $projectImage = date('YmdHis') . "." . $file->getClientOriginalExtension();
            $destinationPath = public_path('img/users/'); // upload path
            $file->move($destinationPath, $projectImage);
            $insert['image'] = "$projectImage";
        }
        if ($request->file('projectAudio') != null) {
            $original_audio = $request->file('projectAudio');
            $extension = $request->file('projectAudio')->getClientOriginalExtension();
            $newAudioName = time() . rand() . '.' . $extension;
            $original_audio->storeAs('audio/projects', $newAudioName, 'public');
            $project['audio'] = $newAudioName;
        }
        if (!empty($request->projectData)) {
            foreach ($request->projectData as $data) {
                if (!empty($data['url']) && !empty($data['platform'])) {
                    if ($data['platform'] == 2) {
                        $projectData['spotify'] = $data['url'];
                    } else if ($data['platform'] == 6) {
                        $projectData['pandora'] = $data['url'];
                    } else if ($data['platform'] == 3) {
                        $projectData['google'] = $data['url'];
                    } else if ($data['platform'] == 5) {
                        $projectData['amazon'] = $data['url'];
                    } else if ($data['platform'] == 7) {
                        $projectData['deezer'] = $data['url'];
                    } else if ($data['platform'] == 4) {
                        $projectData['tidal'] = $data['url'];
                    } else if ($data['platform'] == 8) {
                        $projectData['youtube'] = $data['url'];
                    } else if ($data['platform'] == 9) {
                        $projectData['sound_cloud'] = $data['url'];
                    } else if ($data['platform'] == 1) {
                        $projectData['apple_music'] = $data['url'];
                    }
                }
            }
        }
        $project = new Project();
        $project['title'] = $request->projectTitle;
        $project['release_date'] = $request->releaseDate;
        $project['img'] = $projectImage;
        $project['audio'] = $newAudioName;
        $project['Privacy'] = $request->privacy;
        //array_merge($project, $projectData);
        $user = Auth::guard('web')->user();
        $userId = 1;
        if ($user != null) {
            $userId = $user->id;
        }
        if ($project->save()) {
            $projectEditRequest = \DB::table('projects')->where('id', '=', $project->id)->update($projectData);
            $userProject = new UserProject([
                "user_id" => $userId,
                "project_id" => $project->id,
                "role" => "creator",
            ]);
            $userProject['is_approved'] = true;
            $userProject->save();
            if ($request->get('contributors') != null) {
                $contributorsSize = count(collect($request)->get('contributors'));
                //associate project to
                for ($i = 0; $i < $contributorsSize; $i++) {
                    $userProject = new ProjectEditRequest([
                        "user_id" => $userId,
                        "project_id" => $project->id,
                        "contributor_id" => $request->get('contributors')[$i],
                    ]);
                    $userProject->save();
                    //send connection request
                    $connection_id = $this->sendConnectionRequest($userId, $request->get('contributors')[$i]);
                    $connectionProject = new ConnectionProjectMapping([
                        "user_id" => $userId,
                        "project_id" => $project->id,
                        "contributor_id" => $request->get('contributors')[$i],
                        "connection_id" => $connection_id,
                    ]);
                    $connectionProject->save();
                }
            }
        }
        return redirect('/addproject')->with('flash_message_success', Config::get('constants.ProjectRegister'));
    }
    /* Function for return view for update project */
    public function updateProject(Request $request, $id)
    {
        $count = "";
        return view('web.createproject', compact('id', 'count'));
    }

    /* Function for get autocmoplete data for user */
    public function getAutocompleteData(Request $request)
    {
        $search = $request->search;
        if ($search != '') {
            $users = User::orderby('name', 'asc')->select('id', 'name', 'type', 'img')->where('name', 'like', '%' . $search . '%')->limit(5)->get();
        }
        return response()->json($users);
    }

    /* Function for create connection request in web based */
    public function sendConnectionRequest($userId, $contributorId)
    {
        $connectionSender = new Connection();

        $connectionSender->connected_user_id = $contributorId;
        $connectionSender->type = "project";
        $connectionSender->user_id = $userId;
        if ($connectionSender->save()) {
            $activity = new Activity();
            $activity['sender_id'] = $userId;
            $activity['request_id'] = $connectionSender['id'];
            $activity['message'] = "Requested to connect with you";
            $activity['message_es'] = "Se le solicitó conectarse con usted";
            $activity['message_fr'] = "A demandé à se connecter avec vous";
            $activity['message_zh'] = "請求與您聯繫";
            $activity['type'] = "connection";
            $activity['user_id'] = $contributorId;
            $activity->save();
        }
        return $connectionSender->id;
    }

    /* Function for get connection request in web based */
    public function getConnectionRequests()
    {
        $user = Auth::guard('web')->user();
        $userId = 1;
        if ($user != null) {
            $userId = $user->id;
        }
        $connections = \DB::table('connections')
            ->leftJoin('users', 'connections.connected_user_id',
                '=', 'users.id')
            ->where([['connections.connected_user_id', '=', $userId]])
            ->select('connections.id', 'users.type AS user_type',
                'connections.user_id AS user_id', 'connections.is_approved as is_approved')
            ->get();
        $typeArr = array('1' => 'Creative', '2' => 'Manager', '3' => 'Studio');
        foreach ($connections as $key => $value) {
            $profile = User::where('id', $connections[$key]->user_id)->first();
            $connections[$key]->name = $profile['name'];
            $connections[$key]->type = $this->getTypeAttribute($profile['type']);
            $connections[$key]->img = $profile->img != '' ? $profile->img : '';
        }
        return $connections;
    }

    /* Function for get user type in web based */
    public function getTypeAttribute($value)
    {
        if ($value == 1) {
            return Config::get('constants.Creative');
        } else if ($value == 2) {
            return Config::get('constants.Manager');
        } else if ($value == 3) {
            return Config::get('constants.Studio');
        }
        return $value;
    }

    /* Function for accept connection request in web based */
    public function acceptConnectionRequest(Request $request)
    {
        $recordId = $request->id;
        $values = array(
            'is_approved' => 1,
        );
        $conn = \DB::table('connections')->where('id', '=', $recordId)->get();
        $connection = \DB::table('connections')->where('id', '=', $recordId)->update($values);
        $users = \DB::table('project_edit_requests')
            ->where([['user_id', '=', $conn[0]->connected_user_id],
                ['project_id', '=', $request->project_id]])->update($values);
        $request->id = $request->project_id;
        $request->message = Config::get('constants.RequestAccepted');
        return $this->projectInfo($request);
    }

    /* Function for reject connection request in web based */
    public function rejectConnectionRequest(Request $request)
    {
        $recordId = $request->id;
        $values = array(
            'type' => '',
            'is_approved' => 0,
        );
        $connection = \DB::table('connections')->where('id', '=', $recordId)->update($values);
        $request->id = $request->project_id;
        $request->message = Config::get('constants.RequestRejected');
        return $this->projectInfo($request);
    }

    /* Function for get all connection of logged in user in web based */
    public function getConnections(Request $request)
    {
        $user = Auth::guard('web')->user();
        $userId = 1;
        if ($user != null) {
            $userId = $user->id;
        }
        $connections = \DB::table('connections')
            ->leftJoin('users', 'connections.connected_user_id',
                '=', 'users.id')
            ->where([['connections.connected_user_id', '=', $userId], ['connections.is_approved', '=', null]])
            ->select('connections.id', 'connections.type', 'users.type AS user_type',
                'connections.user_id AS user_id', 'connections.is_approved as is_approved')
            ->get();
        $typeArr = array('1' => 'Creative', '2' => 'Manager', '3' => 'Studio');
        foreach ($connections as $key => $value) {
            $profile = User::where('id', $connections[$key]->user_id)->first();

            $connections[$key]->name = $profile['name'];
            $connections[$key]->connection_type = $connections[$key]->type;
            $connections[$key]->type = $typeArr[$connections[$key]->user_type];
            $connections[$key]->img = $profile->img != '' ? $profile->img : '';
        }
        $count = "";
        return view('webuserlayout.connection_request', compact('connections', 'count'));
    }
    /**
     * Use to get terms
     * **/
    public function getTerms()
    {
        $count = "";
        return view('web.terms', compact('count'));
    }

    /* Function for add connection request in web based */
    public function addConnection(Request $request, $id)
    {
        $values = array('is_approved' => 1);
        $message = '';
        $conn = \DB::table('connections')->where('id', '=', $id)->first();
        $connection = \DB::table('connections')->where('id', '=', $id)->update($values);
        $connectionId = \DB::table('connection_project_mapping')->where('connection_id', '=', $id)->first();
        if ($connectionId) {
            $updateFlag = \DB::table('project_edit_requests')
                ->where([['project_id', '=', $connectionId->project_id], ['user_id', '=', $connectionId->user_id], ['contributor_id', '=', $connectionId->contributor_id]])
                ->orwhere([['project_id', '=', $connectionId->project_id], ['user_id', '=', $connectionId->contributor_id], ['contributor_id', '=', $connectionId->user_id]])
                ->update($values);
        }
        if ($conn->type === Config::get('constants.connectionType')) {
            $message = Config::get('constants.RequestAccepted');
        } else if ($conn->type === Config::get('constants.managementType')) {
            $message = Config::get('constants.managementRequestAccepted');
        } else {
            $message = Config::get('constants.contributorRequestAccepted');
        }
        return redirect('/user-index')->with('flash_message_success', $message);
    }

    /* Function for delete connection request in web based */
    public function deleteConnection(Request $request, $id)
    {
        $recordId = $id;
        $values = array(
            'is_approved' => 1,
        );
        $conn = \DB::table('connections')->where('id', '=', $recordId)->get();
        $connection = \DB::table('connections')->where('id', '=', $recordId)->delete();
        return redirect('/user-index')->with('flash_message_success', Config::get('constants.RequestRejected'));
    }
/* Function for save claim credit */
    public function claimCredit(Request $request, $id)
    {
        $userId = Auth::guard('web')->user()->id;
        $projectId = $id;
        $role = $request->contributors;
        $projectData = \DB::table('user_projects')->where('project_id', '=', $id)->first();
        $contributorId = $projectData->user_id;
        $connection = \DB::table('connection_project_mapping')
            ->where([['contributor_id', '=', $contributorId], ['user_id', '=', $userId], ['project_id', '=', $projectId]])
            ->first();
        if ($contributorId != $userId) {
            if (empty($connection)) {
                $userProject = new ProjectEditRequest([
                    "user_id" => $contributorId,
                    "project_id" => $projectId,
                    "contributor_id" => $userId,
                ]);
                $userProject->save();
                $connectionSender = new Connection();
                $connectionSender->connected_user_id = $contributorId;
                $connectionSender->type = "project";
                $connectionSender->user_id = $userId;
                $connectionSender->save();
                $connectionId = $connectionSender->id;
                $roleData = new RoleUser();
                $roleData->role_id = $role;
                $roleData->user_id = $contributorId;
                $roleData->save();
                //send connection request
                $connectionProject = new ConnectionProjectMapping([
                    "user_id" => $userId,
                    "project_id" => $projectId,
                    "contributor_id" => $contributorId,
                    "connection_id" => $connectionId,
                ]);
                $connectionProject->save();
                return redirect('/user-index')->with('flash_message_success', Config::get('constants.RequestSend'));
            } else {
                return redirect('/user-index')->with('flash_message_error', Config::get('constants.RequestAlreadySend'));
            }
        } else {
            return redirect('/user-index')->with('flash_message_error', Config::get('constants.RequestNotSend'));
        }
    }

/* Function for return view for settings page */
    public function settings(Request $request)
    {
        $count = "";
        return view('web.settings', compact('count'));
    }

/* Function for return view for language page */
    public function language(Request $request)
    {
        $user = Auth::guard('web')->user();
        $count = "";
        return view('web.language', compact('user', 'count'));
    }

/* Function for update language for user */
    public function updateLanguage(Request $request)
    {
        \App::setlocale($request->radio);
        $request->session()->put('lang', $request->radio);
        $exitCode = Artisan::call('cache:clear');
        User::where('id', '=', Auth::guard('web')->user()->id)->update(['language' => $request->radio]);
        return redirect('/user-index');
    }

/* Function for delete project for user */
    public function deleteProject(Request $request, $id)
    {$userId = Auth::guard('web')->user()->id;
        $projectData = \DB::table('user_projects')->where('project_id', '=', $id)->first();
        $projectUserId = $projectData->user_id;
        if ($userId == $projectUserId) {
            \DB::table('user_projects')->where('project_id', '=', $id)->delete();
            \DB::table('projects')->where('id', '=', $id)->delete();
            return redirect('/addproject')->with('flash_message_success', Config::get('constants.projectDelete'));
        }
        return redirect('/addproject')->with('flash_message_error', Config::get('constants.projectDeleteError'));
    }

/* Function for contact form  */
    public function saveContact(Request $request)
    {
        $contact = new Contact;
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->phone = $request->phone;
        $contact->message = $request->message;
        $contact->save();
        return redirect('/user-index')->with('flash_message_success', "Thank you for contacting us");
    }
    /* Function for streaming view  */
    public function viewStreaming(Request $request)
    {
        $count = "";
        return view('web.streaming', compact('count'));
    }

    /* Function for save streaming   */
    public function getStreaming(Request $request)
    {
        $url = $request->channel;
        $html = @file_get_contents($url);
        preg_match("'<meta itemprop=\"channelId\" content=\"(.*?)\"'si", $html, $match);
        if ($match && $match[1]) {
            $Channel_ID = $match[1];
            if ($Channel_ID) {
                $channel = \DB::table('media_providers')->where('provider_key', '=', $Channel_ID)->first();
                if ($channel == "") {
                    $API_Key = 'AIzaSyD3PK-P8sVLNNWYLgznu9qmDdn7cCU9jqQ';
                    $youtube_subscribers = @file_get_contents('https://www.googleapis.com/youtube/v3/channels?part=statistics&id=' . $Channel_ID . '&key=' . $API_Key . '');
                    // Decoding json youtube api response
                    $youtube_api_response = json_decode($youtube_subscribers, true);

                    if ($youtube_api_response) {
                        if (!empty($youtube_api_response['items'][0]['statistics']['subscriberCount']) && !empty($youtube_api_response['items'][0]['statistics']['videoCount'])) {
                            // get count of youtube subscribers.
                            $subscribers_count = intval($youtube_api_response['items'][0]['statistics']['subscriberCount']);
                            // get count of youtube vedios.
                            $vedio_count = intval($youtube_api_response['items'][0]['statistics']['videoCount']);
                            //add streaming data
                            $youtube = "https://www.youtube.com/oembed?url=" . $url . "&format=json";
                            $curl = curl_init($youtube);
                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                            $return = curl_exec($curl);
                            curl_close($curl);
                            $data = json_decode($return, true);
                            $userId = Auth::guard('web')->user()->id;
                            $mediaProvider = new mediaProvider([
                                "user_id" => $userId,
                                "provider_key" => $Channel_ID,
                                "provider_type" => "youtube",
                                'followers' => $subscribers_count,
                                'songs' => $vedio_count,
                                'username' => $data['author_name'],
                            ]);
                            $mediaProvider->save();
                            return redirect('/user-index')->with('flash_message_success', 'Channel Added Successfully');
                        } else {return redirect('/user-index')->with('flash_message_error', 'You dont have any subscribers count');}
                    }}
                return redirect('/user-index')->with('flash_message_error', 'Something went wrong ,Please try after sometime');
            }
        }
        return redirect('/user-index')->with('flash_message_error', 'Invalid Url');
    }
    public function getSpotify(Request $request)
    {
        $userId = Auth::guard('web')->user()->id;
        $client_id = 'acae01c383cb456bb1de40228926bd46';
        $client_secret = 'e568069b06a64885ab2db2708f9a051d';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://accounts.spotify.com/api/token');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Basic ' . base64_encode($client_id . ':' . $client_secret)));
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:x.x.x) Gecko/20041107 Firefox/x.x");
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $json = curl_exec($ch);
        $json = json_decode($json);
        curl_close($ch);
        echo '<pre>' . print_r($json, true) . '</pre>';
        $authorization = "Authorization: Bearer " . $json->access_token;
        $artist = $request->spotify;
        $spotifyURL = 'https://api.spotify.com/v1/users/' . $artist;
        $ch2 = curl_init();
        curl_setopt($ch2, CURLOPT_URL, $spotifyURL);
        curl_setopt($ch2, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch2, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:x.x.x) Gecko/20041107 Firefox/x.x");
        curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
        $json2 = curl_exec($ch2);
        $json2 = json_decode($json2);
        curl_close($ch2);
        if (empty($json2->error)) {
            $userName = $json2->display_name;
            $followers = $json2->followers->total;
            $channel = \DB::table('media_providers')->where('provider_key', '=', $userName)->first();

            if ($channel == "") {
                //add streaming data
                $mediaProvider = new mediaProvider([
                    "user_id" => $userId,
                    "provider_key" => $userName,
                    "provider_type" => "spotify",
                    'followers' => $followers,
                    'username' => $userName,
                ]);
                $mediaProvider->save();
                return redirect('/user-index')->with('flash_message_success', 'Channel Added Successfully');
            }return redirect('/user-index')->with('flash_message_error', 'Channel already added');

        } else {return redirect('/user-index')->with('flash_message_error', 'Unable to find channel name');
        }
    }
    /* For delete stream */
    public function deleteStreaming(Request $request, $id)
    {
        mediaProvider::where('id', $id)->delete();
        return redirect('/user-index')->with('flash_message_success', 'Channel Deleted Successfully');
    }

    /**
     * Use to update Local Language
     * **/
    public function updateLocalLanguage()
    {
        $user = Auth::guard('web')->user();
        if (!empty($user['language'])) {
            \App::setlocale($user['language']);
            $exitCode = Artisan::call('cache:clear');
        }
    }

    /**
     * Use to get chat view
     * **/
    public function userChat()
    {
        if (Auth::guard($this->guard)->check()) {
            $flag = 0;
            $count = "";
            $user = Auth::guard($this->guard)->user();
            $userId = $user->id;
            $connectedUsers = Connection::with('senderConnectedUser')->with('receiverConnectedUser')->where([['connected_user_id', $userId], ['is_approved', true], ['type', 'connection']])->orWhere([['user_id', $userId], ['type', 'connection'], ['is_approved', true]])->paginate(config('constants.PaginateArray.1'));
            foreach ($connectedUsers as $key => $value) {
                if ($value['senderConnectedUser']['id'] == $userId) {
                    $connectedUsers[$key]['connectedUser'] = $connectedUsers[$key]['receiverConnectedUser'];
                    unset($connectedUsers[$key]['senderConnectedUser']);
                    unset($connectedUsers[$key]['receiverConnectedUser']);
                } elseif ($value['receiverConnectedUser']['id'] == $userId) {
                    $connectedUsers[$key]['connectedUser'] = $connectedUsers[$key]['senderConnectedUser'];
                    unset($connectedUsers[$key]['receiverConnectedUser']);
                    unset($connectedUsers[$key]['senderConnectedUser']);
                }
            }

            return view('web.userchat', compact('connectedUsers', 'flag', 'count'));
        }
    }

    /**
     * Use to get user chat
     * **/
    public function getChat(Request $request)
    {
        $userId = Auth::guard('web')->user()->id;
        $id = $request->id;
        $chat = \DB::table('messages')
            ->where([['from_user', '=', $id], ['to_user', '=', $userId]])
            ->orwhere([['to_user', '=', $id], ['from_user', '=', $userId]])
            ->get();
        if ($chat != "") {
            $data = array(
                'read_flag' => 1,
            );
            $updateChat = \DB::table('messages')->where('to_user', '=', $userId)->update($data);
            $flag = 1;
            return view('web.chat', compact('chat', 'flag', 'userId'));
        } else {
            $flag = 0;
            return view('web.chat', compact('flag'));
        }
    }

    /**
     * Use to save user chat
     * **/
    public function saveChat(Request $request)
    {
        $user = Auth::guard($this->guard)->user();
        $userId = $user->id;
        $id = $request->id;
        $message = $request->message;
        $message = new Message([
            "from_user" => $userId,
            "to_user" => $id,
            "content" => $message]);
        $message->save();
    }

    /**
     * Use to combine project
     * **/
    public function combineProject(Request $request)
    {
        $project = $request->combine;
        if ($project != "") {
            $count = count($project);
            if ($count == 2) {
                $project1 = \DB::table('projects')->where('id', '=', $project['0'])->first();
                $project2 = \DB::table('projects')->where('id', '=', $project['1'])->first();
                $data = array(
                    'project_id' => $project['0'],
                );
                if (($project1->apple_music == $project2->apple_music) && ($project1->spotify == $project2->spotify) && ($project1->pandora == $project2->pandora) && ($project1->google == $project2->google) && ($project1->amazon == $project2->amazon) && ($project1->deezer == $project2->deezer) && ($project1->tidal == $project2->tidal) && ($project1->rhapsody == $project2->rhapsody) && ($project1->youtube == $project2->youtube) && ($project1->xbox_music == $project2->xbox_music) && ($project1->sound_cloud == $project2->sound_cloud)) {
                    //--Delete Image---//
                    $oldImgPath = public_path("img/users/" . $project2->img);
                    if (File::exists($oldImgPath)) {
                        File::delete($oldImgPath);
                    }
                    //--Delete audio---//
                    $audio = public_path("audio/projects/" . $project2->audio);
                    if (File::exists($audio)) {
                        File::delete($audio);
                    }
                    $updateConnectionMapping = \DB::table('connection_project_mapping')->where('project_id', '=', $project['1'])->update($data);
                    $projectEditRequest = \DB::table('project_edit_requests')->where('project_id', '=', $project['1'])->update($data);
                    $deleteUserProject = \DB::table('user_projects')->where('project_id', '=', $project['1'])->delete();
                    $deleteProject = \DB::table('projects')->where('id', '=', $project['1'])->delete();
                    return redirect('/my-profile')->with('flash_message_success', "Projects combine successfully.");
                } else {
                    return redirect('/my-profile')->with('flash_message_error', "Projects can not be combine.");
                }
            } else {
                return redirect('/my-profile')->with('flash_message_error', "Please Select two projects.");
            }
        } else {
            return redirect('/my-profile')->with('flash_message_error', "Please Select projects.");
        }
    }

    /**
     * Use to get activity
     * **/
    public function getActivity()
    {
        $userId = Auth::guard('web')->user()->id;
        $sendActivities = \DB::table('activities')->where('user_id', '=', $userId)
            ->join('users', 'users.id', '=', 'activities.sender_id')
            ->take(2)
            ->get();
        $count = "";
        return view('web.activity', compact('sendActivities', 'count'));
    }

    /**
     * view activity page
     * @return void
     */
    public function loadData(Request $request)
    {
        $userId = Auth::guard('web')->user()->id;
        if ($request->ajax()) {
            if ($request->id > 0) {
                $data = DB::table('activities')
                    ->where('activities.id', '<', $request->id)
                    ->where('user_id', '=', $userId)
                    ->join('users', 'users.id', '=', 'activities.sender_id')
                    ->orderBy('activities.id', 'DESC')
                    ->limit(config('constants.limit'))
                    ->get();
            } else {
                $data = DB::table('activities')
                    ->where('user_id', '=', $userId)
                    ->join('users', 'users.id', '=', 'activities.sender_id')
                    ->orderBy('activities.id', 'DESC')
                    ->limit(config('constants.limit'))
                    ->get();
            }
            $output = '';
            $last_id = '';
            if (!$data->isEmpty()) {
                $output .= '<ul class="activity-list">';
                foreach ($data as $row) {
                    $output .= '<li class="bordr-botm bordr-botm pt-3 " id=' . $row->id . '><b>' . $row->name . '</b><p>' . $row->message . '</p></li>';
                    $last_id = $row->id;
                }
                $output .= '</ul>';
                $output .= '
        <div id="load_more" class="align-self-center mx-auto col-md-4 pb-4 pt-4 text-center">
        <button type="button" name="load_more_button" class="btn btn-lg btn-primary btn-login w-100 mt-2" data-id="' . $last_id . '" id="load_more_button">Load More</button>
        </div>
        ';
            } else {
                $output .= '
        <div id="load_more" class="align-self-center mx-auto col-md-4 pb-4 pt-4 text-center">
        <button type="button" name="load_more_button" class="btn btn-lg btn-primary btn-login w-100 mt-2">No Data Found</button>
        </div>
        ';
            }
            echo $output;
        }
    }

    /**
     * Use to count notifications
     * **/
    public function notification()
    {
        $userId = Auth::guard('web')->user()->id;
        $connections = \DB::table('connections')
            ->where('connected_user_id', '=', $userId)
            ->where('is_approved', '=', null)->get();
        $count = count($connections);
        if ($count == 0) {
            $count = "";
        }
        return view('webuserlayout.notification', compact('count'));
    }

    /**
     * Use to count message notifications
     * **/
    public function messageCount()
    {
        $userId = Auth::guard('web')->user()->id;
        $messages = \DB::table('messages')
            ->where('to_user', '=', $userId)
            ->where('read_flag', '=', 0)->get();
        $count = count($messages);
        if ($count == 0) {
            $count = "";
        }
        return view('webuserlayout.messageCount', compact('count'));
    }

    /**
     * Use to count users message notifications
     * **/
    public function userMessageCount()
    {
        if (Auth::guard($this->guard)->check()) {
            $flag = 0;
            $count = "";
            $user = Auth::guard($this->guard)->user();
            $userId = $user->id;
            $connectedUsers = Connection::with('senderConnectedUser')->with('receiverConnectedUser')->where([['connected_user_id', $userId], ['is_approved', true], ['type', 'connection']])->orWhere([['user_id', $userId], ['type', 'connection'], ['is_approved', true]])->paginate(config('constants.PaginateArray.1'));
            foreach ($connectedUsers as $key => $value) {
                if ($value['senderConnectedUser']['id'] == $userId) {
                    $connectedUsers[$key]['connectedUser'] = $connectedUsers[$key]['receiverConnectedUser'];
                    unset($connectedUsers[$key]['senderConnectedUser']);
                    unset($connectedUsers[$key]['receiverConnectedUser']);
                } elseif ($value['receiverConnectedUser']['id'] == $userId) {
                    $connectedUsers[$key]['connectedUser'] = $connectedUsers[$key]['senderConnectedUser'];
                    unset($connectedUsers[$key]['receiverConnectedUser']);
                    unset($connectedUsers[$key]['senderConnectedUser']);
                }
            }
            return view('web.showusers', compact('connectedUsers', 'flag', 'count'));
        }
    }

}
