<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\emergencyCase;
use App\emergencyCaseLocation;
use App\emergencyCaseMessage;
use App\involvedUsers;
use App\Operation_area;
use App\pointLocation;
use App\Vehicle;
use App\User;

use JWTAuth;


use Carbon\Carbon;


function addLocation($emergency_case_id, $geo_data){
        if(!$geo_data['heading']){
            $geo_data['heading'] = 1337;
        }
        $emergencyCaseLocation = new emergencyCaseLocation(['lon'=>(float)$geo_data['longitude'],
                'lat'=>(float)$geo_data['latitude'],
                'accuracy'=>$geo_data['accuracy'],
                'heading'=>$geo_data['heading']]);
        $emergencyCaseLocation->emergency_case_id = $emergency_case_id;
        $emergencyCaseLocation->save();
        
        
        //update emergency_case´s updated_at timestamp 
        $emergency_case = emergencyCase::find($emergency_case_id);
        $emergency_case->updated_at = date('Y-m-d H:i:s', time());
        $emergency_case->save();
        
        return $emergencyCaseLocation->id;
}

class ApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['only' => 'getInvolved']);
    }
    //returns location_area for lon and lat
    public function getLocationArea($lon, $lat){
        
	$operation_areas = Operation_area::all();
        
        
        $point = "$lon $lat";
        
        $polygon = array();
        foreach($operation_areas AS $operation_area){
            $coordinateArray = json_decode($operation_area["polygon_coordinates"]);
            foreach($coordinateArray AS $coordinate){
                $polygon[] = $coordinate[0].' '.$coordinate[1];
            }
            

            $pointLocation = new pointLocation();
            if($pointLocation->pointInPolygon($point, $polygon) == 'inside'){
                return $operation_area["id"];
            }
        }
        
        
    }
    
    public function auth(Request $request){
        if (Auth::attempt(array('username' => $request->username, 'password' => $request->password)))
        {
            
            $user = User::where('username',$request->username)->first();
            $token = JWTAuth::fromUser($user);
        
            $response = array();
            $response["userid"] = $user->id;
            $response["token"] = $token;
            
        }else{
            $response["error"] = 'auth failed';
        }
        
        return $response;
    }
    //checks for updates in the admin panel
    //the app uses reloadApp()
    //depreciated!
    public function checkForUpdates(Request $request){
        $all = $request->all();
        
        $last_updated = $all['last_updated'];
        
        $result = [];
        
        $operation_areas = Operation_area::where('updated_at', '>', $last_updated)->get();
	$emergency_cases = emergencyCase::where('updated_at', '>', $last_updated)->get();
        
        $result['error'] = null;
        $result['data'] = ['operation_areas'=>$operation_areas, 'emergency_cases'=>$emergency_cases];
        
        return $result;
    }
    
    /**
     * used in refugee_app
     * return messages with message_id > last_message_received and case_id
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reloadApp(Request $request){
        $all = $request->all();
        
        $emergency_case_id = $all['emergency_case_id'];
        
        if(isset($all['geo_data'])&&$all['geo_data']!='undefined'){
            $geo_data = json_decode($all['geo_data'], true);

            $geo_data['heading'] = 0;

            addLocation($emergency_case_id, $geo_data);
        }
        
        
        
        $emergencyCaseMessages = $this->getMessagesFromDB($emergency_case_id, $all['last_message_received']);

        
        $result = [];
        $result['error'] = null;
        $result['data']['messages'] = $emergencyCaseMessages;
        return $result;
    }
    
    /**
     * used in refugee_app
     * return messages with message_id > last_message_received and case_id
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reloadSpotter(Request $request){
        $all = $request->all();
        
        $responseData = null;
        foreach($all['cases'] AS $case_id=>$case_data){
            $caseMessages = $this->getMessagesFromDB($case_id, $case_data['last_message_received']);
            if(count($caseMessages)>0)
                $responseData[$case_id]['messages'] = $caseMessages;
        }
        
        return $responseData;
        
        $emergency_case_id = $all['emergency_case_id'];
        
        if(isset($all['geo_data'])&&$all['geo_data']!='undefined'){
            $geo_data = json_decode($all['geo_data'], true);

            $geo_data['heading'] = 0;

            addLocation($emergency_case_id, $geo_data);
        }
        
        
        
        $emergencyCaseMessages = $this->getMessagesFromDB($emergency_case_id, $all['last_message_received']);

        
        $result = [];
        $result['error'] = null;
        $result['data']['messages'] = $emergencyCaseMessages;
        return $result;
    }
    
    /**
     * get cases for operation area
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function casesInOperationArea($id){
        
        
        //@add auth
        
        $operation_area = Operation_area::where('id', $id)->get();
	$emergency_cases = emergencyCase::where('operation_area', $id)->get();
        
        foreach($emergency_cases AS $index=>$emergency_case){
            $emergency_cases[$index]['locations'] = emergencyCaseLocation::where('emergency_case_id', $emergency_case['id'])->get();
        }
        
        $result['polygon_coordinates'] = $operation_area[0]['polygon_coordinates'];
        $result['emergency_cases'] = $emergency_cases;
        return $result;
    }
    
    /**
     * add location to emergency_case
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function ping(Request $request){
        
        $all = $request->all();
        
        $geo_data = json_decode($all['geo_data'], true);
        
        $geo_data['heading'] = 0;
        
        echo addLocation($emergency_case_id, $geo_data);
    }
    
    /**
     * inserts message and new location into database
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendMessage(Request $request){
        
        $all = $request->all();
        
        if(isset($all['geo_data'])&&$all['geo_data']!='undefined'){
            
            $geo_data = json_decode($all['geo_data'], true);

            $geo_data['heading'] = 0;
            
            $all['emergency_case_location_id'] = addLocation($all['emergency_case_id'], $geo_data);
            
        }
        $emergencyCaseMessage = new emergencyCaseMessage($all);
        $emergencyCaseMessage->save();
        
        $result = [];
        $result['error'] = null;
        $result['data']['emergency_case_message_id'] = $emergencyCaseMessage->id;
        
        return json_encode($result);
    }
    
    /**
     * inserts message into database
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendMessageCrew(Request $request){
        
        
        $all = $request->all();
        $all['emergency_case_id'] = $all['case_id'];
        $all['message'] = $all['message'];
        
        //(app_spotter use jwtauth)
        if(isset($all['sender_type']) && $all['sender_type'] == 'spotter'){
            $user = $this->checkApiAuth(); //throws 500 if token is invalid
            $all['sender_id'] = $user->id;
        }else{
            $all['sender_type'] = 'user';
            $all['sender_id'] = Auth::User()->id;
        }
        
        $emergencyCaseMessage = new emergencyCaseMessage($all);
        $emergencyCaseMessage->save();
        
        $result = [];
        $result['error'] = null;
        $result['data']['emergency_case_message_id'] = $emergencyCaseMessage->id;
        $result['data']['message_data'] = $emergencyCaseMessage;
        
        return json_encode($result);
    }
    
    //could be added to model emergency_cases?
    public function getMessagesFromDB($case_id, $last_message_received){
        
        return emergencyCaseMessage::where('id', '>', (int)$last_message_received)->where('emergency_case_id', '=', (int)$case_id)->get();
        
    }
    
    /**
     * returns all message for case_id where message_id > last_recieved_message
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getMessages(Request $request){
        $all = $request->all();
        $case_id = $all['case_id'];
        $last_recieved_message = $all['last_recieved_message'];
    }
    
    
    /**
     * inserts message into database
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkForOpenCase(Request $request){
        $all = $request->all();
        
        $caseData =  emergencyCase::where('session_token', '=', $all['session_token'])
                ->where('boat_status', '=', 'distress')
                ->first();
        $case_id = $caseData['id'];
        $operation_area = $caseData['operation_area'];
        
        
        
        $result = [];
        $result['error'] = null;
        $result['data'] = [];
        $result['data']['operation_area'] = $operation_area;
        $result['data']['emergency_case_id'] = $case_id;
        $result['data']['messages'] = $this->getMessagesFromDB($case_id, 0);
        
        echo json_encode($result);
        
    }
    
    public function getSpotterCases(Request $request){
        $all = $request->all();
        
        $caseData =  emergencyCase::where('session_token', '=', $all['session_token'])
                ->where('boat_status', '=', 'distress');
        
        $caseData =  emergencyCase::get();
        
        
        
        $result = [];
        $result['error'] = null;
        $result['data'] = [];
        $result['data']['operation_area'] = 1;
        $result['data']['emergency_cases'] = $caseData;
        //$result['data']['messages'] = $this->getMessagesFromDB($case_id, 0);
        
        echo json_encode($result);
        
    }
    
    /**
     * Adds a new emergency_case and the first position into the database
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add_request(Request $request)
    {
        
        $all = $request->all();
        
        $location_information = json_decode($all['location_data']);
        
        $location_information->heading = 0;
        
        if(isset($location_information->longitude))
            $all['operation_area'] =  $this->getLocationArea($location_information->longitude,$location_information->latitude);
        
        if(!isset($all['boat_status']))
            $all['boat_status'] = 'distress';
        
        if(!isset($all['boat_condition']))
            $all['boat_condition'] = 'unknown';
        
        //no operation area;
        if(!$all['operation_area']){
            $result = [];
            $result['error'] = 'no_operation_area';


            return json_encode($result);
        }
        
        $emergencyCase = new emergencyCase($all);
        $emergencyCase->save();
        $emergencyCasePositions = [
            new emergencyCaseLocation(['lon'=>(float)$location_information->longitude,
                'lat'=>(float)$location_information->latitude,
                'accuracy'=>$location_information->accuracy,
                'heading'=>$location_information->heading])
        ];

        $emergencyCase->emergency_case_locations()->saveMany($emergencyCasePositions);

        
        
        $result = [];
        $result['error'] = null;
        $result['data']['emergency_case_id'] = $emergencyCase->id;
        $result['data']['operation_area'] = $emergencyCase->operation_area;
        
        return json_encode($result);
        //
    }
    
    public function updateCase($case_id,Request $request){
        
        //check auth
        if($this->checkApiAuth()){
            $emergencyCase = emergencyCase::find($case_id);
            $emergencyCase->update($request->all());
        }
        
        
    }
    //creates new emergencyCaseLocation for case
    public function updateCaseLocation($case_id,Request $request){
        
        //check auth
        if($this->checkApiAuth()){
            
            $all = $request->all();


            //trigonomentry function needs to be added here and overwrite $all['position']['latitude'] and ['longitude']
            $all['spotting_distance'];
            $all['spotting_direction'];


            if(isset($all['position'])&&$all['position']!='undefined'){
                $emergencyCase = emergencyCase::find($case_id);
                $emergencyCase->update($request->all());
                
                
                //addMessage or addMesage in addLocation?
                addLocation($case_id, $all['position']);
                
                $response['data'] = 'Location updated: lat:'.$all['position']['latitude'].' lon: '.$all['position']['longitude'];
                $response['error'] = null;
                return $response;
            }else{
                $response['error'] = 'no valid position data';
            }

            
        }
        
        
    }
    
    public function closeCase(Request $request){
        
        $all = $request->all();
        $emergencyCase = emergencyCase::find($all['case_id']);
        if($all['reason'] === 'accidentally'||$all['reason'] === 'solved_by_client'){
            $emergencyCase->delete();
        }else{
            echo $emergencyCase->update(['additional_information'=>'closed_by_client because of '.$all['reason'],'boat_status'=>$all['reason']]);
        }
        
    }
    
    
    
    
    
    
    
    /**
     * adds user to involved_users and returns complete dialogue
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getInvolved(Request $request)
    {
        $all = $request->all();
        
        $case_id = $all['case_id'];
        
        if(!isset($all['no_involvement'])){
            //check if there is alrady a table
            $checkDB = involvedUsers::where('case_id', '=', $case_id)->where('user_id', '=', Auth::id())->count();

            if($checkDB === 0){
                $involvedUser = new involvedUsers($all);
                $involvedUser->user_id = Auth::id();
                $involvedUser->save();
            }
        }
        
        $result = [];
        $result['error'] = null;
        $result['data'] = [];
        $result['data']['messages'] = $this->getMessagesFromDB($all['case_id'], 0);
        
        return $result;
        
    }

    
    /**
     * adds user to involved_users and returns complete dialogue
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reloadBackend(Request $request)
    {
        
        $all = $request->all();
        //load all new cases since $timestamp
        
        //
        if(isset($all['request'])){
            $request  = $all['request'];
            
            
            

            $result = [];
            
            $date = date('Y-m-d H:i:s', $request['last_updated']);
            //add new cases to result
            $result['data']['cases'] = emergencyCase::where('updated_at', '>', $date)->get();
            
            
            
            if(isset($request['cases'])){
                //add new messages to result
                $result['data']['messages'] = [];
                foreach($request['cases'] AS $caseData){

                    $user = involvedUsers::where('case_id', '=', $caseData['id'])->where('user_id', '=', Auth::id());
                    $user->update(array('last_message_seen'=>$caseData['last_message_received']));

                    $result['data']['messages'][$caseData['id']] = $this->getMessagesFromDB($caseData['id'], $caseData['last_message_received']);
                }
            }
            return $result;
        }else{
            return 'null';
        }
        
    }
    
    public function loadCaseBox(Request $request){
        
        $all = $request->all();
        
        $emergency_case = emergencyCase::find($all['request']['case_id']);
        
	return view('partials.case_box', compact('emergency_case'));
        
    }
    
    public function getVehicles(Request $request){
        
	$vehicles = Vehicle::where('public', '=', 'true')->get();
        
        
        
        
        $result['vessels'] = $vehicles->toArray();
        return $result;
    }
    
    //returns fakse or User obj
    private function checkApiAuth($type=null){
        switch($type){
            default: 
                return JWTAuth::parseToken()->toUser();
                break;
        }
        return false;
    }

    //AuthController
    public function token(){
        $token = JWTAuth::getToken();
        if(!$token){
            throw new BadRequestHtttpException('Token not provided');
        }
        try{
            $token = JWTAuth::refresh($token);
        }catch(TokenInvalidException $e){
            throw new AccessDeniedHttpException('The token is invalid');
        }
        $response = array('token'=>$token);
        return $response;
    }
    
    public function updateVehiclePosition(Request $request){
        
	
        $all = $request->all();
        $Vehicle = Vehicle::where('user_id', '=', JWTAuth::parseToken()->toUser()->id)->first();
        $vehicleLocation = new \App\VehicleLocation(array('lat'=>$all['position']["latitude"], 'lon'=>$all['position']['longitude'], 'vehicle_id'=>$Vehicle->id, 'timestamp'=>time(),'connection_type'=>'spotter_app'));
            $vehicleLocation->save();
            echo $vehicleLocation->id;
        //$result['vessels'] = $vehicles->toArray();
        //return $result;
        return $Vehicle;
    }

    
}
