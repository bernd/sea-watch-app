<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\emergencyCase;

class EmergencyCaseController extends Controller
{

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
            $emergencyCase = emergencyCase::find($id);
            echo $emergencyCase->update($request->all());
            $emergencyCase->touch();
            if (!$emergencyCase->update($request->all())) {
                return redirect()->back()
                        ->with('message', 'Something wrong happened while saving your model')
                        ->withInput();
            }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
