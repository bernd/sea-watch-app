@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title') {!! trans("admin/users.users") !!} :: @parent
@stop

{{-- Content --}}
@section('main')
    <div class="page-header">
        <h3>
            Operation Areas
            <div class="pull-right">
                <div class="pull-right">
                    <a href="{!! URL::to('admin/operationAreas/create') !!}"
                       class="btn btn-sm  btn-primary iframe"><span
                                class="glyphicon glyphicon-plus-sign"></span> {{
					trans("admin/modal.new") }}</a>
                </div>
            </div>
        </h3>
    </div>

    <table id="table" class="table table-striped table-hover">
        <thead>
        <tr>
            <th>Id</th>
            <th>Title</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
@stop

{{-- Scripts --}}
@section('scripts')
@stop
