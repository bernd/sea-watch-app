@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title') {!! trans("admin/article.article") !!} :: @parent @stop

{{-- Content --}}
@section('main')
    <div class="page-header">
        <h3>
            Cases
            
            <div class="pull-right">
                <div class="pull-right">
<!--                    <a href="{!! URL::to('admin/article/create') !!}"
                       class="btn btn-sm  btn-primary iframe"><span
                                class="glyphicon glyphicon-plus-sign"></span> {{
					trans("admin/modal.new") }}</a>-->
                </div>
            </div>
        </h3>
    </div>

    <table id="table" class="table table-striped table-hover">
        <thead>
        <tr>
            <th>Created At</th>
            <th>Status</th>
            <th>Condition</th>
            <th>Type</th>
            <th>Count</th>
            <th>Operation Area</th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
@stop

{{-- Scripts --}}
@section('scripts')
@stop
