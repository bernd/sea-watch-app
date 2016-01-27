@extends('layouts.app')
{{-- Web site Title --}}
@section('title')
        @parent
@stop

@section('content')



</div>
<div class="container">
<div class="row-fluid">
    <form class="form-horizontal"  style="max-width:650px;" action='' method="POST">
        <?php
        
        $case->other_involved = $case->other_involved === 'true'? true: false;
        $case->engine_working = $case->engine_working === 'true'? true: false
        ?>
        
       {{--['boat_status','boat_condition','boat_type','other_involved','engine_working','passenger_count','additional_informations','spotting_distance','spotting_direction','picture','session_token','created_at','updated_at','operation_area]--}}
        
        
      <div class="form-group">
        <label class="col-sm-3 control-label" for="title">Title</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" name="title" id="title" placeholder="title">
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label" for="boat_status">Boat Status</label>
        <div class="col-sm-9">
            {!! Form::select('boat_status', [
                'distress'=>'distress',
                'rescue in progress'=>'rescue in progress',
                'rescued'=>'rescued',
                'on_land'=>'on land']) !!}
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label" for="boat_condition">Boat Condition</label>
        <div class="col-sm-9">
            {!! Form::select('boat_condition', [
                'good'=>'good',
                'bad'=>'bad',
                'sinking'=>'sinking',
                'people_in_water'=>'people in water']) !!}
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label" for="boat_type">Boat Type</label>
        <div class="col-sm-9">
            {!! Form::select('boat_type', [
                'rubber'=>'rubber',
                'wood'=>'wood',
                'steel'=>'steel',
                'other'=>'other']) !!}
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label" for="other_involved">Other Involved</label>
        <div class="col-sm-9">
            {!! Form::checkbox('other_involved', 'true', $case->other_involved) !!}
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label" for="engine_working">engine working</label>
        <div class="col-sm-9">
            {!! Form::checkbox('engine_working', 'true', $case->engine_working) !!}
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label" for="passenger_count">passenger count</label>
        <div class="col-sm-9">
            {!! Form::number('passenger_count') !!}
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label" for="additional_informations">additional_informations</label>
        <div class="col-sm-9">
            {!! Form::text('additional_informations') !!}
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label" for="spotting_distance">spotting_distance</label>
        <div class="col-sm-9">
            {!! Form::number('spotting_distance') !!}
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label" for="spotting_direction">spotting direction</label>
        <div class="col-sm-9">
            {!! Form::number('spotting_direction') !!}
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label" for="session_token">session_token</label>
        <div class="col-sm-9">
            {!! Form::text('session_token') !!}
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label"></label>
        <div class="col-sm-9">
            <input type="submit" value="Update Case">
        </div>
      </div>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
</div>

</div>

<script>
</script>



@stop
