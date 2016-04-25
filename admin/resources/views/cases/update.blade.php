
    <form class="form-horizontal CaseBox_intern" action='' method="POST">
        <?php
        
        $case->other_involved = $case->other_involved === 'true'? true: false;
        $case->engine_working = $case->engine_working === 'true'? true: false
        ?>
        
       {{--['boat_status','boat_condition','boat_type','other_involved','engine_working','passenger_count','additional_informations','spotting_distance','spotting_direction','picture','session_token','created_at','updated_at','operation_area]--}}
        
      <div class="form-group">
        <label class="control-label" for="boat_status">Boat Status</label>
            {!! Form::select('boat_status', [
                'distress'=>'distress',
                'rescue_in_progress'=>'rescue in progress',
                'rescued'=>'rescued',
                'on_land'=>'on land'],
                $case->boat_status,
                ['class' => 'form-control input-sm']) !!}
      </div>
      <div class="form-group">
        <label class="control-label" for="boat_condition">Boat Condition</label>
            {!! Form::select('boat_condition', [
                'good'=>'good',
                'bad'=>'bad',
                'sinking'=>'sinking',
                'people_in_water'=>'people in water'],
                $case->boat_condition,
                ['class' => 'form-control input-sm']) !!}
      </div>
      <div class="form-group">
        <label class="control-label" for="boat_type">Boat Type</label>
            {!! Form::select('boat_type', [
                'rubber'=>'rubber',
                'wood'=>'wood',
                'steel'=>'steel',
                'other'=>'other'],
                $case->boat_type,
                ['class' => 'form-control input-sm']) !!}
      </div>
      <div class="row">
        <div class="col-md-6">
        <div class="form-group">
            <div class="checkbox">  
              <label>
              {!! Form::checkbox('other_involved', 'true', $case->other_involved) !!}
              Other Involved
            </label>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
            <div class="checkbox">
              <label>
              {!! Form::checkbox('engine_working', 'true', $case->engine_working) !!}
              Engine working
            </label>
          </div>
        </div>
      </div>
    </div>
      <div class="form-group">
        <label class="control-label" for="passenger_count">Number of passengers</label>
            {!! Form::number('passenger_count',  $case->passenger_count, array('class' => 'form-control input-sm')) !!}
      </div>
      <div class="form-group">
        <label class="control-label" for="additional_informations">Additional informations</label>
            {!! Form::text('additional_informations', $case->additional_informations, array('class' => 'form-control input-sm')) !!}
      </div>
      <div class="form-group">
        <label class="control-label" for="spotting_distance">Spotting Distance</label>
        <div class="input-group">
            {!! Form::number('spotting_distance',$case->spotting_distance, array('class' => 'form-control input-sm')) !!}
            <div class="input-group-addon">km</div>
        </div>
      </div>
      <div class="form-group">
        <label class="ontrol-label" for="spotting_direction">Spotting Direction</label>
         <div class="input-group">
            {!! Form::number('spotting_direction', $case->spotting_direction, array('class' => 'form-control input-sm')) !!}
            <div class="input-group-addon">&#176; <small>degree</small></div>
          </div>
      </div>
      <div class="form-group">
            <input type="submit" class="btn btn-block btn-primary" value="Update Case">
            <a class="btn btn-block btn-default closeEditCase">Cancel</a>
      </div>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>