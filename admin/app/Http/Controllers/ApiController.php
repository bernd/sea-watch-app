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


use Carbon\Carbon;



class pointLocation {
    var $pointOnVertex = true; // Check if the point sits exactly on one of the vertices?
 
    function pointLocation() {
    }
 
    function pointInPolygon($point, $polygon, $pointOnVertex = true) {
        $this->pointOnVertex = $pointOnVertex;
 
        // Transform string coordinates into arrays with x and y values
        $point = $this->pointStringToCoordinates($point);
        $vertices = array(); 
        foreach ($polygon as $vertex) {
            $vertices[] = $this->pointStringToCoordinates($vertex); 
        }
 
        // Check if the point sits exactly on a vertex
        if ($this->pointOnVertex == true and $this->pointOnVertex($point, $vertices) == true) {
            return "vertex";
        }
 
        // Check if the point is inside the polygon or on the boundary
        $intersections = 0; 
        $vertices_count = count($vertices);
 
        for ($i=1; $i < $vertices_count; $i++) {
            $vertex1 = $vertices[$i-1]; 
            $vertex2 = $vertices[$i];
            if ($vertex1['y'] == $vertex2['y'] and $vertex1['y'] == $point['y'] and $point['x'] > min($vertex1['x'], $vertex2['x']) and $point['x'] < max($vertex1['x'], $vertex2['x'])) { // Check if point is on an horizontal polygon boundary
                return "boundary";
            }
            if ($point['y'] > min($vertex1['y'], $vertex2['y']) and $point['y'] <= max($vertex1['y'], $vertex2['y']) and $point['x'] <= max($vertex1['x'], $vertex2['x']) and $vertex1['y'] != $vertex2['y']) { 
                $xinters = ($point['y'] - $vertex1['y']) * ($vertex2['x'] - $vertex1['x']) / ($vertex2['y'] - $vertex1['y']) + $vertex1['x']; 
                if ($xinters == $point['x']) { // Check if point is on the polygon boundary (other than horizontal)
                    return "boundary";
                }
                if ($vertex1['x'] == $vertex2['x'] || $point['x'] <= $xinters) {
                    $intersections++; 
                }
            } 
        } 
        // If the number of edges we passed through is odd, then it's in the polygon. 
        if ($intersections % 2 != 0) {
            return "inside";
        } else {
            return "outside";
        }
    }
 
    function pointOnVertex($point, $vertices) {
        foreach($vertices as $vertex) {
            if ($point == $vertex) {
                return true;
            }
        }
 
    }
 
    function pointStringToCoordinates($pointString) {
        $coordinates = explode(" ", $pointString);
        return array("x" => $coordinates[0], "y" => $coordinates[1]);
    }
 
}




function addLocation($emergency_case_id, $geo_data){
    
        $emergencyCaseLocation = new emergencyCaseLocation(['lon'=>(float)$geo_data['longitude'],
                'lat'=>(float)$geo_data['latitude'],
                'accuracy'=>$geo_data['accuracy'],
                'heading'=>$geo_data['heading']]);
        $emergencyCaseLocation->emergency_case_id = $emergency_case_id;
        $emergencyCaseLocation->save();
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
        //require('../../pointLocation.php');
        
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
        
        $geo_data = json_decode($all['geo_data'], true);
        
        $geo_data['heading'] = 0;
        
        
        $emergency_case_id = $all['emergency_case_id'];
        
        addLocation($emergency_case_id, $geo_data);
        
        
        
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
     * inserts message into database
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendMessage(Request $request){
        
        $all = $request->all();
        
        $geo_data = json_decode($all['geo_data'], true);
        
        $geo_data['heading'] = 0;
        
        
        $location_id = addLocation($all['emergency_case_id'], $geo_data);
        
        $all['emergency_case_location_id'] = $location_id;
        
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
        $all['sender_type'] = 'user';
        $all['sender_id'] = Auth::User()->id;
        
        $emergencyCaseMessage = new emergencyCaseMessage($all);
        $emergencyCaseMessage->save();
        
        $result = [];
        $result['error'] = null;
        $result['data']['emergency_case_message_id'] = $emergencyCaseMessage->id;
        
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
        
        
        return json_encode($result);
        //
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
        
        //check if there is alrady a table
        $checkDB = involvedUsers::where('case_id', '=', $case_id)->where('user_id', '=', Auth::id())->count();
        
        if($checkDB === 0){
            $involvedUser = new involvedUsers($all);
            $involvedUser->user_id = Auth::id();
            $involvedUser->save();
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
        if(isset($all['request'])){
            $request  = $all['request'];

            $result = [];
            $result['data']['messages'] = [];
            foreach($request['cases'] AS $caseData){
                
                $user = involvedUsers::where('case_id', '=', $caseData['id'])->where('user_id', '=', Auth::id());
                $user->update(array('last_message_seen'=>$caseData['last_message_received']));
        
                $result['data']['messages'][$caseData['id']] = $this->getMessagesFromDB($caseData['id'], $caseData['last_message_received']);
            }
            return $result;
        }else{
            return 'null';
        }
        
    }

    
    
}
