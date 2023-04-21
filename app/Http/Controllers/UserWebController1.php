<?php

namespace App\Http\Controllers;
use App\Http\Controllers;
use App\Manager;
use App\Studio;
use App\StudioImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\User;
use Hash;
use App\Creative;
use App\Genre;
use App\Gender;
use App\Craft;
use App\Type;
use App\Platform;
use App\UserPlatforms;
use App\ProjectEditRequest;
use App\Country;
use App\Connection;
use App\Project;
use App\State;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use DB;
use App\Activity;
use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use File;
use App\Advance;
use URL;
use Storage;
use App\UserProject;

class UserWebController extends Controller
{
    protected 
        $redirectTo = 'login',
        $guard = 'web';
/* Function for return view for index page */
    public function index(Request $request)
    { $genres = Genre::select('id', 'title')->get();
        $genders = Gender::select('id', 'title')->get();
        $types = Type::select('id', 'title')->get();
        $countries = Country::select('id', 'name')->get();
        $crafts = Craft::select('id', 'title')->get();
        $user = Auth::guard($this->guard)->user();
        $startConnectionRange = '';
        $endConnectionRange = '';
        $startprojectRange = '';
        $endprojectRange = '';
        if (Input::has('connection') && $request->input('connection') != '')
        {
            $connectionRange = explode('-', $request->input('connection'));
            $startConnectionRange = $connectionRange[0];
            $endConnectionRange = $connectionRange[1];
        }
        if (Input::has('project') && $request->input('project') != '')
        {
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

        if(isset($user) && !empty($user)) {
            $users = $users->where('id', '!=', $user->id);
        }
        $conditionsCreative = array();
        $conditionsUser = array();
        $conditionStudio = array();
        if (Input::has('type') && $request->input('type') != ''){
            array_push($conditionsCreative, ['type', '=', $request->input('type')]);
            array_push($conditionStudio, ['type', '=', $request->input('type')]);
        }
        if (Input::has('artistry') && $request->input('artistry') != '')
            array_push($conditionsCreative, ['craft', '=', $request->input('artistry')]);
        if (Input::has('genre') && $request->input('genre') != '')
            array_push($conditionsCreative, ['genre', '=', $request->input('genre')]);

        if (Input::has('gender') && $request->input('gender') != '')
            array_push($conditionsCreative, ['gender', '=', $request->input('gender')]);

        if (Input::has('country') && $request->input('country') != '')
            array_push($conditionsUser, ['country', '=', $request->input('country')]);

        if (Input::has('state') && $request->input('state') != '')
            array_push($conditionsUser, ['state', '=', $request->input('state')]);

        if (Input::has('city') && $request->input('city') != '')
            array_push($conditionsUser, ['city', '=', $request->input('city')]);

        if($searchQuery && $searchQuery != '') {
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
        } elseif(!empty($conditionsCreative)) {
            $users = $users->whereHas('creative', function ($query) use ($conditionsCreative) {
                $query->where($conditionsCreative);
            });
        } elseif(!empty($conditionStudio)) {
            $users = $users->whereHas('studio', function ($query) use ($conditionStudio) {
                $query->where($conditionStudio);
            });
        }
        if (Input::has('connection') && $startConnectionRange != '' && $endConnectionRange != ''){
            $users = $users->has('connections', '>=', $startConnectionRange)
                    ->has('connections', '<=', $endConnectionRange)
            ->orderBy('connections_count', 'desc');
        }
        if (Input::has('project') && $startprojectRange != '' && $endprojectRange != ''){
            $users = $users->has('projects', '>=', $startConnectionRange)
                    ->has('projects', '<=', $endConnectionRange)
            ->orderBy('projects_count', 'desc');
        }
        if(!empty($conditionsUser)){
            $users = $users->where($conditionsUser);
        }
        $users = $users->paginate(config('constants.PaginateArray.1'));
        foreach($users as $key => $value)
        {
            if(!empty($value['sender']) || !empty($value['receiver'])){
                 if(isset($value['sender']['is_approved']) || isset($value['receiver']['is_approved'])){
                    $users[$key]->connection = config('constants.Connected');
                }else{
                    $users[$key]->connection = config('constants.Pending');
                }

             }else{
                $users[$key]->connection = config('constants.NotConnected');
            }
            switch ($users[$key]->type){
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

        if(count($users) > 0) {
            return view('web.user_index', compact('users', 'genres', 'genders', 'crafts', 'types', 'countries', 'searchGender', 'searchGenre', 'searchType', 'searchState', 'searchCountry', 'searchArtistry', 'searchCity', 'searchConnection', 'searchProject', 'searchQuery'));
        } else{
            return view('web.user_index',compact('users', 'genres', 'genders', 'crafts', 'types', 'countries', 'genres', 'genders', 'crafts', 'types', 'countries', 'searchGender', 'searchGenre', 'searchType', 'searchState', 'searchCountry', 'searchArtistry', 'searchCity', 'searchConnection', 'searchProject', 'searchQuery'))->withErrors(config('constants.MatchRecord'));
        }
      
    }

/* Function for return view for login page */
    public function login(Request $request)
    {
        return view('welcome');
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
        echo "<label>City:</label>";
        echo "<select>";
        foreach ($state as $value) {
            echo "<option>" . $value->name . "</option>";
        }
    }

/* Function for return view for profile page */
    public function profile(Request $request)
    {
        return view('web.profile');
    }

/* Function for return view for add project page */
    public function addProject(Request $request)
    {
        $projects = \DB::table('projects')->get();

        $user = Auth::guard('web')->user();
        $userId =0;

        if ($user != null){
            $userId = $user->id;
        }

        return view('web.addproject', compact('projects', 'userId'));
    }


/* Function for return view for create project page */
    public function createProject(Request $request)
    {
        $users = \DB::table('users')->get();
        $roles = \DB::table('roles')->get();
        $platforms = \DB::table('platforms')->get();
        return view('web.createproject', compact('users', 'roles','platforms'));
    }

/* Function for return view for project information page */
    public function projectInfo(Request $request)
    {
        $project=\DB::table('projects')->where('id', '=', $request->id)->get();
        $detail= $project[0];
        $contributors=\DB::table('project_edit_requests')->where('project_id', '=', $detail->id)
                ->join('users', 'users.id', '=','project_edit_requests.contributor_id')
                ->where('project_edit_requests.is_approved', '=', 1)
                ->get();
        $platforms=\DB::table('user_projects')
                ->join('user_platforms', 'user_platforms.user_id', '=','user_projects.user_id')
                ->where('user_projects.project_id', '=', $detail->id)
                ->get();
        $requestConnections = $this->getConnectionRequests();
        $message=$request->message;
        return view('web.projectinfo', compact('detail', 'contributors', 'platforms','requestConnections','message'));

    }

/* Function for return view for creative register page */
    public function creative(Request $request)
    {
        $genres = Genre::select('id', 'title')->get();
        $genders = Gender::select('id', 'title')->get();
        $crafts = Craft::select('id', 'title')->get();
        $types = Type::select('id', 'title')->get();
        $platforms = Platform::select('id', 'title')->get();
        return view('web.creative', compact('genres', 'genders', 'crafts', 'types', 'platforms'));
    }

/* Function for return view for studio register page */
    public function studio(Request $request)
    {
        return view('web.studio');
    }

/* Function for return view for manager register page */
    public function manager(Request $request)
    {
        return view('web.manager');
    }

/* Function for return view for managers page */
    public function managerView(Request $request)
    {
        return view('web.manager_view');
    }

    /* Function for return view for studio map page */
    public function studioMapView(Request $request)
    {
        return view('web.studio_map_view');
    }

    /* Function for return view for studio page */
    public function studioView(Request $request)
    {
        return view('web.studio_view');
    }

    /* Function for return view for studio login user profile */
    public function studioProfile(Request $request)
    {
        $user = Auth::guard('web')->user();
        $userId = $user->id;
        $studioId = \DB::table('studios')->where('user_id', '=',$userId)->first();
        $userData = \DB::table('users')
            ->join('studios', 'users.id', '=', 'studios.user_id')
            ->join('studio_images', 'studios.id', '=', 'studio_images.studio_id')
            ->where('users.id', '=', $userId)
            ->first();
        $userImg = \DB::table('studios')
            ->join('studio_images', 'studios.id', '=', 'studio_images.studio_id')
            ->where('studios.id', '=', $studioId->id)
            ->get();
        $profileImg = \DB::table('studios')
            ->join('studio_images', 'studios.id', '=', 'studio_images.studio_id')
            ->where('studios.id', '=', $studioId->id)
            ->first();
        $projectData =\DB::table('projects')
            ->join('user_projects','projects.id', '=', 'user_projects.project_id')
            ->where('user_projects.user_id', '=', $userId)
            ->get();
            return view('web.studio_profile',compact('userData','userImg','profileImg','projectData'));
    }

/* Function for return view for managers profile page */
    public function managerProfile(Request $request)
    {
        $clientData =[];
        $user = Auth::guard('web')->user();
        $userId = $user->id;
        $userData = \DB::table('users')
            ->join('managers', 'users.id', '=', 'managers.user_id')
            ->where('users.id', '=', $userId)
            ->first();
        $name = $userData->name;
        $nameArr = explode(" ", $name);
        $requestData = \DB::table('users')
        ->join('connections', 'users.id', '=', 'connections.user_id')
        ->where('users.id', '=', $userId)
        ->get();
        foreach($requestData as $rd){
            $clientData = \DB::table('users')
            ->join('connections', 'users.id', '=', 'connections.connected_user_id')
            ->where([['users.id', '=', $rd->connected_user_id],['connections.type','=', "management"]])
            ->get(); 
        }
        
        return view('web.manager_profile', compact('userData', 'nameArr','clientData'));
    }

/* Function for return view for managers view profile page for not login user */
public function managerViewProfile(Request $request,$id)
{   
    $user = Auth::guard('web')->user();
    $userId = $user->id;   
     $userData = \DB::table('users')
            ->join('managers', 'users.id', '=', 'managers.user_id')
            ->where('users.id', '=', $id)
            ->first();
        $connection = \DB::table('connections')
        ->where([['connected_user_id', '=', $id],['user_id', '=', $userId]])
        ->first();
        if($connection){
            if($connection->is_approved == "1"){
                $status = config('constants.Connected');
                $request = config('constants.Connected'); 
            }
            elseif($connection->is_approved == ""){
                $status = config('constants.Pending');
                $request = config('constants.Pending');
            }
            else{
                $status = config('constants.AddConnection');
                $request = config('constants.Request');
            }
        }
        else{
            $status = config('constants.AddConnection');
            $request = config('constants.Request');
        }
        $clientData = \DB::table('users')
        ->join('connections', 'users.id', '=', 'connections.connected_user_id')
        ->where([['users.id', '=', $id],['connections.type','=', "management"]])
        ->get();
        if(empty($clientData)){
            $clientData = \DB::table('users')
            ->join('connections', 'users.id', '=', 'connections.user_id')
            ->where([['users.id', '=', $id],['connections.type','=', "management"]])
            ->get();  
        }
       
        
      
        
    return view('web.manager_profile_view',compact('userData','status','request','clientData'));
}

/* Function for return view for managers view profile page for not login user */
public function studioViewProfile(Request $request,$id)
{
    $user = Auth::guard('web')->user();
    $userId = $user->id;   
    $studioId = \DB::table('studios')->where('user_id', '=',$id)->first();
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
    $projectData =\DB::table('projects')
        ->join('user_projects','projects.id', '=', 'user_projects.project_id')
        ->where('user_projects.user_id', '=', $id)
        ->get();
        $connection = \DB::table('connections')
        ->where([['connected_user_id', '=', $id],['user_id', '=', $userId]])
        ->first();
        if($connection){
            if($connection->is_approved == "1"){
                $status = config('constants.Connected');
            }
            elseif($connection->is_approved == ""){
                $status = config('constants.Pending');
            }
            else{
                $status = config('constants.AddConnection');
            }
        }
        else{
            $status = config('constants.AddConnection');
        }
        return view('web.studio_profile_view',compact('userData','userImg','profileImg','projectData','status'));
}

/* Function for return view for creative view profile page for not login user */
public function creativeViewProfile(Request $request,$id)
{  $clientData =[];
    $user = Auth::guard('web')->user();
    $userId = $user->id;   
    $userData = \DB::table('users')
    ->join('creatives', 'users.id', '=', 'creatives.user_id')
    ->where('users.id', '=', $id)
    ->first();
    $connection = \DB::table('connections')
    ->where([['connected_user_id', '=', $id],['user_id', '=', $userId]])
    ->first();
    if($connection){
        if($connection->is_approved == "1"){
            $status = config('constants.Connected');
        }
        elseif($connection->is_approved == ""){
            $status = config('constants.Pending');
        }
        else{
            $status = config('constants.AddConnection');
        }
    }
    else{
        $status = config('constants.AddConnection');
    }
    $requestData = \DB::table('users')
    ->join('connections', 'users.id', '=', 'connections.user_id')
    ->where('users.id', '=', $userId)
    ->get();
    foreach($requestData as $rd){
        $clientData = \DB::table('users')
        ->join('connections', 'users.id', '=', 'connections.connected_user_id')
        ->where([['users.id', '=', $rd->connected_user_id],['connections.type','=', "management"]])
        ->get(); 
    }
     return view('web.creative_profile_view',compact('userData','status','clientData'));
}


    /* Function for return view for contact page */
    public function contact(Request $request)
    {
        return view('web.contact');
    }

    /* Function for return view for faq page */
    public function faq(Request $request)
    {
        return view('web.faq');
    }

      /* Function for return view for index page */
      public function userIndex(Request $request)
      {
          return view('web.user_index');
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
        return view('web.studio_edit_profile', compact('userData'));
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
        return view('web.edit_profile', compact('userData', 'nameArr'));
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
        return view('web.manager_profile', compact('userData', 'nameArr'));

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
         $studioId = \DB::table('studios')->where('user_id', '=',$userId)->first();
         $userData = \DB::table('users')
             ->join('studios', 'users.id', '=', 'studios.user_id')
             ->join('studio_images', 'studios.id', '=', 'studio_images.studio_id')
             ->where('users.id', '=', $userId)
             ->first();
         $userImg = \DB::table('studios')
             ->join('studio_images', 'studios.id', '=', 'studio_images.studio_id')
             ->where('studios.id', '=', $studioId->id)
             ->get();
         $profileImg = \DB::table('studios')
            ->join('studio_images', 'studios.id', '=', 'studio_images.studio_id')
            ->where('studios.id', '=', $studioId->id)
            ->first();
            return view('web.studio_profile',compact('userData','userImg','profileImg'));
 
     }

/* Function for return view for connection page */
    public function connection(Request $request)
    {
        return view('web.connection');
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
            'has_profile'   => 1,
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
        return redirect('/login')->with('flash_message_success', Config::get('constants.ManagerRegister'));
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
            'has_profile'   => 1,
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
        if ($files = $request->file('files')) {
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
            return redirect('/login')->with('flash_message_success', Config::get('constants.StudioRegister'));
        }
    }
    /* Function for register new Creative in web based */
    public function registerCreative(Request $request)
    {   
        if (Auth::guard('web')->check()) {
            $user = Auth::guard($this->guard)->user();
            $userId = $user->id;
            if($user->has_profile == 1) {
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
                    'platform.*.id' => 'exists:platforms,id'
                ]);
                
                if ($validator->fails()) {

                    return redirect()->back()->withErrors($validator)->withInput(Input::all());

                }
                
                if(!empty($request->input('platform')) && !empty($request->input('link'))) {
                   $platfrm=$request->input('platform');
                   $urls=$request->input('link');
                   array_pop($platfrm);
                   array_pop($urls);
                   $platformData = $this->combineArr($platfrm, $urls);
                } else {
                    $platformData=array();
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
                    'name' => $firstName." ".$lastName,
                    'register_flag' => 1
                    );
                \DB::table('users')->where('id','=',$userId)->update($data);

                if ($files = $request->file('fileUpload')) {
                
                    $original_img = $request->file('fileUpload');
                    $extension = $request->file('fileUpload')->getClientOriginalExtension();

                    $profileImage =  time(). rand() . '.' . $extension;

                    $destinationPath = public_path("img/users");
                             
                    $files->move($destinationPath, $profileImage);

                    $insert['image'] = $profileImage;
                    $img=array('img'=>$profileImage);
                
                    $userImage=\DB::table('users')->where('id','=',$userId)->update($img);
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
                
                if($creative->save()) {
                    $user->has_profile = 1;
                    $user->save();
                }
               

                if(!empty($platformData) && count($platformData)>0) {
                    foreach ($platformData as $key => $value) {
                        if ($value != '') {
                            $userPlatform = new UserPlatforms;
                            $userPlatform->platform_id = $key;
                            $userPlatform->user_id = $userId;
                            $userPlatform->url = $value;
                            $userPlatform->created_at = now();
                            $userPlatform->updated_at =  now();
                            $userPlatform->save();
                        }
                    }
                }
                return redirect('/login')->with('flash_message_success', config('constants.ManagerRegister'));  

            }
        }else {
             return redirect('/login')->with('flash_message_error', config('constants.AuthExpire')); 
        }
        
    }

    public function combineArr($arr1, $arr2){
        return(array_combine($arr1, $arr2));
    }


/*************** Creative profile data******/
    public function creativeProfile() {
        
        if (Auth::guard($this->guard)->check()) {

            $userId = Auth::guard($this->guard)->user()->id;
            $user = null;
            $user = User::where('id', $userId)
                    ->with('creative', 'projects', 'platforms')
                    ->withCount('connections')
                    ->first();

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
            
            foreach($user['creative']['managers'] as $key => $value)
            {
                //Format variable
                $user['creative']['managers'][$key]->img = URL::to('img/users/' . $user['creative']['managers'][$key]->img);
            }
            $genres = Genre::where('id', $user['creative']['genre'])->select('id', 'title')->first();
            $genders = Gender::where('id', $user['creative']['gender'])->select('id', 'title')->first();
            $crafts = Craft::where('id', $user['creative']['craft'])->select('id', 'title')->first();
            $types = Type::where('id', $user['creative']['type'])->select('id', 'title')->first();
            $platforms = Platform::select('id', 'title')->get();
            if($this->getUserConnectionRequests()) {
                $requestConnections = $this->getUserConnectionRequests();
            }else {
                $requestConnections ='';
            }
           return view('web.profile', compact('genres', 'genders', 'crafts', 'types', 'platforms', 'user', 'requestConnections'));

        } else {
            return redirect('/login')->with('flash_message_error', config('constants.AuthExpire')); 
        }
    }



    /****************Edit Creative profile data******/
    public function editCreative() {
        $genres = Genre::select('id', 'title')->get();
        $genders = Gender::select('id', 'title')->get();
        $crafts = Craft::select('id', 'title')->get();
        $types = Type::select('id', 'title')->get();
        $platforms = Platform::select('id', 'title')->get();
        
        if (Auth::guard($this->guard)->check()) {

            $userId = Auth::guard($this->guard)->user()->id;
            $user = null;
            $user = User::where('id', $userId)
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
                    ['connections.user_id', '=', $userId],
                    ['connections.is_approved', '=', '1'],
                    ['connections.type', '=', 'management']])
                ->get();

            $user['creative']['managers'] = $managers;
            
            foreach($user['creative']['managers'] as $key => $value)
            {
                //Format variable
                $user['creative']['managers'][$key]->img = URL::to('img/users/' . $user['creative']['managers'][$key]->img);
            }
            
           return view('web.update_creative', compact('genres', 'genders', 'crafts', 'types', 'platforms', 'user'));
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
                'platform.*.id' => 'exists:platforms,id'
            ]);
            
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput(Input::all());

            }

            $profile = Creative::where('user_id', $userId)->first();
            if($request->has('fileUpload')){
                $files = $request->file('fileUpload');
                //------Delete Old Image------//
                $oldImgPath = public_path("img/users/". $profile['img']);

                if(File::exists($oldImgPath)) {
                    File::delete($oldImgPath);
                }

                //-----Create New Image-----//
                $original_img = $request->file('fileUpload');
                $extension = $request->file('fileUpload')->getClientOriginalExtension();

                $profileImage =  time(). rand() . '.' . $extension;

                $destinationPath = public_path("img/users");

                $files->move($destinationPath, $profileImage);

                $insert['image'] = $profileImage;
                $img=array('img'=>$profileImage);
                
                $userImage=\DB::table('users')->where('id','=',$userId)->update($img);
            }

            $user->name = $request->input('firstName'). " ".$request->input('lastName');
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
            $platformData=array();
            if(!empty($request->input('platform')) && !empty($request->input('link'))) {
               $platfrm=$request->input('platform');
               $urls=$request->input('link');
               array_pop($platfrm);
               array_pop($urls);
               $platformData = $this->combineArr($platfrm, $urls);
            }
            if($profile->save() && $user->save()) {
               if($request->has('platform')){ 
                    //Delete Old Platforms
                    UserPlatforms::where('user_id', $userId)->delete();

                    if(!empty($platformData)) {
                        foreach ($platformData as $key => $value) {
                            $userPlatform = new UserPlatforms;
                            $userPlatform->platform_id = $key;
                            $userPlatform->user_id = $userId;
                            $userPlatform->url = $value;
                            $userPlatform->created_at = now();
                            $userPlatform->updated_at =  now();
                            $userPlatform->save();
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
        return view('web.get_advance');   
    }

    /* Function for Submit Data for getadvance */
    public function advance(Request $request)
    {
        $user = Auth::guard('web')->user();
        $userId =0;

        if ($user != null){
            $userId = $user->id;
        }
        $advance = new Advance;
        $advance->type=$request->legal;
        $advance->is_decisionmaker=$request->is_decisionmaker;
        $advance->is_musicprimary=$request->is_musicprimary;
        $advance->is_entertainmentprimary=$request->is_entertainmentprimary;
        $advance->amount_period=$request->amount_period;
        $advance->period=$request->period;
        $advance->user_id = $userId;
        $advance->save();

        return redirect('/managerProfile')->with('flash_message_success', Config::get('constants.AdvanceRegister'));
    }
    
    public function getUserConnectionRequests(){

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
            foreach($connections as $key => $value)
            {
                $profile = User::where('id', $connections[$key]->user_id)->first();

                $connections[$key]->name = $profile['name'];
                $connections[$key]->type = $typeArray[$connections[$key]->user_type];
                $connections[$key]->img = $profile->img !='' ? $profile->img : '';
            }
            return $connections;
        }
    }


   /* Function for create new project in web based */
    public function saveProject(Request $request)
    {
        $projectImage="";
        $projectAudio="";            
                        
        if($request->file('projectImage') != null)
        {
            $file = $request->file('projectImage');
            $projectImage=$file->getClientOriginalName();
            Storage::putFileAs('public/uploads', $file, $projectImage);
        }
        if($request->file('projectAudio') != null)
        {
            $file = $request->file('projectAudio');
            $projectAudio=$file->getClientOriginalName();
            Storage::putFileAs('public/audio', $file, $projectAudio);
        }
        $project = new Project();
        $project['title'] = $request->projectTitle;
        $project['release_date'] = $request->releaseDate;
        $project['img'] = $projectImage;
        $project['audio'] = $projectAudio;
        $user = Auth::guard('web')->user();
        $userId =1;

        if ($user != null){
            $userId = $user->id;
        }
        if($project->save()) {
            $userProject = new UserProject([
                "user_id" => $userId,
                "project_id" => $project->id,
                "role" => "creator"
            ]);
            $userProject['is_approved'] = true;
            $userProject->save();
        $platformsSize = count(collect($request)->get('platforms'));
        if($request->get('contributors') != null)
        {
            $contributorsSize = count(collect($request)->get('contributors'));

            //associate project to 
            for ($i = 0; $i < $contributorsSize; $i++)
            {
                $userProject = new ProjectEditRequest([
                    "user_id" => $userId,
                    "project_id" => $project->id,
                    "contributor_id" => $request->get('contributors')[$i],
                ]);
                $userProject->save();

                //send connection request
                $this->sendConnectionRequest($userId, $request->get('contributors')[$i]);
            }
        }
        for ($i = 0; $i < $platformsSize; $i++)
        {
            $userProject = new UserPlatforms([
                "user_id" => $userId,
                "platform_id" => $request->get('platforms')[$i],
                "url" => $request->get('url')[$i]
            ]);

            $userProject->save();
        }
        }
        return redirect('/addproject')->with('flash_message_success', Config::get('constants.ProjectRegister'));
    }

    public function updateProject(Request $request, $id)
    {
        return view('web.createproject', compact('id'));
    }

    /* Function for get autocmoplete data for user */
    public function getAutocompleteData(Request $request){
        $search = $request->search;
        if($search != ''){
            $users = User::orderby('name','asc')->select('id','name','img')->where('name', 'like', '%' .$search . '%')->limit(5)->get();
        }
        return response()->json($users);
     }
  

     /* Function for create connection request in web based */
     public function sendConnectionRequest($userId, $contributorId)
     {
        $connectionSender = new Connection();

        $connectionSender->connected_user_id = $contributorId;
        $connectionSender->type = "connection";
        $connectionSender->user_id = $userId;

        if($connectionSender->save()) {
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
     }
     public function getConnectionRequests(){
        
            $user = Auth::guard('web')->user();
            $userId =1;
            if ($user != null){
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
            foreach($connections as $key => $value)
            {
                $profile = User::where('id', $connections[$key]->user_id)->first();
                $connections[$key]->name = $profile['name'];
                $connections[$key]->type = $this->getTypeAttribute($profile['type']);
                $connections[$key]->img = $profile->img !='' ? $profile->img : '';
            }
           
            return $connections;
        
    }

    public function getTypeAttribute($value){
        if($value == 1)
            return Config::get('constants.Creative');
        else if($value == 2)
            return Config::get('constants.Manager');
        else if($value == 3)
            return Config::get('constants.Studio');
        return $value;
    }

    public function acceptConnectionRequest(Request $request)
    {
        $recordId=$request->id;
        $values = array(
            'is_approved' => 1,
        );
        $conn=\DB::table('connections')->where('id', '=', $recordId)->get();
        $connection= \DB::table('connections')->where('id', '=', $recordId)->update($values);;
        $users = \DB::table('project_edit_requests')
            ->where([['user_id', '=', $conn[0]->connected_user_id],
                ['project_id','=',$request->project_id]])->update($values);
        $request->id=$request->project_id;
        $request->message=Config::get('constants.RequestAccepted');
        return $this->projectInfo($request);
    }

    public function rejectConnectionRequest(Request $request)
    {
        $recordId=$request->id;
        $values = array(
            'type' => '',
            'is_approved' => 0
        );
        $connection= \DB::table('connections')->where('id', '=', $recordId)->update($values);
        $request->id=$request->project_id;
        $request->message=Config::get('constants.RequestRejected');
        return $this->projectInfo($request);
    }

    public function getConnections(Request $request)
    {
        $user = Auth::guard('web')->user();
        $userId =1;
        if ($user != null){
        $userId = $user->id;
       }
        $connections = \DB::table('connections')
        ->leftJoin('users', 'connections.connected_user_id',
            '=', 'users.id')
        ->where([['connections.connected_user_id', '=', $userId],['connections.is_approved', '=', NULL]])
        ->select('connections.id', 'users.type AS user_type',
            'connections.user_id AS user_id', 'connections.is_approved as is_approved')
        ->get();
        $typeArr = array('1' => 'Creative', '2' => 'Manager', '3' => 'Studio');
        foreach($connections as $key => $value)
        {
            $profile = User::where('id', $connections[$key]->user_id)->first();

            $connections[$key]->name = $profile['name'];
            $connections[$key]->type = $typeArr[$connections[$key]->user_type];
            $connections[$key]->img = $profile->img !='' ? $profile->img : '';
        }
        return view('webuserlayout.connection_request', compact('connections'));
   } 

public function addConnection(Request $request,$id)
{
    $recordId=$id;
    $values = array(
        'is_approved' => 1,
    );
    $conn=\DB::table('connections')->where('id', '=', $recordId)->get();
    $connection= \DB::table('connections')->where('id', '=', $recordId)->update($values);
    return redirect('/user-index')->with('flash_message_success', Config::get('constants.RequestAccepted'));
}
 
public function deleteConnection(Request $request,$id)
{
    $recordId=$id;
    $values = array(
        'is_approved' => 1,
    );
    $conn=\DB::table('connections')->where('id', '=', $recordId)->get();
    $connection= \DB::table('connections')->where('id', '=', $recordId)->delete();
    return redirect('/user-index')->with('flash_message_success', Config::get('constants.RequestRejected'));
}

}
