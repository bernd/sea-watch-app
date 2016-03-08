@extends('layouts.app')

{{-- Web site Title --}}
@section('title') {!!  trans('site/user.login') !!} :: @parent @stop

{{-- Content --}}
@section('content')
    

        <div class="row">
            <div class="login_window col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
                <div class="page-header">
                    <h2 class="entry-title">{!! trans('site/user.login_to_account') !!}</h2>
                </div>
            {!! Form::open(array('url' => URL::to('auth/login'), 'method' => 'post', 'files'=> true)) !!}
            <div class="form-group  {{ $errors->has('email') ? 'has-error' : '' }}">
                {!! Form::label('email', "E-Mail Address", array('class' => 'control-label')) !!}
                <div class="controls">
                    {!! Form::text('email', null, array('class' => 'form-control')) !!}
                    <span class="help-block">{{ $errors->first('email', ':message') }}</span>
                </div>
            </div>
            <div class="form-group  {{ $errors->has('password') ? 'has-error' : '' }}">
                {!! Form::label('password', "Password", array('class' => 'control-label')) !!}
                <div class="controls">
                    {!! Form::password('password', array('class' => 'form-control')) !!}
                    <span class="help-block">{{ $errors->first('password', ':message') }}</span>
                </div>
            </div>
            <div class="form-group">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="remember"> Remember Me
                        </label>
                    </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-block btn-primary" style="margin-right: 15px;">
                    Login
                </button>
            </div>
            {!! Form::close() !!}
            </div>
        </div>
        <div class="row">
            <div class="lost_password col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
                <a href="{{ URL::to('/password/email') }}" class="gray">Forgot Your Password?</a> 
            </div>
        </div>

       
@endsection
