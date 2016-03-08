@extends('layouts.app')

@section('content')
 
<script src="./js/jquery.min.js" type="text/javascript"></script>
<script src='https://api.mapbox.com/mapbox.js/v2.2.4/mapbox.js'></script>
<link href='https://api.mapbox.com/mapbox.js/v2.2.4/mapbox.css' rel='stylesheet' />
<script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v0.4.0/leaflet.markercluster.js'></script>
<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v0.4.0/MarkerCluster.css' rel='stylesheet' />
<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v0.4.0/MarkerCluster.Default.css' rel='stylesheet' />




          <script src="./js/cases.js" type="text/javascript"></script>
          <script>
var emergency_cases_obj = <?php echo $emergency_cases->toJson();?>;
    
var involved_cases = []; //is used to save involved chat_sessions to init upload

        
L.mapbox.accessToken = 'pk.eyJ1IjoibmljemVtIiwiYSI6ImNpam02MzNrNzAwMmt2eG0zdXI0ZHYzajAifQ.yi825X8J7cP1upzA1x6Y-Q';

              
$(document).ready(function(){
    swApp.initClicks();
    swApp.init();
});

</script>

<!--loggedOut-->
      <div class="row ">
        
        
        @include('partials.casenav')
        <div class="col-sm-10 col-md-10 main">
            <ul id="caseList">
            @foreach ($emergency_cases as $emergency_case)
                
                @include('partials.case_box')
                
            @endforeach
                
            </ul>
          
          
          
          
        </div>
      </div>


@stop
