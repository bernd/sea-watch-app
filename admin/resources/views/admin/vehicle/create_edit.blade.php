@extends('admin.layouts.modal')
{{-- Content --}}
@section('content')
        <!-- Tabs -->
        
<ul class="nav nav-tabs">
    <li class="active"><a href="#tab-general" data-toggle="tab"> {{
			trans("admin/modal.general") }}</a></li>
</ul>
<!-- ./ tabs -->

@if (isset($vehicle))
{!! Form::model($vehicle, array('url' => URL::to('admin/vehicle') . '/' . $vehicle->id, 'method' => 'put', 'class' => 'bf', 'files'=> true)) !!}
@else
{!! Form::open(array('url' => URL::to('admin/vehicle'), 'method' => 'post', 'class' => 'bf', 'files'=> true)) !!}
@endif
        <!-- Tabs Content -->
<div class="tab-content">
    <!-- General tab -->
    <div class="tab-pane active" id="tab-general">
        {!! Form::hidden('user_id', null) !!}
        @include('admin.user.create_edit_form')
        
        <div class="form-group  {{ $errors->has('title') ? 'has-error' : '' }}">
            {!! Form::label('title', 'Vehicle Title', array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('title', null, array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('title', ':message') }}</span>
            </div>
        </div>
        <div class="form-group  {{ $errors->has('type') ? 'has-error' : '' }}">
            {!! Form::label('type', 'type', array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::select('type', [
                    'app' => 'App',
                    'iridium_mail_gateway' => 'Iridium Phone']
                 ) !!}
                <span class="help-block">{{ $errors->first('type', ':message') }}</span>
            </div>
        </div>
        <div class="form-group  {{ $errors->has('sat_number') ? 'has-error' : '' }}">
            {!! Form::label('name', 'Identifier', array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('sat_number', null, array('class' => 'form-control')) !!}
                <span class="help-block">
                    Iridium-Go: Mobilenumber (e.g. "881623439957")<br>
                    App: Identifier (e.g. "landrover_1")<br>
                </span>
                <span class="help-block">{{ $errors->first('sat_number', ':message') }}</span>
            </div>
        </div>
        <div class="form-group  {{ $errors->has('key') ? 'has-error' : '' }}">
            {!! Form::label('name', 'Marker Color', array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('marker_color', null, array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('marker_color', ':message') }}</span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <button type="reset" class="btn btn-sm btn-warning close_popup">
                <span class="glyphicon glyphicon-ban-circle"></span> {{
				trans("admin/modal.cancel") }}
            </button>
            <button type="reset" class="btn btn-sm btn-default">
                <span class="glyphicon glyphicon-remove-circle"></span> {{
				trans("admin/modal.reset") }}
            </button>
            <button type="submit" class="btn btn-sm btn-success">
                <span class="glyphicon glyphicon-ok-circle"></span>
                @if	(isset($vehicle))
                    {{ trans("admin/modal.edit") }}
                @else
                    {{trans("admin/modal.create") }}
                @endif
            </button>
        </div>
    </div>
    {!! Form::close() !!}
    @stop @section('scripts')
        
</div>
@stop
