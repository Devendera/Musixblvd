<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'v1'], function() {

    Route::post('myurl', 'ProjectController@urlDomain');
    Route::get('test', 'AuthController@test');

    //Non protected routes
    Route::post('register', 'AuthController@register');
    Route::post('provider-login', 'AuthController@loginWithProvider');
    Route::post('login', 'AuthController@login');
    Route::post('forgot-password', 'AuthController@forgotPassword');
    Route::get('country', 'CountryController@index');
    Route::post('state', 'StateController@index');
    Route::get('craft', 'CraftController@index');
    Route::get('genre', 'GenreController@index');
    Route::get('platform', 'PlatformController@index');
    Route::get('status', 'StatusController@index');
    Route::get('type', 'TypeController@index');
    Route::get('gender', 'GenderController@index');
    Route::post('invite', 'AuthController@inviteByEmail');


    //Protected routes
    Route::group(['middleware' => ['jwt.verify']], function() {

        Route::group(['prefix' => 'user'], function() {

            Route::post('update-notification-token', 'UserController@updateNotificationToken');
            Route::get('logout', 'UserController@logout');
            Route::post('refresh', 'UserController@refresh');
            Route::get('me', 'UserController@me');
            Route::get('creatives', 'UserController@getAllCreatives');
            Route::get('managers', 'UserController@getAllManagers');
            Route::get('studios', 'UserController@getAllStudios');
            Route::get('connected-users', 'UserController@getConnectedUsers');

            //Media Platforms
            Route::post('media-providers/add', 'MediaProviderController@addProvider');
            Route::get('media-providers', 'MediaProviderController@index');

            //Search for all users
            Route::post('search', 'UserController@search');

            //Search for creatives
            Route::post('search-creatives', 'UserController@searchCreatives');

            //Search for studios
            Route::post('search-studios', 'UserController@searchStudios');

            //Filter users with special attributes
            Route::post('filter', 'UserController@filterUsers');

            //Ask creative for management
            Route::post('request-management', 'UserController@requestManagement');

            Route::get('connections', 'ProfileController@getAllConnectedUsers');
            Route::post('update-email', 'UserController@updateEmail');
            Route::post('update-password', 'UserController@updatePassword');
            Route::get('management-requests', 'ManagerController@getManagementRequests');
            Route::post('verify-management-request', 'ManagerController@verifyManagerRequest');
            Route::post('decline-management-request', 'ManagerController@declineManagerRequest');
            Route::post('add-legal-distribution-form', 'UserController@addLegalAndDistributionFor');


            Route::group(['prefix' => 'profile'], function() {

                //Get Profile
                Route::get('me', 'ProfileController@getMyProfile');
                Route::post('/', 'ProfileController@getUserProfile');

                //Create Profiles
                Route::post('create-creative', 'ProfileController@createCreative');
                Route::post('create-manager', 'ProfileController@createManager');
                Route::post('create-studio', 'ProfileController@createStudio');

                //Update Profiles
                Route::post('update-creative', 'ProfileController@updateCreative');

                Route::post('search-by-name', 'ProfileController@searchByName');
                Route::post('search-users-by-name', 'ProfileController@searchUsersByName');
            });

            Route::group(['prefix' => 'manager'], function() {
                Route::get('/', 'ManagerController@index');
                Route::get('me', 'ManagerController@me');
                Route::post('create', 'ManagerController@create');
                Route::post('add-creative', 'ManagerController@addCreative');
                Route::post('add-manager', 'ManagerController@addManager');
                Route::post('search-creatives-by-name', 'ManagerController@searchByName');
                Route::post('search-managers-by-name', 'ManagerController@searchManagersByName');
                Route::get('my-creatives', 'ManagerController@getMyCreatives');
                Route::get('{id}/creatives', 'ManagerController@getOtherManagerCreatives');
                Route::get('/{id}', 'ManagerController@getOtherManagerProfile');
            });

            Route::group(['prefix' => 'studio'], function() {
                Route::get('/', 'StudioController@index');
                Route::post('create', 'StudioController@create');
                Route::get('me', 'StudioController@me');
                Route::post('search-by-name', 'StudioController@searchStudioByName');
                Route::get('/{id}', 'StudioController@getOtherStudioProfile');
            });

        });

        Route::group(['prefix' => 'connection'], function() {

            Route::post('create', 'ConnectionController@create')->name('create');
            Route::post('delete', 'ConnectionController@delete')->name('delete');
            Route::post('verify', 'ConnectionController@verify')->name('verify');
            Route::get('requests', 'ConnectionController@getConnectionRequests')->name('requests');
        });

        Route::group(['prefix' => 'project'], function() {

            Route::get('/', 'ProjectController@getAllProjects');
            Route::post('/', 'ProjectController@getProjectById');
            Route::post('for-update', 'ProjectController@getProjectByIdForUpdate');


            Route::post('contributor-search', 'ProjectController@contributorSearch');
            Route::post('create', 'ProjectController@create')->name('create');
            Route::post('update', 'ProjectController@update')->name('update');
            Route::post('combine', 'ProjectController@combine')->name('combine');
            Route::post('request-edit', 'ProjectController@checkUpdateApproval');
            Route::post('decline', 'ProjectController@decline')->name('decline');
            Route::post('delete', 'ProjectController@delete')->name('delete');
            Route::get('me', 'ProjectController@getMyProjects')->name('me');
            Route::post('/other', 'ProjectController@getUserProjectById')->name('/other');
            Route::get('requests', 'ProjectController@getProjectRequests')->name('requests');
            Route::post('verify', 'ProjectController@verify')->name('verify');
            Route::post('claim-credits', 'ProjectController@claimCredits');
            Route::post('verify-project-contributor-request', 'ProjectController@verifyProjectContributorRequest');
            Route::get('/{id}', 'ProjectController@getProjectById');
        });

        Route::group(['prefix' => 'activity'], function() {

            Route::get('/', 'ActivityController@getMyActivities');
            Route::post('verify', 'ActivityController@verifyActivity');
            Route::post('delete', 'ActivityController@delete');
        });

    });


});


