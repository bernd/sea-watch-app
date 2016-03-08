@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title') Vehicle :: @parent
@stop

{{-- Content --}}
@section('main')
    <div class="page-header">
        
        <h3>
            Vehicles
            <div class="pull-right">
                <div class="pull-right">
                    <a href="{!! URL::to('admin/vehicle/create') !!}"
                       class="btn btn-sm  btn-primary iframe"><span
                                class="glyphicon glyphicon-plus-sign"></span> Add Vehicle</a>
                </div>
            </div>
        </h3>
    </div>

    <table id="table" class="table table-striped table-hover">
        <thead>
        <tr>
            <th>{!! trans("admin/vehicles.id") !!}</th>
            <th>{!! trans("admin/vehicles.title") !!}</th>
            <th>{!! trans("admin/vehicles.type") !!}</th>
            <th>{!! trans("admin/vehicles.sat_number") !!}</th>
            <th>{!! trans("admin/admin.action") !!}</th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
@stop

{{-- Scripts --}}
@section('scripts')
@stop
