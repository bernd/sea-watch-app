<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Input;
use App\Language;
use App\Http\Requests\Admin\LanguageRequest;
use App\Http\Requests\Admin\DeleteRequest;
use App\Http\Requests\Admin\ReorderRequest;
use Illuminate\Support\Facades\Auth;
use Datatables;

class CaseController extends AdminController {

    public function __construct()
    {
        view()->share('type', 'case');
    }
    /**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
            // Show the page
            return view('admin.case.index');
            
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
       // Show the page
        return view('admin/case/create_edit');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(LanguageRequest $request)
	{
        $language = new Language($request->all());
        $language -> user_id = Auth::id();
        $language -> save();
	}
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Language $language)
	{
        return view('admin/case/create_edit',compact('case'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(LanguageRequest $request, Language $language)
	{
        $language -> user_id_edited = Auth::id();
        $language -> update($request->all());
	}

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */

    public function delete(Language $language)
    {
        // Show the page
        return view('admin/language/delete', compact('language'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function destroy(Language $language)
    {
        $language->delete();
    }

    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {
        
        
        //weird workaround because datable was broken
        $emergency_cases = \App\emergencyCase::all(array('created_at', 'boat_status', 'boat_condition', 'boat_type', 'passenger_count', 'operation_area'));
        
        return Datatables::of($emergency_cases)
            //->edit_column('confirmed', '@if ($confirmed=="1") <span class="glyphicon glyphicon-ok"></span> @else <span class=\'glyphicon glyphicon-remove\'></span> @endif')

            ->remove_column('id')
            ->remove_column('operation_area')
            ->remove_column('locations')
            ->add_column('operation_area', '$operation_area_title')
//            ->remove_column('updated_at')
//            ->remove_column('deleted_at')
//            ->remove_column('confirmation_code')
//            ->remove_column('admin')
//            ->remove_column('username')
//            ->remove_column('mobile_number')
//            ->remove_column('organisation')
//            ->remove_column('operation_areas')
//            ->remove_column('remember_token')
//            ->add_column('actions', '@if ($id!="1")<a href="{{{ URL::to(\'admin/user/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm iframe" ><span class="glyphicon glyphicon-pencil"></span>  {{ trans("admin/modal.edit") }}</a>
//                    <a href="{{{ URL::to(\'admin/user/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger iframe"><span class="glyphicon glyphicon-trash"></span> {{ trans("admin/modal.delete") }}</a>
//                @endif')
            ->make();
    }

}
