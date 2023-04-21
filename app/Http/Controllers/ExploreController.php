<?php
namespace App\Http\Controllers;

use App\Connection;
use App\Country;
use App\Craft;
use App\Creative;
use App\Gender;
use App\Genre;
use App\Http\Controllers;
use App\Manager;
use App\Studio;
use App\Type;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;

class ExploreController extends Controller
{
    protected $redirectTo = 'login',
    $guard = 'web';
    /* Function for return view for explore filters page */
    public function exploreFilter(Request $request)
    {
        $genres = Genre::select('id', 'title')->get();
        $genders = Gender::select('id', 'title')->get();
        $types = Type::select('id', 'title')->get();
        $countries = Country::select('id', 'name')->get();
        $crafts = Craft::select('id', 'title')->get();
        $user = Auth::guard($this->guard)->user();
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
            array_push($conditionsCreative, ['creatives.type', '=', $request->input('type')]);
            array_push($conditionStudio, ['studios.type', '=', $request->input('type')]);
        }
        if (Input::has('artistry') && $request->input('artistry') != '') {
            array_push($conditionsCreative, ['creatives.craft', '=', $request->input('artistry')]);
        }

        if (Input::has('genre') && $request->input('genre') != '') {
            array_push($conditionsCreative, ['creatives.genre', '=', $request->input('genre')]);
        }

        if (Input::has('gender') && $request->input('gender') != '') {
            array_push($conditionsCreative, ['creatives.gender', '=', $request->input('gender')]);
        }

        if (Input::has('country') && $request->input('country') != '') {
            array_push($conditionsUser, ['users.country', '=', $request->input('country')]);
        }

        if (Input::has('state') && $request->input('state') != '') {
            array_push($conditionsUser, ['users.state', '=', $request->input('state')]);
        }

        if (Input::has('city') && $request->input('city') != '') {
            array_push($conditionsUser, ['users.city', '=', $request->input('city')]);
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
            if (Auth::guard('web')->check()) {
                if (!empty($value['sender']) || !empty($value['receiver'])) {
                    if (isset($value['sender']['is_approved']) || isset($value['receiver']['is_approved'])) {
                        $users[$key]->connection = config('constants.Connected');
                    } else {
                        $users[$key]->connection = config('constants.Pending');
                    }

                } else {
                    $users[$key]->connection = config('constants.NotConnected');
                }

            } else {
                $users[$key]->connection = config('constants.Connected');
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
        if (count($users) > 0) {
            return view('web.explore_filter', compact('users', 'count', 'genres', 'genders', 'crafts', 'types', 'countries', 'searchGender', 'searchGenre', 'searchType', 'searchState', 'searchCountry', 'searchArtistry', 'searchCity', 'searchConnection', 'searchProject', 'searchQuery'));
        } else {
            return view('web.explore_filter', compact('users', 'count', 'genres', 'genders', 'crafts', 'types', 'countries', 'genres', 'genders', 'crafts', 'types', 'countries', 'searchGender', 'searchGenre', 'searchType', 'searchState', 'searchCountry', 'searchArtistry', 'searchCity', 'searchConnection', 'searchProject', 'searchQuery'))->withErrors(config('constants.MatchRecord'));
        }
    }

    /* Function for return view for explore page */
    public function explore(Request $request)
    {
        $user = Auth::guard($this->guard)->user();
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
        if ($searchQuery && $searchQuery != '') {
            $users = $users->whereHas('creative', function ($query) use ($searchQuery) {
                $query->where('name', 'LIKE', '%' . $searchQuery . '%');
            })
                ->orWhereHas('manager', function ($query) use ($searchQuery) {
                    $query->where('name', 'LIKE', '%' . $searchQuery . '%');
                })
                ->orWhereHas('studio', function ($query) use ($searchQuery) {
                    $query->where('name', 'LIKE', '%' . $searchQuery . '%');
                });
        }
        $users = $users->paginate(config('constants.PaginateArray.1'));

        foreach ($users as $key => $value) {
            if (Auth::guard('web')->check()) {
                if (!empty($value['sender']) || !empty($value['receiver'])) {
                    if (isset($value['sender']['is_approved']) || isset($value['receiver']['is_approved'])) {
                        $users[$key]->connection = config('constants.Connected');
                    } else {
                        $users[$key]->connection = config('constants.Pending');
                    }

                } else {
                    $users[$key]->connection = config('constants.NotConnected');
                }

            } else {
                $users[$key]->connection = config('constants.Connected');
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
        $count = "";
        if (count($users) > 0) {
            return view('web.explore', compact('users', 'searchQuery', 'count'));
        } else {
            return view('web.explore', compact('users', 'searchQuery', 'count'))->withErrors(config('constants.MatchRecord'));
        }
    }
    /******************Function to get states associate with country id **********/
    public function getStates(Country $country)
    {
        return $country->states()->select('id', 'name')->get();
    }
}
