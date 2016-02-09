
var case_statuses = {
        'distress':{
            title:'Distress',
            color:'ff4337'
        },
        'rescue_in_progress':{
            title:'In progress',
            color:'ff8942'
        },
        'rescued':{
            title:'Rescued',
            color:'40b0fb'
        },
        'on_land':{
            title:'On Land',
            color:'40b0fb'
        }
};
    

$(document).ready(function(){
    
    
    //filter for casenav
    $('.filter').click(function(e){
        e.preventDefault();
        
        
        if($(this).hasClass('all')){
            if($(this).hasClass('active')){
                $(this).removeClass('active');
            }else{
                $(this).addClass('active');
            }
            $('.filter').not(this).not('.all').removeClass('active');
        }else{
            $(this).prevAll('.all').first().removeClass('active');
            if($(this).hasClass('active')){
                $(this).removeClass('active');
            }else{
                $(this).addClass('active');
            }
        }
        
        $('.caseBox').show();
        $('.caseBox').hide();
        var results = [];
        $('.filter.active').each(function(){
            results.push($(this).attr('data-class'));
            if($(this).attr('data-class')){
                    console.log($(this).attr('data-class'));
                    $('.'+$(this).attr('data-class')).show();
            }
        });
        if(results.join('') === '')
                    $('.caseBox').show();
            
    });
    
});


var helpers = new function(){
    
    this.getMarkerColor = function(boat_status){
        return case_statuses[boat_status].color;
    }
    
    this.generateMarkerClusterFeatures = function(cases){
        
        var features = []
        var self = this;
        $.each(cases, function(index, value){
            features.push({
                    "type": "Feature",
                    
                    "properties": {
                      "marker-color": "#"+self.getMarkerColor(value.boat_status),
                      'case_id':value.id
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
    this.mapLayers = [];
    
    this.map;
    //used to init map in views/pages/home_map
    this.initMap = function(){
        this.map = L.mapbox.map(this.mapContainerId, 'mapbox.streets')
        .setView(this.startLocation, 5);
        
        this.applyFilters();
        var self = this;
        
        $('.filter').click(function(){
            //500ms delay otherwise class is not added before filters are applied
            setTimeout(function() {
                self.applyFilters();
            },500);
        });
    };
    
    //used to init mini map in views/pages/home_cases
    this.addMiniMap = function(case_id, mapId){
        
        
        var case_data = this.getCaseData(case_id);
        
        var map = L.mapbox.map(mapId, 'mapbox.streets').setView([parseFloat(case_data.locations[0].lat), parseFloat(case_data.locations[0].lon)], 16);
        
        this.addCaseToMap(map, case_id);
        
        map.scrollWheelZoom.disable();

//        L.mapbox.featureLayer({
//            // this feature is in the GeoJSON format: see geojson.org
//            // for the full specification
//            type: 'Feature',
//            geometry: {
//                type: 'Point',
//                // coordinates here are in longitude, latitude order because
//                // x, y is the standard for GeoJSON and many formats
//                coordinates: [
//                  location[1],
//                  location[0] 
//                ]
//            },
//            properties: {
//                // one can customize markers by adding simplestyle properties
//                // https://www.mapbox.com/guides/an-open-platform/#simplestyle
//                'marker-size': 'large',
//                'marker-color': '#BE9A6B'
//            }
//        }).addTo(map);
    };
    this.addMarkerToMap = function(location, color){
        
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
                'marker-color': '#'+color,
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
    
    
    //reloads casebox if casebox exists otherwise creates new casebox
    this.reloadCase = function(case_id){
        
    };
    
    this.showCaseDetails = function(case_id){
        console.log(case_id);
        this.clearMap;
        var caseObj = this.getCaseData(case_id);
        alert('showing now details for location');
        var self = this
        
        $.each(caseObj.locations, function(index,value){
            self.addMarkerToMap(helpers.getMarkerColor(caseObj.boat_status),[parseFloat(value.lat),parseFloat(value.lon)]);
        });
        
        
    };
    this.getCaseData = function(id){
        var ret = null;
        $.each(emergency_cases_obj,function(index, value){
            if(parseInt(value.id) == parseInt(id)){
                ret = value;
            }
        });
        return ret;
    };
    this.clusterLayer;
    
    
    this.applyFilters = function(){
        console.log('----------------');
        console.log('active filters:');
        console.log(this.getFilters());
        console.log('results');
        console.log(this.getFilters());
        console.log(this.filterResults(emergency_cases_obj));
        this.clearMap();
        $.each(this.filterResults(emergency_cases_obj), function(index,value){
            swApp.addCaseToMap(swApp.map, value.id);
        });
        //this.generateMarkerCluster(this.filterResults(emergency_cases_obj));
    };
    
    this.addCaseToMap = function(map,case_id){
        
        var case_data = this.getCaseData(case_id);
        var featureGroup = L.featureGroup().addTo(map);
        var line_points = [];
        $.each(case_data.locations, function(index,value){
            line_points.push([parseFloat(value.lat), parseFloat(value.lon)]);
        });

        // Define polyline options
        // http://leafletjs.com/reference.html#polyline
        var polyline_options = {
            color: '#000'
        };

        // Defining a polygon here instead of a polyline will connect the
        // endpoints and fill the path.
        // http://leafletjs.com/reference.html#polygon
        var polyline = L.polyline(line_points, polyline_options).addTo(featureGroup);
        this.mapLayers.push(polyline);
        var currentIndex = this.mapLayers.length;
        this.mapLayers.push(L.mapbox.featureLayer().addTo(map));
        
        var geoJson = [{
                            type: 'Feature',
                            geometry: {
                                type: 'Point',
                                coordinates: [line_points[0][1], line_points[0][0]]
                            },
                            properties: {
                                title: 'Marker one',
                                description: '<em>Wow</em>, this tooltip is breaking all the rules.',
                                'marker-color': '#548cba'
                            }
                        }];

        this.mapLayers[currentIndex].setGeoJSON(geoJson);
        
    };
    
    this.generateMarkerCluster = function(cases){
        
        
        this.clearMap();
        
        
        this.clusterLayer = new L.MarkerClusterGroup().on('click', function(e) {
            console.log('e');
            console.log(e.layer.feature);
            swApp.showCaseDetails(e.layer.feature.properties.case_id);
            
            
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
        if(swApp.clusterLayer)
            swApp.map.removeLayer(swApp.clusterLayer);
        
        
        $.each(this.mapLayers,function(index,value){
            
            swApp.map.removeLayer(value);
        });
    };
};




Array.prototype.contains = function(obj) {
    var i = this.length;
    while (i--) {
        if (this[i] === obj) {
            return true;
        }
    }
    return false;
}


//thx to Darin Dimitrov @ http://stackoverflow.com/questions/1988349/array-push-if-does-not-exist
// check if an element exists in array using a comparer function
// comparer : function(currentElement)
Array.prototype.inArray = function(comparer) { 
    for(var i=0; i < this.length; i++) { 
        if(comparer(this[i])) return true; 
    }
    return false; 
}; 

// adds an element to the array if it does not already exist using a comparer 
// function
Array.prototype.pushIfNotExist = function(element, comparer) { 
    if (!this.inArray(comparer)) {
        this.push(element);
    }
};