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
    return View::make('cases.update')
        ->with('case', emergencyCase::find($id));
}));

Route::post('cases/edit/{id}', 'EmergencyCaseController@update');

/**
 * @api {post} /api/messages/send SendMessage
 * @apiDescription sends message and adds location
 * @apiName SendMessage
 * @apiGroup message
 *
 * @apiParam {Number} emergency_case_id id of the opened case.
 * @apiParam {String} sender_type refugee/land_operator_rumors.
 * @apiParam {String} sender_id sender device unique id/fingerprint
 * @apiParam {String} geo_data geo JSON string
 *
 * @apiSuccess {String} firstname Firstname of the User.
 * @apiSuccess {String} lastname  Lastname of the User.
 */
Route::post('api/messages/send', 'ApiController@sendMessage');


/**
 * @api {post} /api/cases/checkForOpenCase
 * @apiDescription checks if open case with session_token exists
 * @apiName CheckForOpenCase
 * @apiGroup cases
 *
 * @apiParam {String} session_token user device unique identifier(UUID)
 *
 * @apiSuccess {Number}  emergency_case_ids
 * @apiSuccess {Number}  operation_area
 * @apiSuccess {String}  JSON emergency_case_messages
 */
Route::post('api/cases/checkForOpenCase', 'ApiController@checkForOpenCase');

/**
 * @api {get} /api/cases/create CreateCase
 * @apiDescription creates new case if submitted geolocation is in any operation area
 * @apiName CreateCase
 * @apiGroup case
 * 
 * @apiParam {String} [status] 
 * @apiParam {String} [condition] 
 * @apiParam {String} [boat_type] 
 * @apiParam {bool} [other_involved] 
 * @apiParam {bool} [engine_working] 
 * @apiParam {Number} [passenger_count] 
 * @apiParam {String} [additional_informations] 
 * @apiParam {Number} [spotting_distance] 
 * @apiParam {Number} [spotting_direction] in degrees
 * @apiParam {String} [picture] base64 encoded picture
 * @apiParam {String} source source
 * @apiParam {String} location_data  geo JSON string
 *
 * @apiSuccess {Number} case_id
 */
Route::post('api/cases/create', 'ApiController@add_request');

/**
 * @api {post} api/cases/sendMessageCrew submit message (only for backend, auth required)
 * @apiName SendMessageCrew
 * @apiGroup message

 * @apiParam {Number} case_id
 * @apiParam {String} message
 *
 * @apiSuccess {Number} message_id
 */
Route::post('api/cases/sendMessageCrew', 'ApiController@sendMessageCrew');

/**
 * @api {get} api/cases/operation_area/:id CasesInOperationArea
 * @apiDescription sends get cases in operation area (for backend)
 * @apiName CasesInOperationArea
 * @apiGroup cases
 *
 * @apiSuccess {String} JSON cases
 */
Route::get('api/cases/operation_area/{id}', 'ApiController@casesInOperationArea');

/**
 * @api {post} api/cases/getInvolved GetInvolved
 * @apiDescription adds user to db table involved_users and returns message (auth required)
 * @apiName GetInvolved
 * @apiGroup cases
 * @apiParam {Number} case_id
 *
 * @apiSuccess {String} JSON emergency_case_messages
 */
Route::post('api/cases/getInvolved', 'ApiController@getInvolved');



Route::post('api/cases/closeCase', 'ApiController@closeCase');


/**
 * @api {post} api/reloadApp reloadApp
 * @apiDescription updates location and receives new messages
 * @apiName ReloadApp
 * @apiParam {Number} emergency_case_id
 * @apiParam {Number} last_message_received
 * @apiParam {String} geo_data geo JSON string
 *
 * @apiSuccess {String} JSON messages
 */
Route::post('api/reloadApp', 'ApiController@reloadApp');

/**
api/reloadBackend reload backend (auth required)
 */
Route::post('api/reloadBackend', 'ApiController@reloadBackend');

/**
load case box
 * @apiParam {Number} case_id
 */
Route::post('api/loadCaseBox', 'ApiController@loadCaseBox');



//Deprecated
Route::post('api/checkForUpdates', 'ApiController@checkForUpdates');
Route::post('api/cases/ping', 'ApiController@ping');




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
    
    # Cases
    Route::get('case/data', 'Admin\CaseController@data');
    Route::get('case/{case}/show', 'Admin\CaseController@show');
    Route::get('case/{case}/edit', 'Admin\CaseController@edit');
    Route::get('case/{case}/delete', 'Admin\CaseController@delete');
    Route::resource('case', 'Admin\CaseController');

    # Users
    Route::get('user/data', 'Admin\UserController@data');
    Route::get('user/{user}/show', 'Admin\UserController@show');
    Route::get('user/{user}/edit', 'Admin\UserController@edit');
    Route::get('user/{user}/delete', 'Admin\UserController@delete');
    Route::resource('user', 'Admin\UserController');
});
