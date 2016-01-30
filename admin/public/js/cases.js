
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
}
    

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
                    $('.'+$(this).attr('data-class')).show();
            }
        });
        if(results.join('') === '')
                    $('.caseBox').show()
            
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
    this.addMiniMap = function(location, mapId){
        var map = L.mapbox.map(mapId, 'mapbox.streets')

            .setView(location, 16);

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
                'marker-color': '#BE9A6B'
            }
        }).addTo(map);
    }
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
    
    this.showCaseDetails = function(case_id){
        console.log(case_id);
        this.clearMap;
        var caseObj = this.getCaseData(case_id);
        alert('showing now details for location');
        var self = this
        
        console.log(caseObj);
        $.each(caseObj.locations, function(index,value){
            console.log([parseFloat(value.lat),parseFloat(value.lon)]);
            self.addMarkerToMap([parseFloat(value.lat),parseFloat(value.lon)]);
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
        this.generateMarkerCluster(this.filterResults(emergency_cases_obj));
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
    }
}




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