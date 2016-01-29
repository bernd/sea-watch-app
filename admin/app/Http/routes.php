<?php
use App\emergencyCase;
use App\operation_areas;

header('Access-Control-Allow-Origin:*');

class app_config{
    public static $app_name = 'Sea-Watch.APP';
}

/****************   Model binding into route **************************/
Route::model('language', 'App\Language');
Route::model('user', 'App\User');

Route::pattern('id', '[0-9]+');
Route::pattern('slug', '[0-9a-z-_]+');

/***************    Site routes  **********************************/
Route::get('/', 'HomeController@index');
Route::get('home', function(){ 
    return Redirect::to('/', 301); 
});


Route::get('/map', 'HomeController@map');

Route::get('about', 'PagesController@about');
Route::get('contact', 'PagesController@contact');


Route::get('operation_areas/create', 'operation_areas@create');
Route::post('operation_areas/create', 'operation_areas@store');


// route to show our edit form
Route::get('cases/edit/{id}', array('as' => 'case.edit', function($id) 
{
    // return our view and Nerd information
    return View::make('cases.update') // pulls app/views/nerd-edit.blade.php
        ->with('case', emergencyCase::find($id));
}));

Route::post('cases/edit/{id}', 'EmergencyCaseController@update');

Route::post('api/messages/send', 'ApiController@sendMessage');

Route::post('api/cases/create', 'ApiController@add_request');


//not supposed for usage with the app!
Route::post('api/cases/sendMessageCrew', 'ApiController@sendMessageCrew');

Route::get('api/cases/operation_area/{id}', 'ApiController@casesInOperationArea');

Route::post('api/cases/ping', 'ApiController@ping');

Route::post('api/cases/getInvolved', 'ApiController@getInvolved');

Route::post('api/reloadApp', 'ApiController@reloadApp');

Route::post('api/reloadBackend', 'ApiController@reloadBackend');

Route::post('api/checkForUpdates', 'ApiController@checkForUpdates');




//Route::any('aapi/cases/create', function(){
//    return 'asdasd';
//});





Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);

/***************    Admin routes  **********************************/
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function() {

    # Admin Dashboard
    Route::get('dashboard', 'Admin\DashboardController@index');

    # Language
    Route::get('language/data', 'Admin\LanguageController@data');
    Route::get('language/{language}/show', 'Admin\LanguageController@show');
    Route::get('language/{language}/edit', 'Admin\LanguageController@edit');
    Route::get('language/{language}/delete', 'Admin\LanguageController@delete');
    Route::resource('language', 'Admin\LanguageController');

    # Users
    Route::get('user/data', 'Admin\UserController@data');
    Route::get('user/{user}/show', 'Admin\UserController@show');
    Route::get('user/{user}/edit', 'Admin\UserController@edit');
    Route::get('user/{user}/delete', 'Admin\UserController@delete');
    Route::resource('user', 'Admin\UserController');
});
