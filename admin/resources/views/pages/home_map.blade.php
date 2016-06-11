@extends('layouts.app')

@section('content')


      <div class="row">
        
        
        @include('partials.casenav')
        <div class="col-sm-10 col-md-10 col-sm-offset-2 col-md-offset-2 main maps_display_mode" id="maps">
          
          
          

        </div>
        <div id="caseDetailContainer" style="position:fixed;top: 49px;right:30px;width: 315px;overflow: auto;height: 350px;">asd</div>
      </div>
          
<script src="./js/jquery.min.js" type="text/javascript"></script>
<script src='https://api.mapbox.com/mapbox.js/v2.2.4/mapbox.js'></script>
<link href='https://api.mapbox.com/mapbox.js/v2.2.4/mapbox.css' rel='stylesheet' />
<script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v0.4.0/leaflet.markercluster.js'></script>
<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v0.4.0/MarkerCluster.css' rel='stylesheet' />
<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v0.4.0/MarkerCluster.Default.css' rel='stylesheet' />


<script>
var emergency_cases_obj = <?php echo $emergency_cases->toJson();?>;
var vehicles_obj = <?php echo $vehicles->toJson();?>;
var operation_areas_obj = <?php echo $operation_areas->toJson();?>;
    
L.mapbox.accessToken = 'pk.eyJ1IjoibmljemVtIiwiYSI6ImNpam02MzNrNzAwMmt2eG0zdXI0ZHYzajAifQ.yi825X8J7cP1upzA1x6Y-Q';


$(document).ready(function(){
    swApp.initMap();
});
</script>
<script src="./js/cases.js"></script>


@stop
