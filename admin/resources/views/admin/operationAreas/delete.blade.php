@extends('admin.layouts.modal')
@section('content')
	{!! Form::model($operationArea, array('url' => URL::to('admin/operationArea/delete/' . $operationArea->id) , 'method' => 'delete', 'class' => 'bf', 'files'=> true)) !!}
	<div class="form-group">
		<div class="controls">
			Are you sure to delete this operation area?<br>
			<element class="btn btn-warning btn-sm close_popup">
				<span class="glyphicon glyphicon-ban-circle"></span> {{
			trans("admin/modal.cancel") }}</element>
			<button type="submit" class="btn btn-sm btn-danger">
				<span class="glyphicon glyphicon-trash"></span> {{
				trans("admin/modal.delete") }}
			</button>
		</div>
	</div>
	{!! Form::close() !!}
@stop
