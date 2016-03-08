<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use App\User;
use App\Operation_area;

class DashboardController extends AdminController {

    public function __construct()
    {
        parent::__construct();
        view()->share('type', '');
    }

	public function index()
	{
            $title = "Dashboard";

            $users = User::count();
            $operation_areas = Operation_area::select()->get();
            return view('admin.dashboard.index',  compact('title','users','operation_areas'));
	}
}