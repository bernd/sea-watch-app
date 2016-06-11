
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




var emergency_case = new function(){
            
        var base_url = swAppConfig.urlBase;
        
        this.showChat = function(case_id, callback){
            if(swApp.involvedCases.indexOf(parseInt(case_id)) === -1)
                swApp.involvedCases.push(parseInt(case_id));
            
            $.post(base_url+'api/cases/getInvolved', {case_id:case_id,no_involvement:true},function( data ) {
                callback(data);
            });
        };
        
        this.getInvolved = function(case_id, callback){
            if(swApp.involvedCases.indexOf(parseInt(case_id)) === -1)
                swApp.involvedCases.push(parseInt(case_id));
            
            $.post(base_url+'api/cases/getInvolved', {case_id:case_id},function( data ) {
                callback(data);
            });
        };
        this.pushChatMessage = function(case_id, options){
            var divClass, pClass;
            pClass = '';
          if(options.type === 'sent'){
              divClass = "user_2 message";
          }
          if(options.type === 'received'){
              divClass = "user_1 message";
          }
          if(options.type === 'notification'){
              divClass = "chat_status_notification";
              pClass = 'meta';
          }
          
          if(typeof $('.caseBox[data-id='+case_id+'] .messenger__chat').attr('data-last-message-received') == 'undefined'&&
             typeof options.message_id !== 'undefined'){
              $('.caseBox[data-id='+case_id+'] .messenger__chat').attr('data-last-message-received', options.message_id);
          }else if(typeof $('.caseBox[data-id='+case_id+'] .messenger__chat').attr('data-last-message-received') !== 'undefined'){
              if(parseInt(options.message_id) > parseInt( $('.caseBox[data-id='+case_id+'] .messenger__chat').attr('data-last-message-received'))){
                $('.caseBox[data-id='+case_id+'] .messenger__chat').attr('data-last-message-received', options.message_id);
              }
          }
          
          var html = '<div class="'+divClass+'" data-id="'+options.message_id+'">'
              html += '    <p class="'+pClass+'">'+options.message+'</p>';
              html += '</div>';
              
          if($('.message[data-id='+options.message_id+']').length === 0)
            $('.caseBox[data-id='+case_id+'] .messenger__chat').append(html);
        };
        this.submitMessage = function(case_id, message,callback){
            var self = this;
            $.post(base_url+'api/cases/sendMessageCrew', {case_id:case_id, message:message},function( result ) {
                
                var result = JSON.parse(result);
                
                if(result.error != null){
                    alert(result.error);
                }else{
                    
                    self.pushChatMessage(case_id, {type:'sent', message:message, message_id:result.data.emergency_case_message_id});
          
                }
                callback();
                
            });
        };
        
};

    

$(document).ready(function(){
    
    $('#createCase').click(function(){
       $('#createCaseBox').show();
    });
    
    $('#createCaseForm').submit(function(e){
        e.preventDefault();
        var data = $('#createCaseForm').serializeArray();
        //data.push({name: 'wordlist', value: wordlist});
        var postData = {};
        $(data ).each(function(index, obj){
            postData[obj.name] = obj.value;
        });
        
        
        postData.location_data = JSON.stringify({latitude:postData.lat, longitude:postData.lon, heading:0, accuracy: 0});
        $.post("api/cases/create", postData, function(result){
            console.log(result);
            var result = JSON.parse(result);
            if(result.error == null){
                
                alert('case created');
                $('#createCaseBox').slideUp();
            }else{
                alert(result.error);
            }
        });
    });
    
    $('.caseBox').show();
                
    //init masonry
    var $grid = $('#caseList').masonry({
        itemSelector: '.caseBox',
        columnWidth: 100,
        gutter: 10
    });
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
                
        //reinit masonry
        var $grid = $('#caseList').masonry({
            itemSelector: '.caseBox',
            columnWidth: 100,
            gutter: 10,
        });
        
            
    });
    
    
    
});


var helpers = new function(){
    
    this.getMarkerColor = function(boat_status){
        return case_statuses[boat_status].color;
    };
    
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
        };
    }
};

var swApp = new function(){
    
    this.startLocation = [36.19390,19.49248];
    this.mapContainerId = 'maps';
    this.mapLayers = [];
    
    this.involvedCases = []; //list of ids
    this.cases = {} //case objects which also contain the map objects
    this.vehicles = {} //case objects which also contain the map objects
    this.lastUpdated = Math.round(new Date().getTime()/1000);
    
    this.map;
    
    //general init
    this.init = function(){
        this.initReload();
        
        //preload audio file
        $("#bing").trigger('load');
    };
    
    //used to init map in views/pages/home_map
    this.initMap = function(){
        this.map = L.mapbox.map(this.mapContainerId, 'mapbox.streets')
        .setView(this.startLocation, 5);
        
        this.applyFilters();
        var self = this;
        
        this.init();
        
        $('.filter').click(function(){
            //500ms delay otherwise class is not added before filters are applied
            setTimeout(function() {
                self.applyFilters();
            },500);
        });
    };
    
    //used to init mini map in views/pages/home_cases
    this.addMiniMap = function(case_id, mapId){
        //enable two markers in one view(casebox + large map)
        var pseudo_id = 'mapcontainer_'+case_id;
        
        if(typeof this.cases[pseudo_id] == 'undefined')
            this.cases[pseudo_id] = {};
        
        var case_data = this.getCaseData(case_id);
        if(typeof case_data.locations[0] !== 'undefined'){
            console.log(mapId);
            
            this.cases[pseudo_id].map = L.mapbox.map(mapId, 'mapbox.streets').setView([parseFloat(case_data.locations[0].lat), parseFloat(case_data.locations[0].lon)], 16);
        
            this.addCaseToMap(this.cases[pseudo_id].map, case_id, 'mini');
        
            this.cases[pseudo_id].map.scrollWheelZoom.disable();
            
        }else{
            $('#'+mapId).html('<span class="error">No positions available.</span>');
        }
    };
    
    
    //old - not used?
    this.addVesselsToMap = function(){
            var self = this;
            $.get('api/getVehicles',{request: 'request'} ,function( result ) {
                console.log('vessels:');
                console.log('vessels:');
                console.log('vessels:');
                console.log(result);
                $.each(result.vessels, function(index,value){
                    
                        L.mapbox.featureLayer({
                            // this feature is in the GeoJSON format: see geojson.org
                            // for the full specification
                            type: 'Feature',
                            geometry: {
                                type: 'Point',
                                // coordinates here are in longitude, latitude order because
                                // x, y is the standard for GeoJSON and many formats
                                coordinates: [
                                  
                                  value.locations[0].lon,
                                  value.locations[0].lat
                                ]
                            },
                            properties: {
                                title: 'Peregrine Espresso',
                                description: '1718 14th St NW, Washington, DC',
                                // one can customize markers by adding simplestyle properties
                                // https://www.mapbox.com/guides/an-open-platform/#simplestyle
                                'marker-size': 'large',
                                'marker-color': '#121212'
                            }
                        }).on('click', function(e) {
                            console.log(e.layer.feature.properties);
                            //this.setGeoJSON(geoJson);?
                        }).addTo(self.map);
                    
                    
                });
                
            });
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
        
        filters.vehicles = [];
        $('li.vehicle.active').each(function(){
            filters.vehicles.push({id:$(this).attr('data-id')});
        });
        return filters;
    };
    this.filterCases = function(cases){
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
    
    
    
    this.pushChatMessage = function(case_id, options){
            var divClass, pClass;
            pClass = '';
          if(options.type === 'sent'){
              divClass = "user_2 message";
          }
          if(options.type === 'received'){
              divClass = "user_1 message";
          }
          if(options.type === 'notification'){
              divClass = "chat_status_notification";
              pClass = 'meta';
          }
          
          
     
          //check if message is base64 image
          //@sec base64 xss possible?: https://www.owasp.org/index.php/XSS_Filter_Evasion_Cheat_Sheet
           var matches = options.message.match(/III(.+?)III/g);
          if(matches != null){
              options.message = '<img class="chatImage" src="data:image/jpeg;base64,'+matches[0].replace(/III/g,'').replace('"','\"')+'">';
          }
    
          
          if(typeof $('.caseBox[data-id='+case_id+'] .messenger__chat').attr('data-last-message-received') == 'undefined'&&
             typeof options.message_id !== 'undefined'){
              $('.caseBox[data-id='+case_id+'] .messenger__chat').attr('data-last-message-received', options.message_id);
          }else if(typeof $('.caseBox[data-id='+case_id+'] .messenger__chat').attr('data-last-message-received') !== 'undefined'){
              if(parseInt(options.message_id) > parseInt( $('.caseBox[data-id='+case_id+'] .messenger__chat').attr('data-last-message-received'))){
                $('.caseBox[data-id='+case_id+'] .messenger__chat').attr('data-last-message-received', options.message_id);
              }
          }
          
          var html = '<div class="'+divClass+'" data-id="'+options.message_id+'">'
              html += '    <p class="'+pClass+'">'+options.message+'</p>';
              html += '</div>';
              
          if($('.message[data-id='+options.message_id+']').length === 0)
            $('.caseBox[data-id='+case_id+'] .messenger__chat').append(html);
        };
    //handles array of messages and pushes them into the chat
    this.handleMessageArray = function(messageArray){
            var self = this;
            $.each(messageArray,function(index, value){
                var type = 'sent';
                if(value.sender_type === 'refugee'){
                    type = 'received';
                }
                self.pushChatMessage(value.emergency_case_id, {type:type, message:value.message, message_id:value.id});
            });
        };
    
    this.initReload = function(){
        var self = this;
        setInterval(function() {
            self.reload();
        }, 10000);
    }
    this.reload = function(){
            var self = this;
            var request = {};
            request.last_updated = this.lastUpdated;
            
            request.cases = [];
            $.each(this.involvedCases, function(index, value){
                
                request.cases.push({id:value, last_message_received:parseInt($('.caseBox[data-id='+value+'] .messenger__chat').attr('data-last-message-received'))});
                
            });
            
            $.post('api/reloadBackend',{request: request} ,function( result ) {
                self.lastUpdated = Math.round(new Date().getTime()/1000);
                
                
                
                    if(result == 'null')
                        return 0;
                    
                    if(typeof result.data.cases !== 'undefined'){
                        $.each(result.data.cases, function(index, value){
                            emergency_cases_obj.push(value);
                            self.reloadCase(value.id);
                        });
                    }
                    
                    if(typeof result.data.messages !== 'undefined')
                        $.each(result.data.messages, function(index, value){
                            var case_id = index;
                            self.handleMessageArray(value);
                        });
            });
    };
        
        
        
        
    //reloads casebox if casebox exists otherwise creates new casebox
    this.reloadCase = function(case_id){
        console.log('reloadCase');
        
        var self = this;
        if(typeof this.map === 'object'){
            //map mode
            //
            //play sound
            self.bing();
            
            //add caseToMap
            self.addCaseToMap(self.map, case_id);
            
        }else{
            //grid mode
            if($('.caseBox_'+case_id).length > 0){
                //update caseBox
                
                //update polyline and marker
                self.addCaseToMap(self.cases[case_id].map, case_id);
                
            }else{
                //load caseBox
                this.loadCaseBox(case_id,function(result){
                    
                    //play sound
                    self.bing();
                    $('#caseList').prepend(result);
                    self.initClicks();
                    
                    
                    $('.caseBox').css('position', 'relative');
                    //reinit masonry
                    var $grid = $('#caseList').masonry({
                        itemSelector: '.caseBox',
                        columnWidth: 100,
                        gutter: 10,
                    });
                });
            }
        }
        
    };
    this.initClicks = function(){
        var self = this;
        $('.get-involved').click(function(e){
            e.preventDefault();
            var case_id = $(this).attr('data-id');
            emergency_case.getInvolved(case_id,function(result){

                if(result.error != null){
                    alert(result.error);
                }else{

                    self.handleMessageArray(result.data.messages);

                    $('.caseBox_'+case_id+' .front').hide();
                    $('.caseBox_'+case_id+' .back').show();

                    $('.caseBox_'+case_id+' .close_chat').click(function(){
                        $('.caseBox_'+case_id+' .front').show();
                        $('.caseBox_'+case_id+' .back').hide();
                    });

                }
            });
        });
        $('.show-messages').click(function(e){
            e.preventDefault();
            var case_id = $(this).attr('data-id');
            emergency_case.showChat(case_id,function(result){

                if(result.error != null){
                    alert(result.error);
                }else{

                    self.handleMessageArray(result.data.messages);

                    $('.caseBox_'+case_id+' .front').hide();
                    $('.caseBox_'+case_id+' .back').show();

                    $('.caseBox_'+case_id+' .close_chat').click(function(){
                        $('.caseBox_'+case_id+' .front').show();
                        $('.caseBox_'+case_id+' .back').hide();
                    });

                }
            });
        });

        $('.caseBox .form_inline form').submit(function(e){
            e.preventDefault();
            var case_id = $(this).find('input[type=text]').attr('data-id');
            var message = $(this).find('input[type=text]').val();
            var $this = $(this).find('input[type=text]');

            emergency_case.submitMessage(case_id, message,function(){
                $this.val('');
            });

        });

        $('.caseBox .case_settings').click(function(e){
            e.preventDefault();
            var $this = $(this);

            var case_id = $(this).parent().parent().parent().attr('data-id');

            $(this).parent().parent().parent().children('.editCase').load('cases/edit/'+case_id,function(){ 

                $this.parent().parent().parent().children('.front,.back').hide();
                $(this).show();

                var $front = $this.parent().parent().parent().children('.front');
                var $editCase = $(this);

                $(this).children('form').submit(function(e){
                    e.preventDefault();

                    var data = $(this).serialize();

                    $.ajax({
                        type: "POST",
                        url: "cases/edit/"+case_id,
                        data: data,
                        dataType: "json",
                        statusCode: {
                            200: function(data) {
                                if(data === 1){
                                    alert('the case has been updated');
                                    $editCase.hide();
                                    $front.show();
                                }
                            }
                        }
                    });

                });

                $('.closeEditCase').click(function(){
                    $(this).parent().parent().parent().parent().children('.editCase').hide();
                    $(this).parent().parent().parent().parent().children('.front').show();
                });
            });


        });
    };
    this.loadCaseBox = function(case_id, callback){
        $.post('api/loadCaseBox',{request: {case_id:case_id}} ,function( result ) {
            callback(result);
        });
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
    this.getVehicleData = function(id){
        var ret = null;
        $.each(vehicles_obj,function(index, value){
            if(parseInt(value.id) == parseInt(id)){
                ret = value;
            }
        });
        return ret;
    };
    this.clusterLayer;
    
    
    
    
    this.applyFilters = function(){
        
        //clear map
        this.clearMap();
        
        //add cases from object in home_map.blade.php
        $.each(this.filterCases(emergency_cases_obj), function(index,value){
            swApp.addCaseToMap(swApp.map, value.id);
        });
        
        //add vehicles from object in home_map.blade.php
        
        console.log(vehicles_obj);
        $.each(vehicles_obj, function(index,value){
            swApp.addVehicleToMap(swApp.map,  value.id);
        })
        
        
        //this.generateMarkerCluster(this.filterResults(emergency_cases_obj));
    };
    
    this.addCaseToMap = function(map,case_id,suffix){
        
        if(typeof suffix === 'undefined')
            var suffix = '';
        
        var showAll = false;
        
        var self = this;
        
        var real_case_id = case_id;
        var case_id = case_id+suffix;
        
        if(typeof this.cases[case_id] == 'undefined')
            this.cases[case_id] = {};
        else{
            var mapObj;
            if(typeof this.map === 'object')
                mapObj = this.map
            else
                mapObj = this.cases[case_id].map;
           
            if(typeof this.cases[case_id].featureGroup !== 'undefined'&&typeof this.cases[case_id].iconLayer !== 'undefined'){
                mapObj.removeLayer(this.cases[case_id].featureGroup);
                mapObj.removeLayer(this.cases[case_id].iconLayer);
            }
        }
        
        var case_data = this.getCaseData(real_case_id);
        
        
        this.cases[case_id].featureGroup = L.featureGroup().addTo(map);
        var line_points = [];
        $.each(case_data.locations, function(index,value){
            
            if(!showAll&&(index > case_data.locations.length-15))
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
        this.cases[case_id].polyline = L.polyline(line_points, polyline_options).addTo(this.cases[case_id].featureGroup);
        //this.mapLayers.push(polyline);
        var currentIndex = this.mapLayers.length;
        this.cases[case_id].iconLayer = L.mapbox.featureLayer().addTo(map);
        this.cases[case_id].iconLayer.on('click',function(e){
            
                //load caseBox
                self.loadCaseBox(real_case_id,function(result){
                    
                    //play sound
                    $('#caseDetailContainer').html(result);
                    self.initClicks();
                });
            
            console.log(e.layer.feature.properties);
        });
        
        this.mapLayers.push(this.cases[case_id].iconLayer);
        
        if(typeof line_points[0] !== 'undefined'){
            var geoJson = [{
                                type: 'Feature',
                                geometry: {
                                    type: 'Point',
                                    coordinates: [line_points[0][1], line_points[0][0]]
                                },
                                properties: {
                                    title: 'Case-ID:'+real_case_id,
                                    'case-id':real_case_id,
                                    description: 'first tracked: '+case_data.created_at+'<br>last tracked: '+case_data.updated_at,
                                    'marker-color': '#548cba'
                                }
                            }];

             this.cases[case_id].iconLayer.setGeoJSON(geoJson);
        };
        
    };
    
    this.addVehicleToMap = function(map,vehicle_id){
        
        var self = this;
        
        var vehicle_data = this.getVehicleData(vehicle_id);
        

        //apply filters
        var filters = this.getFilters()

        //all filters are looped and if
        //the filter is in the filter.vehicles
        //array. if not => return null
        var proceed = false;

        //only filter if at least one filter is in the array
        if(filters.vehicles.length > 0){
        	$.each(filters.vehicles,function(index, value){
        		if(parseInt(value.id) === parseInt(vehicle_id)){
        			proceed = true;
        		}
        	});
        }else{
        	proceed = true;
        }
        if(!proceed){
        	return null;
        }

        if(typeof this.vehicles[vehicle_id] == 'undefined')
            this.vehicles[vehicle_id] = {};
        else{
            var mapObj;
            if(typeof this.map === 'object')
                mapObj = this.map
            else
                mapObj = this.vehicles[vehicle_id].map;
           
            if(typeof this.vehicles[vehicle_id].featureGroup !== 'undefined'&&typeof this.vehicles[vehicle_id].iconLayer !== 'undefined'){
                mapObj.removeLayer(this.vehicles[vehicle_id].featureGroup);
                mapObj.removeLayer(this.vehicles[vehicle_id].iconLayer);
            }
        }
        console.log(vehicle_data.title);
        
        this.vehicles[vehicle_id].featureGroup = L.featureGroup().addTo(map);
        var line_points = [];
        
        $.each(vehicle_data.locations, function(index,value){

            //only show first 
            if(index < vehicle_data.locations.length){
                line_points.push([parseFloat(value.lat), parseFloat(value.lon)]);
            }
        });
        // Define polyline options
        // http://leafletjs.com/reference.html#polyline
        var polyline_options = {
            color: '#'+vehicle_data.marker_color
        };

        // Defining a polygon here instead of a polyline will connect the
        // endpoints and fill the path.
        // http://leafletjs.com/reference.html#polygon
        this.vehicles[vehicle_id].polyline = L.polyline(line_points, polyline_options).addTo(this.vehicles[vehicle_id].featureGroup);
        //this.mapLayers.push(polyline);
        var currentIndex = this.mapLayers.length;
        this.vehicles[vehicle_id].iconLayer = L.mapbox.featureLayer().addTo(map);
        this.vehicles[vehicle_id].iconLayer.on('click',function(e){
            
                //load vehicleBox
                self.loadCaseBox(vehicle_id,function(result){
                    
                    //play sound
                    $('#caseDetailContainer').html(result);
                    self.initClicks();
                });
        });
        console.log(this.vehicles[vehicle_id].iconLayer);
        
        this.mapLayers.push(this.vehicles[vehicle_id].iconLayer);
        if(typeof line_points[0] !== 'undefined'){
            var geoJson = [{
                                type: 'Feature',
                                geometry: {
                                    type: 'Point',
                                    coordinates: [line_points[0][1], line_points[0][0]]
                                },
                                properties: {
                                    title: 'Vehicle-ID:'+vehicle_id,
                                    'vehicle-id':vehicle_id,
                                    description: 'first tracked: '+vehicle_data.created_at+'<br>last tracked: '+vehicle_data.updated_at,
                                    'marker-color': '#'+vehicle_data.marker_color
                                }
                            }];

            this.vehicles[vehicle_id].iconLayer.setGeoJSON(geoJson);
        };
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
        
        
        $.each(this.cases,function(i,v){
            swApp.map.removeLayer(v.polyline);
        });
        $.each(this.vehicles,function(i,v){
            swApp.map.removeLayer(v.polyline);
        });
        
        $.each(this.mapLayers,function(index,value){
            
            swApp.map.removeLayer(value);
        });
    };
    
    this.bing = function(){
        
           $("#bing").trigger('play');
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