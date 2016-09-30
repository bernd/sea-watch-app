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

      <div class="row ">
        
        
        @include('partials.casenav')
        <div class="col-sm-7 col-md-7 main">
            <ul id="caseList">

            @foreach ($emergency_cases as $emergency_case)
                
                @include('partials.case_box')
                
            @endforeach
                
            </ul>
          
          
          
          
        </div>

        <style>
        .chat {
          background:rgba(255,255,255,0.5);
          border-left:1px solid rgba(0,0,0,0.25);
          bottom:0px;
          padding:64px 15px 0px 15px;
          box-sizing:border-box;
          height:100vh;
          top:0px;
          position:fixed;
          }

          .message-container {
            width:100%;
            display:block;
          }



          .message-meta {

          }

            .message-meta_user {

            }




        </style>
        <div class="col-sm-3 col-md-3 col-sm-offset-9 col-md-offset-9 chat">
          <h3>Chat · Status-Log</h3>

        </div>
      </div>


@stop
