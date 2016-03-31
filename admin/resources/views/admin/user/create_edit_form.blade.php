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
        
        
        
        <div class="form-group  key {{ $errors->has('password') ? 'has-error' : '' }}">
            {!! Form::label('password', trans("admin/users.password"), array('class' => 'control-label')) !!}
            <div class="controls">
                <a href="#" class="btn btn-success btn-sm new-key"><span class="glyphicon glyphicon-refresh"></span>  Genarete New</a>
                {!! Form::text('password', null, array('class' => 'form-control key')) !!}
                <span class="help-block">{{ $errors->first('password', ':message') }}</span>
            </div>
        </div>
        <div class="form-group  key  {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
            {!! Form::label('password_confirmation', trans("admin/users.password_confirmation"), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('password_confirmation', null, array('class' => 'form-control key')) !!}
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
                    $('.key').val(Password.generate(16));
                });
                
                $('#type').change(function(e){
                    e.preventDefault();
                    if($(this).val() == 'app'){
                        $('.key').show();
                    }else{
                        $('.key').hide();
                    };
                });
            });
        </script>