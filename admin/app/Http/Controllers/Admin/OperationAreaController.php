<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use App\Operation_area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Datatables;
use DB;


class OperationAreaController extends AdminController
{


    public function __construct()
    {
        view()->share('type', 'operation_area');
    }

    /*
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function index()
    {
        // Show the page
        return view('admin.operationAreas.index');
    }
    public function create(){
        $this->middleware('auth');
        $operation_areas = Operation_area::select()->get();
        return view('admin.operationAreas.create',compact('operation_areas'));
    }
    public function store(Request $request)
    {
            $this->middleware('auth');
	    $location = new Operation_area($request->all());
            $location -> user_id = Auth::id();
            $location -> save();

        return 'everything stored!';
    }
    public function data()
    {
        
        
        //weird workaround because datable was broken
        $operationAreas = Operation_area::all(); 
        
        return Datatables::of($operationAreas)
            //->edit_column('confirmed', '@if ($confirmed=="1") <span class="glyphicon glyphicon-ok"></span> @else <span class=\'glyphicon glyphicon-remove\'></span> @endif')

            ->remove_column('polygon_coordinates')
            ->remove_column('active')
            ->remove_column('user_id')
            ->remove_column('created_at')
            ->remove_column('updated_at')
            ->add_column('actions', '<!--<a href="{{{ URL::to(\'admin/operationAreas/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm iframe" ><span class="glyphicon glyphicon-pencil"></span>  {{ trans("admin/modal.edit") }}</a>-->
                    <a href="{{{ URL::to(\'admin/operationAreas/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger iframe"><span class="glyphicon glyphicon-trash"></span> {{ trans("admin/modal.delete") }}</a>
                ')
            ->make();
    }
    public function delete(Operation_area $operationArea)
    {
        return view('admin.operationAreas.delete', compact('operationArea'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $user
     * @return Response
     */
    public function destroy(Operation_area $operationArea)
    {
        $operationArea->delete();
    }
}
