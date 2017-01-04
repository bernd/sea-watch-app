<?php
use App\emergencyCase;
use App\operation_areas;

//@sec

header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");

header("Access-Control-Allow-Methods: OPTIONS, GET, POST, PUT");

class app_config{
    public static $app_name = 'Sea-Watch.APP';
}

/****************   Model binding into route **************************/
Route::model('language', 'App\Language');
Route::model('user', 'App\User');
Route::model('vehicle', 'App\Vehicle');

Route::pattern('id', '[0-9]+');
Route::pattern('slug', '[0-9a-z-_]+');

/***************    Site routes  **********************************/

Route::filter('auth', function() {
    if (Auth::guest())
    return Redirect::guest('auth/login');
});



Route::group(array('before' => 'auth'), function(){

    Route::get('/', 'HomeController@index');
    Route::get('home', function(){ 
        return Redirect::to('/', 301); 
    });


    Route::get('/map', 'HomeController@map');
    Route::get('/vehicleGrid', 'HomeController@vehicleGrid');
    Route::get('/adminGrid', 'HomeController@adminGrid');

    Route::get('about', 'PagesController@about');
    Route::get('contact', 'PagesController@contact');



    // route to show our edit form
    Route::get('cases/edit/{id}', array('as' => 'case.edit', function($id)
    {
        return View::make('cases.update')
            ->with('case', emergencyCase::find($id));
    }));

    Route::post('cases/edit/{id}', 'EmergencyCaseController@update');
});

/**
 * @api {post} /api/user/auth userauth
 * @apiDescription auhtorize user and send token
 * @apiName UserAuth
 * @apiGroup auth
 *
 *
 * @apiSuccess {String} token authtoken.
 */
Route::post('api/user/auth', 'ApiController@auth');

/**
 * @api {post} /api/user/token tokenCheck
 * @apiDescription check token for validity and expiration
 * @apiName 
 * @apiGroup auth
 *
 *
 */
Route::post('api/user/token', 'ApiController@token');

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
Route::get('api/messages', 'ApiController@getMessages');


/**
 * @api {post} /api/cases/checkForOpenCaseSpotter
 * @apiDescription checks if open cases with session_token exist
 * @apiName checkForOpenCaseSpotter
 * @apiGroup cases
 *
 * @apiParam {String} session_token user device unique identifier(UUID)
 *
 * @apiSuccess {Number}  operation_area
 * @apiSuccess {String}  JSON emergency_cases
 * @apiSuccess {String}  JSON emergency_case_messages
 */
Route::get('api/cases/spotter', 'ApiController@getSpotterCases');

/**
 * @api {get} /api/cases/create CreateCase
 * @apiDescription creates

/**
 * @api {post} /api/cases/checkForOpenCaseSpotter
 * @apiDescription checks if open cases with session_token exist
 * @apiName checkForOpenCaseSpotter
 * @apiGroup cases
 *
 * @apiParam {String} session_token user device unique identifier(UUID)
 *
 * @apiSuccess {Number}  operation_area
 * @apiSuccess {String}  JSON emergency_cases
 * @apiSuccess {String}  JSON emergency_case_messages
 */

Route::get('api/cases/spotter', 'ApiController@getSpotterCases');
 

 /*
 * @api {get} /api/cases/spotter getSpotterCases
 * @apiName CreateCase
 * @apiGroup cases
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
Route::put('api/case/{id}', 'ApiController@updateCase');
Route::put('api/caseLocation/{id}', 'ApiController@updateCaseLocation');

Route::post('api/cases/reloadSpotter/',  'ApiController@reloadSpotter');
/**
 * @api {get} /api/cases/create CreateCase
 * @apiDescription creates new case if submitted geolocation is in any operation area
 * @apiName CreateCase
 * @apiGroup cases
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
 * @api {post} api/cases/getInvolved GetInvolved
 * @apiDescription adds user to db table involved_users and returns message (auth required)
 * @apiName GetInvolved
 * @apiGroup cases
 * @apiParam {Number} case_id
 *
 * @apiSuccess {String} JSON emergency_case_messages
 */
Route::post('api/cases/getInvolved', 'ApiController@getInvolved');



/**
 * @api {post} api/cases/closeCase closeCase
 * @apiDescription Updates boat_status or deletes case if reason=accidentally||solved_by_client.
 * @apiName CloseCase
 * @apiGroup cases
 * @sec validation required!
 * @apiParam {String} reason
 */
Route::post('api/cases/closeCase', 'ApiController@closeCase');


/**
 * @api {post} api/reloadApp reloadApp
 * @apiDescription updates location and receives new messages
 * @apiName ReloadApp
 * @apiParam {Number} emergency_case_id
 * @apiParam {Number} last_message_received
 * @apiParam {String} geo_data geo JSON string
 * @apiGroup App
 *
 * @apiSuccess {String} JSON messages
 */
Route::post('api/reloadApp', 'ApiController@reloadApp');

/**
api/reloadBackend reload backend (auth required)
 */
Route::post('api/reloadBackend', 'ApiController@reloadBackend');

/**
 * @api {post} api/loadCaseBox loadCaseBox
 * @apiDescription Loads Casebox (e.g. for views home_map and home_cases)
 * @apiName LoadCaseBox
 * @apiParam {Number} case_id
 * @apiGroup cases
 *
 * @apiSuccess {String} HTML casebox
 */
Route::post('api/loadCaseBox', 'ApiController@loadCaseBox');


/**
 * @api {post} api/getVehicles getVehicles
 * @apiDescription Returns all vehicles (e.g. for view home_map)
 * @apiName GetVehicles
 * @apiGroup vehicles
 *
 * @apiSuccess {String} HTML casebox
 */
Route::get('api/getVehicles', 'ApiController@getVehicles');


/**
 * @api {post} api/getVehicles getVehicles
 * @apiDescription Updates Position for vehicle (e.g. for view home_map)
 * @apiName UpdatePosition
 * @apiGroup vehicles
 */
Route::post('api/vehicle/updatePosition', 'ApiController@updateVehiclePosition');



Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);



/***************    Admin routes  **********************************/
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function() {

    # Admin Dashboard
    Route::get('dashboard', 'Admin\DashboardController@index');

    # Operation Areas
    Route::get('operationAreas/', 'Admin\OperationAreaController@index');
    Route::get('operationAreas/create', 'Admin\OperationAreaController@create');
    Route::post('operationAreas/create', 'Admin\OperationAreaController@store');
    Route::post('operationArea/{operationArea}/delete', 'Admin\OperationAreaController@destroy');
    Route::get('operation_area/data', 'Admin\OperationAreaController@data');
    Route::get('operationAreas/{operationArea}/delete', 'Admin\OperationAreaController@delete');
    Route::resource('operationArea', 'Admin\OperationAreaController');
    
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
    
    # Vehicles
    Route::get('vehicle/data', 'Admin\VehicleController@data');
    Route::get('vehicle/{vehicle}/show', 'Admin\VehicleController@show');
    Route::get('vehicle/{vehicle}/edit', 'Admin\VehicleController@edit');
    Route::get('vehicle/{vehicle}/delete', 'Admin\VehicleController@delete');
    Route::resource('vehicle', 'Admin\VehicleController');
});
