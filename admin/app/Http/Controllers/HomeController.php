<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;


use App\Operation_area;
use App\emergencyCase;
use App\User;
use App\Vehicle;
use DB;

class HomeController extends Controller {

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
                $current_user = User::where('id', Auth::id())->get();
                        
                if(count($current_user) == 0){
                    $user_operation_areas = [];
                    foreach(Operation_area::select()->get() AS $op_area){
                        $user_operation_areas[] = $op_area->id;
                    }
                }else
                    $user_operation_areas = explode(',',$current_user[0]->operation_areas);
                
                $operation_areas = Operation_area::select()->whereIn('id',$user_operation_areas)->get();
                $vehicles = Vehicle::all();
                $emergency_cases = emergencyCase::select()->whereIn('operation_area',$user_operation_areas)->orderBy('created_at', 'desc')->get();
                
		return view('pages.home_cases', compact('operation_areas','emergency_cases','vehicles'));
	}
	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function vehicleGrid()
	{
            
                $current_user = User::where('id', Auth::id())->get();
                        
                if(count($current_user) == 0){
                    $user_operation_areas = [];
                    foreach(Operation_area::select()->get() AS $op_area){
                        $user_operation_areas[] = $op_area->id;
                    }
                }else
                    $user_operation_areas = explode(',',$current_user[0]->operation_areas);
                
                $operation_areas = Operation_area::select()->whereIn('id',$user_operation_areas)->get();
                $vehicles = Vehicle::select()->where('public', '=', true)->get();
                $emergency_cases = emergencyCase::select()->whereIn('operation_area',$user_operation_areas)->orderBy('created_at', 'desc')->get();
                
		return view('pages.home_vehicles', compact('operation_areas','emergency_cases','vehicles'));
	}
	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function adminGrid()
	{
                $current_user = User::where('id', Auth::id())->get();
                        
                if(count($current_user) == 0){
                    $user_operation_areas = [];
                    foreach(Operation_area::select()->get() AS $op_area){
                        $user_operation_areas[] = $op_area->id;
                    }
                }else
                    $user_operation_areas = explode(',',$current_user[0]->operation_areas);
                
                $operation_areas = Operation_area::select()->whereIn('id',$user_operation_areas)->get();
                $vehicles = Vehicle::select()->where('public', '=', true)->get();
                $emergency_cases = emergencyCase::select()->whereIn('operation_area',$user_operation_areas)->orderBy('created_at', 'desc')->get();
                
		return view('pages.home_cases', compact('operation_areas','emergency_cases','vehicles'));
	}
	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function map()
	{
                $current_user = User::where('id', Auth::id())->get();
                        
                if(count($current_user) == 0){
                    $user_operation_areas = [];
                    foreach(Operation_area::select()->get() AS $op_area){
                        $user_operation_areas[] = $op_area->id;
                    }
                }else
                    $user_operation_areas = explode(',',$current_user[0]->operation_areas);
                
                $operation_areas = Operation_area::select()->whereIn('id',$user_operation_areas)->get();
                $emergency_cases = emergencyCase::select()->whereIn('operation_area',$user_operation_areas)->orderBy('created_at', 'desc')->get();
                $vehicles = Vehicle::select()->where('public', '=', true)->get();
		return view('pages.home_map', compact('operation_areas','emergency_cases','vehicles'));
	}

}