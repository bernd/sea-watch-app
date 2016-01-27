@extends('admin.layouts.modal')
{{-- Content --}}
@section('content')
        <!-- Tabs -->
<ul class="nav nav-tabs">
    <li class="active"><a href="#tab-general" data-toggle="tab"> {{
			trans("admin/modal.general") }}</a></li>
</ul>
<!-- ./ tabs -->
@if (isset($user))
{!! Form::model($user, array('url' => URL::to('admin/user') . '/' . $user->id, 'method' => 'put', 'class' => 'bf', 'files'=> true)) !!}
@else
{!! Form::open(array('url' => URL::to('admin/user'), 'method' => 'post', 'class' => 'bf', 'files'=> true)) !!}
@endif
        <!-- Tabs Content -->
<div class="tab-content">
    <!-- General tab -->
    <div class="tab-pane active" id="tab-general">
        <div class="form-group  {{ $errors->has('name') ? 'has-error' : '' }}">
            {!! Form::label('name', trans("admin/users.name"), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('name', null, array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('name', ':message') }}</span>
            </div>
        </div>
        <div class="form-group  {{ $errors->has('username') ? 'has-error' : '' }}">
            {!! Form::label('username', trans("admin/users.username"), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('username', null, array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('username', ':message') }}</span>
            </div>
        </div>
        <div class="form-group  {{ $errors->has('email') ? 'has-error' : '' }}">
            {!! Form::label('email', trans("admin/users.email"), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('email', null, array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('email', ':message') }}</span>
            </div>
        </div>
        <div class="form-group  {{ $errors->has('mobile_number') ? 'has-error' : '' }}">
            {!! Form::label('mobile_number', 'Mobile Number', array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('mobile_number', null, array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('mobile_number', ':message') }}</span>
            </div>
        </div>
        <div class="form-group  {{ $errors->has('organisation') ? 'has-error' : '' }}">
            {!! Form::label('organisation', 'organisation', array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('organisation', null, array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('organisation', ':message') }}</span>
            </div>
        </div>
        
        
        
        <div class="form-group  {{ $errors->has('operation_areas') ? 'has-error' : '' }}">
            {!! Form::label('operation_areas', 'operation_areas', array('class' => 'control-label')) !!}
            <div class="controls">
                
                <?php
                $checked_areas = [];
                
                if(isset($user)){
                    $checked_areas = explode(',',$user->operation_areas);
                }
                
                foreach(App\Operation_area::get() AS $operation_area){
                    $is_checked = in_array($operation_area->id, $checked_areas) ? "checked" :  "";
                    echo '<input type="checkbox" value="'.$operation_area->id.'" class="op_select" '.$is_checked.'>';
                    echo $operation_area->title;
                }
                ?>
                
                
                {!! Form::text('operation_areas', null, array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('operation_areas', ':message') }}</span>
            </div>
        </div>
        <script>
        $('#operation_areas').hide();
        $('.op_select').click(function(){
            var results = [];
            $('.op_select:checked').each(function(){
                results.push($(this).val());
            });
            $('#operation_areas').val(results.join(','));
        });
        </script>
        
        
        
        <div class="form-group  {{ $errors->has('password') ? 'has-error' : '' }}">
            {!! Form::label('password', trans("admin/users.password"), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::password('password', array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('password', ':message') }}</span>
            </div>
        </div>
        <div class="form-group  {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
            {!! Form::label('password_confirmation', trans("admin/users.password_confirmation"), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::password('password_confirmation', array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('password_confirmation', ':message') }}</span>
            </div>
        </div>
        <div class="form-group  {{ $errors->has('confirmed') ? 'has-error' : '' }}">
            {!! Form::label('confirmed', trans("admin/users.active_user"), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::label('confirmed', trans("admin/users.yes"), array('class' => 'control-label')) !!}
                {!! Form::radio('confirmed', '1', @isset($user)? $user->confirmed : 'false') !!}
                {!! Form::label('confirmed', trans("admin/users.no"), array('class' => 'control-label')) !!}
                {!! Form::radio('confirmed', '0', @isset($user)? $user->confirmed : 'true') !!}
                <span class="help-block">{{ $errors->first('confirmed', ':message') }}</span>
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
                @if	(isset($user))
                    {{ trans("admin/modal.edit") }}
                @else
                    {{trans("admin/modal.create") }}
                @endif
            </button>
        </div>
    </div>
    {!! Form::close() !!}
    @stop @section('scripts')
        <script type="text/javascript">
            $(function () {
                $("#roles").select2()
            });
        </script>
</div>
@stop
