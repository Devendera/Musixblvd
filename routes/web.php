<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
Route::get('/v', function () {
    return view('email.invalid');
});
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    // return what you want
});
Route::get('user/verify/{verification_code}', 'AuthController@verifyUser')->name('email.verify');
Route::get('user/verify-new-email/{verification_code}', 'AuthController@verifyUserNewEmail')->name('email.verify_new_email');
Route::get('user/reset-password/{verification_code}', 'AuthController@showPasswordResetForm')->name('change.password.form');
Route::post('user/reset-password', 'AuthController@changePassword')->name('change.password');
//Testing Emails
Route::get('mailable', function () {
    return new App\Mail\SendMail(null, 4);
});
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/login', 'UserWebController@login')->name('login');
Route::post('login', 'AuthController@loginweb');
Route::post('register', 'AuthController@registerweb');
Route::get('/register', 'UserWebController@register')->name('register');
Route::post('forgot-password', 'AuthController@forgotPasswordUser');
Route::get('forgot-password', 'AuthController@forgetUserPassword');
Route::get('resetpassword/{email}','AuthController@forgetPasswordForm');
Route::post('saveNewPassword','AuthController@saveNewPassword');
Route::get('/connection', 'UserWebController@connection')->name('connection');
Route::get('/managerView', 'UserWebController@managerView')->name('managerView');
Route::get('/studioView', 'UserWebController@studioView')->name('studioView');
Route::get('/studioMapView', 'UserWebController@studioMapView')->name('studioMapView');
Route::get('/advance', 'UserWebController@getAdvance')->name('advance');
Route::post('advance', 'UserWebController@advance');
Route::get('/contact', 'UserWebController@contact')->name('contact');
Route::get('/faq', 'UserWebController@faq')->name('faq');
Route::group(['middleware' => 'auth:web'],function(){
Route::get('/settings', 'UserWebController@settings')->name('settings');
Route::get('/language', 'UserWebController@language')->name('language');
Route::post('/update-language', 'UserWebController@updateLanguage');
Route::get('changeUserPassword','AuthController@changeUserPassword');
Route::post('saveChangePassword','AuthController@saveChangePassword');
Route::get('/index', 'UserWebController@index')->name('index');
Route::post('/getAutocompleteData','UserWebController@getAutocompleteData')->name('autocomplete');
Route::post('/acceptConnection', 'UserWebController@acceptConnectionRequest')->name('acceptConnection');
Route::post('/rejectConnection', 'UserWebController@rejectConnectionRequest')->name('rejectConnection');
Route::get('/studio/profile', 'UserWebController@studioProfile')->name('studioProfile');
Route::get('/addproject', 'UserWebController@addproject')->name('addproject');
Route::get('/createproject', 'UserWebController@createproject')->name('createproject');
Route::post('/createproject', 'UserWebController@saveProject')->name('saveProject');
Route::get('/createproject/{id}', 'UserWebController@updateProject')->name('updateProject');
Route::get('/projectInfo/{id}', 'UserWebController@projectInfo')->name('projectInfo');
Route::post('/claim-credit/{id}', 'UserWebController@claimCredit')->name('claimCredit');
Route::get('/streaming', 'UserWebController@viewStreaming')->name('streaming');
Route::post('/getStreaming', 'UserWebController@getStreaming')->name('getStreaming');
Route::get('/studio', 'UserWebController@studio')->name('studio');
Route::get('/manager', 'UserWebController@manager')->name('manager');
Route::post('registerManager', 'UserWebController@registerManager');
Route::post('registerStudio', 'UserWebController@registerStudio');
Route::post('updateManager', 'UserWebController@updateManager');
Route::get('/creative', 'UserWebController@creative')->name('creative');
Route::post('register-creative', 'UserWebController@registerCreative')->name('registerCreative');
Route::get('my-profile', 'UserWebController@creativeProfile')->name('creativeProfile');
Route::get('edit-creative', 'UserWebController@editCreative')->name('editCreative');
Route::post('update-creative', 'UserWebController@updateCreative')->name('updateCreative');
Route::get('/managerProfile', 'UserWebController@managerProfile')->name('managerProfile');
Route::post('/updateStudioProfile', 'UserWebController@updateStudioProfile')->name('updateStudioProfile');
Route::get('/studioEditProfile', 'UserWebController@studioEditProfile')->name('studioEditProfile');
Route::get('/editProfile', 'UserWebController@editProfile')->name('editProfile');
Route::post('/getSpotify', 'UserWebController@getSpotify')->name('getSpotify');
Route::get('/connected-users', 'ConnectionsWebController@getConnectedUsers');
Route::get('confirm-request/{id}','ConnectionsWebController@confirmRequest');
Route::get('/deleteStreaming/{id}', 'UserWebController@deleteStreaming')->name('deleteStreaming');
Route::get('/terms', 'UserWebController@getTerms')->name('getTerms');
Route::get('/user-chat', 'UserWebController@userChat')->name('userChat');
Route::get('/get-chat', 'UserWebController@getChat')->name('getChat');
Route::get('/save-chat', 'UserWebController@saveChat')->name('saveChat');
Route::post('/combineProject', 'UserWebController@combineProject')->name('combineProject');
Route::get('/view-activity', 'UserWebController@getActivity')->name('getActivity');
Route::post('/loadmore/load_data', 'UserWebController@loadData')->name('loadmore.load_data');
Route::get('/notification', 'UserWebController@notification')->name('notification');
Route::get('/messageCount', 'UserWebController@messageCount')->name('messageCount');
Route::get('/userMessageCount', 'UserWebController@userMessageCount')->name('userMessageCount');
});
Route::get('/manager-view/{id}', 'UserWebController@managerViewProfile')->name('manager-view');
Route::get('/studio-view/{id}', 'UserWebController@studioViewProfile')->name('studio-view');
Route::get('/creative-view/{id}', 'UserWebController@creativeViewProfile')->name('creative-view');
Route::get('/getState', 'UserWebController@getState')->name('getState');
Route::post('/saveContact', 'UserWebController@saveContact')->name('saveContact');
Route::get('/', 'UserWebController@userIndex')->name('userIndex');
Route::get('/explore', 'ExploreController@explore')->name('explore');
Route::get('creatives', 'ConnectionsWebController@getAllCreatives');
Route::get('managers', 'ConnectionsWebController@getAllManagers');
Route::get('studios', 'ConnectionsWebController@getAllStudios');
Route::post('create-connections', 'ConnectionsWebController@createConnections')->name('createConnections');
Route::post('create-management', 'ConnectionsWebController@createRequestManagement')->name('createRequestManagement');
Route::get('explore-filter', 'ExploreController@exploreFilter')->name('exploreFilter');
Route::post('search-filter', 'ExploreController@SearchFilter')->name('SearchFilter');
Route::get('country/{country}/states', 'ExploreController@getStates')->name('states');
Route::get('/logout', 'AuthController@logout')->name('logout');
Route::get('/user-index', 'UserWebController@userIndex')->name('userIndex');
Route::get('/get-connection-request', 'UserWebController@getConnections')->name('get-connection-request');
Route::get('/add-connection/{id}', 'UserWebController@addConnection')->name('add-connection');
Route::get('/delete-connection/{id}', 'UserWebController@deleteConnection')->name('delete-connection');
Route::get('/delete-project/{id}', 'UserWebController@deleteProject')->name('delete-project');
Route::get('/term', 'UserWebController@getTerms')->name('getTerms');
Route::get('/{lang}', function ($lang) {
    App::setlocale($lang);
    return view('web.user_index');
});