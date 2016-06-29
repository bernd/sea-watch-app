<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Operation_area;

class operation_areas extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    public function create(){
        $this->middleware('auth');
        $operation_areas = Operation_area::select()->get();
        return view('operation_areas.create',compact('operation_areas'));
    }

    /**
     * Create a new task.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
            $this->middleware('auth');
	    $location = new Operation_area($request->all());
            $location -> user_id = Auth::id();
            $location -> save();

        return 'everything stored!';
    }
}
