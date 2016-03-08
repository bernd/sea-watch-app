@extends('layouts.app')

@section('content')



<style>
    
    .nav-sidebar li.active{
        background:#c9c9c9;
    }
    
    .caseDetails{
        position:absolute;
        width:300px;
        height:350px;
        background:#fff;
        border: 1px solid #c9c9c9;
        top:50px;
        right:200px;
        border-radius: 3px;
        padding: 5px;
    }
    
    .caseDetails header{
        font-size: 15px;
    }
    
    .caseDetail .locationList{
        max-height: 150px;
        overflow: auto;
        list-style: none;
        padding:0;
    }
    
    .caseDetail .close{
        position: absolute;
        top: 5px;
        right: 5px;
    }
    .caseDetail .edit{
        position: absolute;
        top: 5px;
        right: 20px;
    }
    .ol-overlaycontainer-stopevent{
        display:none;
    }
    
    
</style>

      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
            
            
            
            
          <ul class="nav nav-sidebar">
            <div style="margin: 10px 0px;">
                <div class="btn-group btn-group-justified" role="group" aria-label="...">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-default" onclick="map.getView().setZoom(map.getView().getZoom()-1)"><span class="glyphicon glyphicon-minus"></span></button>
                    </div>
                    <div class="btn-group" role="group">
                      <button type="button" class="btn btn-default"><i class="fa fa-location-arrow"></i></button>
                    </div>
                    <div class="btn-group" role="group">
                      <button type="button" class="btn btn-default" onclick="map.getView().setZoom(map.getView().getZoom()+1)"><span class="glyphicon glyphicon-plus"></span></button>
                    </div>
                </div>
            </div>
            <li><b>Operation Area</b></li>
            @foreach ($operation_areas as $operation_area)
                <li class="op_area" data-id="<?php echo $operation_area->id;?>"><a href="#op_area"><?php echo $operation_area->title;?> <span class="label label-danger pull-right"><?php echo $operation_area->count_open_cases();?></span></a></li>
            @endforeach
            
            <li id="caseHeadline"><b>Cases</b></li>
            <p id="caseHint">Please choose an operation area to show the cases</p>
            <li><input type="checkbox" id="onlyActiveHints" checked> Only show active cases</li>

            <p id="map_control"></p>
            
            
          </ul>
          <!--<ul class="nav nav-sidebar">
            <li><b>Options</b></li>
            <li><a href="">Nav item again</a></li>
          </ul>-->
            <div id="olControlDiv"></div>
        </div>
        <div class="col-sm-9 col-md-10 main">
          <div id="map" class="map"></div>
          
          
          
          <script src="{{ asset('js/jquery.min.js') }}"></script>
          <script src="{{ asset('js/example-behaviour.js') }}"></script>
          <script src="{{ asset('js/fastclick.js') }}"></script>
          <script src="{{ asset('js/loader.js') }}"></script>
          
          <script src="./js/jquery.min.js" type="text/javascript"></script>
          <script src="./js/example-behaviour.js" type="text/javascript"></script>
          <script src="./js/fastclick.js" type="text/javascript"></script>
          
          
          <script type="text/javascript" src="./js/ol.js"></script>
          <script type="text/javascript" src="./js/mobile-full-screen.js"></script>
          
          <script>
              
              var home_map = new function(){
                  this.operation_area;
              }
              
              
                  Number.prototype.padLeft = function(base,chr){
                        var  len = (String(base || 10).length - String(this).length)+1;
                        return len > 0? new Array(len).join(chr || '0')+this : this;
                  }
                  var onClick = function(){};
                  var markers = [];
                  
                  function formatDateLaravel(dateObj, showYear, showDate){
                      if(typeof showYear === 'undefined')
                          var showYear = true;
                      if(typeof showDate === 'undefined')
                          var showDate = true;
                      
                      var d = dateObj;
                      var time_zone_shift = -1; //-1 = german time zone
                      
                      d.setHours(d.getHours()+time_zone_shift);
                      
                      var resultArray = [];
                      if(showYear)
                        resultArray.push(d.getFullYear());
                      if(showDate){
                        resultArray.push((d.getMonth()+1).padLeft());
                        resultArray.push(d.getDate().padLeft());
                      }
                      
                      return resultArray.join('-') +' ' +
                                  [d.getHours().padLeft(),
                                   d.getMinutes().padLeft(),
                                   d.getSeconds().padLeft()].join(':');
                  }
                  
                  var base_url = '//safe-passage.transparency-everywhere.com/admin/public/';
                  var last_updated = formatDateLaravel(new Date());
                  function reload(){
                      api.query(base_url+'api/checkForUpdates', {last_updated:last_updated}, function(result){
                         
                         
                         
                         last_updated = formatDateLaravel(new Date()); 
                      });
                  }
                  function addPolygon(polygon, title){
                        var vectorFeature = new ol.Feature({
                            geometry: new ol.geom.Polygon(polygon),
                            name: 'Vallée de l\'impossible'
                        });
                        vectorFeature.setStyle(new ol.style.Style({
                            fill: new ol.style.Fill({
                                color: '#FF0000',
                                weight: 10
                            }),
                            stroke: new ol.style.Stroke({
                                color: '#FF0000',
                                width: 3
                            }),
                            text: new ol.style.Text({
                                font: '12px Calibri,sans-serif',
                                text: 'Vallée de l\'impossible',
                                textBaseline: 'middle',
                                textAlign: 'center',
                                fill: new ol.style.Fill({
                                    color: '#000'
                                }),
                                stroke: new ol.style.Stroke({
                                    color: '#fff',
                                    width: 3
                                })
                            })
                        }));

                        vectorSrc.addFeature(vectorFeature);

                  }
                  
                  function addMarker(pos, data, onclick){
                      
                      map.unbindAll();
                      if(!data)
                          data = {asd:'asd', test:'test'};
                      
                        //map.getView().setCenter(pos);
                        var pos = ol.proj.transform(pos, 'EPSG:4326', 'EPSG:3857');           
                        var iconFeature = new ol.Feature({
                          geometry: new ol.geom.Point(pos),
                          data:data
                        });
                        var vectorSource = new ol.source.Vector({
                          features: [iconFeature]
                        });
                        var vectorLayer = new ol.layer.Vector({
                          source: vectorSource
                        });
                        var iconStyle = new ol.style.Style({
                          image: new ol.style.Icon({
                            anchor: [0.5, 46],
                            anchorXUnits: 'fraction',
                            anchorYUnits: 'pixels',
                            opacity: 0.75,
                            src: '//www.keenthemes.com/preview/metronic/theme/assets/global/plugins/jcrop/demos/demo_files/image1.jpg'
                          })
                        });
                        map.addLayer(vectorLayer);
                        markers.push(vectorLayer);
                        
                        
                        onClick = function(evt){
                            var feature = map.forEachFeatureAtPixel(evt.pixel,
                                function(feature, layer) {
                                  return feature;
                                });
                            if (feature) {
                                
                              var geometry = feature.getGeometry();
                              var coord = geometry.getCoordinates();
                              var featureProperties = feature.getProperties();
                              if(typeof onclick == 'function'){
                                  if(featureProperties.data !== {})
                                        onclick(featureProperties.data);
                                  else
                                        onclick();
                              }
                            };
                        };
                        
                        map.on('click', onClick);

                      // change mouse cursor when over marker
                      map.on('pointermove', function(e) {
                        if (e.dragging) {
                          //$(element).popover('destroy');
                          return;
                        }
                        var pixel = map.getEventPixel(e.originalEvent);
                        var hit = map.hasFeatureAtPixel(pixel);
                      });
                        
                    }
                    
                    function parseDate(dateString){
                        
                        var d = new Date(dateString);

                        var curr_date = d.getDate();

                        var curr_month = d.getMonth();

                        var curr_year = d.getFullYear();

                        return curr_date+"."+curr_month+" "+curr_year+' '+d;
                        
                    }
                    
                    function dateToTitle(date){
                        return date;
                    }
                    
                    function showCasesForOperationArea(cases){
                        
                        $.each(cases, function(index, caseData){
                            
                            if(caseData.boat_status === 'rescued'){
                                if($('#onlyActiveHints').is(':checked')){
                                    
                                }else{
                                    var casePositions = caseData.locations;
                                    addCaseMarker(casePositions[0], caseData);
                                    
                                }
                            }else{
                                
                               var casePositions = caseData.locations;
                               addCaseMarker(casePositions[0], caseData);
                            }
                        });
                    }
                    
                    function showSingleLocationMarker(lat, lon){
                        removeAllMarkers();
                        addMarker([parseFloat(lon), parseFloat(lat)], {}, function(){});
                    }
                    
                    function removeAllMarkers(){
                        map.unbindAll();
                        $.each(markers,function(index,value){
                            map.removeLayer(value);
                        });
                        markers = [];
                    }
                    var test = 0;
                    function addCaseMarker(casePositionData, caseData){
                        //removeAllMarkers();
                        var value = casePositionData;
                        var self = this;
                                    //removeAllMarkers();
                                    
                        addMarker([parseFloat(value.lon-test), parseFloat(value.lat-test)], caseData,function(caseData){
                                    //workaround
                                    self.showCaseDetails(caseData);
                        });
                        
                    }
                    
                    function showCaseDetails(caseData){
                                    //workaround
                                    if(JSON.stringify(caseData) != '{}'){
                                        removeAllMarkers();
                                        $('.caseDetails').remove();
                                        $('body').append('<div class="caseDetails"></div>');
                                        var html = '<div class="caseDetail">';
                                                html += '<header>';
                                                html += dateToTitle(caseData.created_at);
                                                html += '</header>';

                                                html += '<a href="#" class="close" onclick="$(\'.caseDetails\').remove()"><i class="glyphicon glyphicon-remove"></i></a>';
                                                html += '<a href="cases/edit/'+caseData.id+'" class="edit"><i class="glyphicon glyphicon-edit"></i></a>';

                                                    console.log(caseData);
                                                    console.log('caseData');

                                                $.each(caseData, function(index, value){
                                                    //console.log(index+value);
                                                    if(index === 'id'||
                                                       index === 'passenger_count'||
                                                       index === 'boat_status'||
                                                       index === 'boat_condition'||
                                                       index === 'boat_type'||
                                                       index === 'spotting_direction'||
                                                       index === 'spotting_distance'||
                                                       index === 'engine_working'){
                                                        html += '<div style="clear:both">';
                                                            html += '<div style="width:150px; overflow:hidden" class="pull-left">'+index+'</div>';
                                                            html += '<div class="pull-left">';
                                                                html += value;
                                                            html += '</div>';
                                                        html += '</div>';

                                                    }


                                                });

                                                    html += '<div style="clear:both">';
                                                        html += 'Known Positions';
                                                    html += '</div>';
                                                    html += '<ul class="locationList">';
                                                        var n = 0;
                                                        $.each(caseData.locations, function(index, value){
                                                            addMarker([parseFloat(value.lon)-n, parseFloat(value.lat)-n], {}, function(){});
                                                            html += '<li onclick="showSingleLocationMarker('+value.lat+','+value.lon+');">'+formatDateLaravel(new Date(value.created_at), false, false)+'</li>';
                                                        });
                                                    html += '</ul>';
                                            html += '</div>';
                                        $('.caseDetails').append(html);
                                    }
                        
                    }
                    
              $(document).ready(function(){
                  var base_url = '//safe-passage.transparency-everywhere.com/admin/public/';
                  
                  
                  
                  function loadOperationArea(operation_area_id){
                    home_map.operation_area = operation_area_id;
                    $.get(base_url+'api/cases/operation_area/'+operation_area_id, function( data ) {
                        
                        $('.case').removeClass('active');
                        $('.nav-sidebar li.op_area').removeClass('active');
                        $('.nav-sidebar li.op_area[data-id='+operation_area_id+']').addClass('active');
                        $('.nav-sidebar .case').remove();
                        
                        
                        //get center of polygon and move map to it
                        var polygon = [];
                        console.log(data.polygon_coordinates);
                        addPolygon(data.polygon_coordinates, 'ads');
                        $.each(JSON.parse(data.polygon_coordinates), function(index, value){
                            polygon.push({"y":value[0], "x":value[1]});
                        });
                        region = new Region(polygon);

                        var center = region.centroid();
                        map.getView().setCenter(ol.proj.transform([center.y, center.x], 'EPSG:4326', 'EPSG:3857'));
                        //view.setCenter(ol.proj.fromLonLat(olCoordinates));
                        view.setZoom(12);
                        
                        //addMarker([center.y, center.x]);
                        
                        var sitePoints = [];
                        var coordinates = JSON.parse(data.polygon_coordinates);
                        
                        
                        if(data.emergency_cases){
                            removeAllMarkers();
                            $('#caseHint').remove();
                            $.each(data.emergency_cases, function(index, value){
                                
                                
                                $listElement = $('<li class="case"><a href="#">'+formatDateLaravel(new Date(value.created_at), false)+'</a></li>');
                                
                                
                                if((value.boat_status !== 'rescued')&&($('#onlyActiveHints').is(':checked'))){
                                    $listElement.css('display', 'none');
                                }
                                
                                $listElement.click(function(e){
                                    
                                    $('.case').removeClass('active');
                                    $(this).addClass('active');
                                    
                                    removeAllMarkers();
                                    console.log([parseFloat(value.locations[0].lon), parseFloat(value.locations[0].lat)]);
                                    addCaseMarker(value.locations[0], value);
                                    //addCaseMarker(casePositionData, caseData);
                                });
                                $('#caseHeadline').after($listElement);
                            });
                            showCasesForOperationArea(data.emergency_cases);
                        }
                        
                    });

                  }
                  $('.nav-sidebar li.op_area').click(function(){
                    loadOperationArea($(this).attr('data-id'));
                  });
                  $('#onlyActiveHints').change(function(){
                      loadOperationArea(home_map.operation_area);
                  });
              });
          </script>
          
        </div>
      </div>


@stop
