<?php

namespace App\Http\Controllers;

use App\Operation_area;
use App\emergencyCase;
use DB;

class HomeController extends Controller {

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
                $operation_areas = Operation_area::select()->get();
                
                $emergency_cases = emergencyCase::select()->get();
                
                
		return view('pages.home_cases', compact('operation_areas','emergency_cases'));
	}
	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function map()
	{
                $operation_areas = Operation_area::select()->get();
                
                $emergency_cases = emergencyCase::select()->get();
                
                
                
		return view('pages.home_map', compact('operation_areas','emergency_cases'));
	}

}