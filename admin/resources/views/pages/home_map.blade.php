@extends('layouts.app')

@section('content')


      <div class="row">
        
        
        @include('partials.casenav')
        <div class="col-sm-10 col-md-10 main" style="position:absolute;top:62px;right:0;bottom:0;" id="maps">
          
          
          

        </div>
      </div>
          
          <script src="./js/jquery.min.js" type="text/javascript"></script>
<script src='https://api.mapbox.com/mapbox.js/v2.2.4/mapbox.js'></script>
<link href='https://api.mapbox.com/mapbox.js/v2.2.4/mapbox.css' rel='stylesheet' />
<script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v0.4.0/leaflet.markercluster.js'></script>
<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v0.4.0/MarkerCluster.css' rel='stylesheet' />
<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v0.4.0/MarkerCluster.Default.css' rel='stylesheet' />
        <script src="./js/cases.js"></script>


<script>
var emergency_cases_obj = <?php echo $emergency_cases->toJson();?>;
    
L.mapbox.accessToken = 'pk.eyJ1IjoibmljemVtIiwiYSI6ImNpam02MzNrNzAwMmt2eG0zdXI0ZHYzajAifQ.yi825X8J7cP1upzA1x6Y-Q';

var helpers = new function(){
    
    this.getMarkerColor = function(boat_status){
        return case_statuses[boat_status].color;
    }
    
    this.generateMarkerClusterFeatures = function(cases){
        
        var features = []
        
        $.each(cases, function(index, value){
            console.log(helpers.getMarkerColor(value.boat_status));
            features.push({
                    "type": "Feature",
                    
                    "properties": {
                      "marker-color": "#63b6e5"
                    },
                    "geometry": {
                        "coordinates": [parseFloat(value.locations[0].lon),parseFloat(value.locations[0].lat)],
                        "type": "Point"
                    }
                });
        });
        
        return {
            "type": "FeatureCollection",
            "features": features,
            "id": "markerCluster"
        }
    }
};

var swApp = new function(){
    
    this.startLocation = [36.19390,19.49248];
    this.mapContainerId = 'maps';
    this.map;
    this.initMap = function(){
        this.map = L.mapbox.map(this.mapContainerId, 'mapbox.streets')
        .setView(this.startLocation, 5);
        
        this.applyFilters();
        var self = this;
        
        $('.filter').click(function(){
            self.applyFilters();
        });
    };
    this.addMarkerToMap = function(location){
        
        L.mapbox.featureLayer({
            // this feature is in the GeoJSON format: see geojson.org
            // for the full specification
            type: 'Feature',
            geometry: {
                type: 'Point',
                // coordinates here are in longitude, latitude order because
                // x, y is the standard for GeoJSON and many formats
                coordinates: [
                  location[1],
                  location[0] 
                ]
            },
            properties: {
                title: 'Peregrine Espresso',
                description: '1718 14th St NW, Washington, DC',
                // one can customize markers by adding simplestyle properties
                // https://www.mapbox.com/guides/an-open-platform/#simplestyle
                'marker-size': 'large',
                'marker-color': '#BE9A6B',
                'marker-symbol': 'cafe'
            }
        }).on('click', function(e) {
            console.log(e.layer.feature.properties);
            //this.setGeoJSON(geoJson);?
        }).addTo(this.map);
        
    };
    this.getFilters = function(){
        var filters = {};
        filters.operation_areas = [];
        $('.op_area.active').each(function(){
            if(typeof $(this).attr('data-id') !== 'undefined')
                filters.operation_areas.push($(this).attr('data-id'));
        });

        filters.statuses = [];
        $('.status.active').each(function(){
            if(typeof $(this).attr('data-class') !== 'undefined')
                filters.statuses.push($(this).attr('data-class'));
        });

        filters.sources = [];
        $('.source.active').each(function(){
            if(typeof $(this).attr('data-class') !== 'undefined')
                filters.sources.push($(this).attr('data-class'));
        });
        return filters;
    };
    this.filterResults = function(cases){
        var filters = this.getFilters();
        
            
            //all filters deactivated
            if(filters.operation_areas.length === 0 &&
               filters.statuses.length === 0 &&
               filters.sources.length === 0){
                    filtered_cases = cases;
            }else{
                if(filters.operation_areas.length > 0){
                    var filtered_cases = [];
                    $.each(cases,function(index,caseObj){
                        
                        if(filters.operation_areas.contains(caseObj.operation_area)){
                            filtered_cases.push(caseObj);
                        }
                    });
                    var cases = filtered_cases;
                    
                }
                if(filters.statuses.length > 0){
                    var filtered_cases = [];
                    $.each(cases,function(index,caseObj){
                        
                        if(filters.statuses.contains(caseObj.boat_status)){
                            filtered_cases.push(caseObj);
                        }
                    });
                    var cases = filtered_cases;
                    
                }
                
                
            }
        return filtered_cases;
        
    };
    
    this.clusterLayer;
    
    
    this.applyFilters = function(){
        this.generateMarkerCluster(this.filterResults(emergency_cases_obj));
    };
    
    this.generateMarkerCluster = function(cases){
        
        this.clearMap();
        
        
        this.clusterLayer = new L.MarkerClusterGroup().on('click', function(e) {
           
            console.log(e.layer.feature.properties);
            //this.setGeoJSON(geoJson);?
        });
        var geoJsonLayer = L.geoJson(helpers.generateMarkerClusterFeatures(cases),{
        
            pointToLayer: L.mapbox.marker.style,
            style: function(feature) { return feature.properties; }
        });

        this.clusterLayer.addLayer(geoJsonLayer);
        this.map.addLayer(this.clusterLayer);
        
    };
    
    this.clearMap = function(){
        console.log(swApp.clusterLayer);
        if(swApp.clusterLayer)
            swApp.map.removeLayer(swApp.clusterLayer);
    }
}


function initMap(){
        swApp.initMap();
}


$(document).ready(function(){
    initMap();
});
</script>


@stop
