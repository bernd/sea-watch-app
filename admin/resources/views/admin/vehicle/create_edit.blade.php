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
        <div class="form-group  {{ $errors->has('key') ? 'has-error' : '' }}" style="@if ($vehicle->type != 'app') display:none @endif" id="appKey">
            {!! Form::label('name', 'password/key', array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('key', null, array('class' => 'form-control')) !!}
                <a href="#" class="btn btn-success btn-sm new-key"><span class="glyphicon glyphicon-refresh"></span>  Genarete New</a>
                <span class="help-block">
                    Required for App authentification
                </span>
                <span class="help-block">{{ $errors->first('key', ':message') }}</span>
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
        <script type="text/javascript">
            
            
            //thx to saaj on http://stackoverflow.com/questions/9719570/generate-random-password-string-with-requirements-in-javascript
            var Password = {
 
                _pattern : /[a-zA-Z0-9_\-\+\.]/,


                _getRandomByte : function()
                {
                  // http://caniuse.com/#feat=getrandomvalues
                  if(window.crypto && window.crypto.getRandomValues) 
                  {
                    var result = new Uint8Array(1);
                    window.crypto.getRandomValues(result);
                    return result[0];
                  }
                  else if(window.msCrypto && window.msCrypto.getRandomValues) 
                  {
                    var result = new Uint8Array(1);
                    window.msCrypto.getRandomValues(result);
                    return result[0];
                  }
                  else
                  {
                    return Math.floor(Math.random() * 256);
                  }
                },

                generate : function(length)
                {
                  return Array.apply(null, {'length': length})
                    .map(function()
                    {
                      var result;
                      while(true) 
                      {
                        result = String.fromCharCode(this._getRandomByte());
                        if(this._pattern.test(result))
                        {
                          return result;
                        }
                      }        
                    }, this)
                    .join('');  
                }    

              };
            
            
            
            $(function () {
                $("#roles").select2();
                
                
                
                $('.new-key').click(function(e){
                    e.preventDefault();
                    $('input[name="key"]').val(Password.generate(16));
                });
                
                $('#type').change(function(e){
                    e.preventDefault();
                    if($(this).val() == 'app'){
                        $('#appKey').show();
                    }else{
                        $('#appKey').hide();
                    };
                });
            });
        </script>
</div>
@stop
