@extends('layouts.app')

@section('content')



<script src='https://api.mapbox.com/mapbox.js/v2.2.4/mapbox.js'></script>
<link href='https://api.mapbox.com/mapbox.js/v2.2.4/mapbox.css' rel='stylesheet' />
<script>
L.mapbox.accessToken = 'pk.eyJ1IjoibmljemVtIiwiYSI6ImNpam02MzNrNzAwMmt2eG0zdXI0ZHYzajAifQ.yi825X8J7cP1upzA1x6Y-Q';

function addMiniMap(location, mapId){
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
            'marker-color': '#BE9A6B',
            'marker-symbol': 'cafe'
        }
    }).addTo(map);
}

</script>


      <div class="row">
        
        
        @include('partials.casenav')
        <div class="col-sm-10 col-md-10 main">
            <ul id="caseList">
                <li class="caseBox on_land oparea_1" data-id="1">
                    <header>
                        
                        <span class="time">1 week ago</span>
                        <span class="connection_type">
                        internet</span>
                        
                        <span class="status">
                            On Land                        </span>
                        <span class="source">Refugee</span>
                    </header>
                    <div class="map leaflet-container leaflet-retina leaflet-fade-anim" id="map_1" tabindex="0" style="position: relative;"><div class="leaflet-map-pane" style="transform: translate3d(0px, 0px, 0px);"><div class="leaflet-tile-pane"><div class="leaflet-layer"><div class="leaflet-tile-container"></div><div class="leaflet-tile-container leaflet-zoom-animated"><img class="leaflet-tile leaflet-tile-loaded" src="http://b.tiles.mapbox.com/v4/mapbox.streets/16/34585/21190@2x.png?access_token=pk.eyJ1IjoibmljemVtIiwiYSI6ImNpam02MzNrNzAwMmt2eG0zdXI0ZHYzajAifQ.yi825X8J7cP1upzA1x6Y-Q" style="height: 256px; width: 256px; left: 34px; top: -118px;"><img class="leaflet-tile leaflet-tile-loaded" src="http://a.tiles.mapbox.com/v4/mapbox.streets/16/34584/21190@2x.png?access_token=pk.eyJ1IjoibmljemVtIiwiYSI6ImNpam02MzNrNzAwMmt2eG0zdXI0ZHYzajAifQ.yi825X8J7cP1upzA1x6Y-Q" style="height: 256px; width: 256px; left: -222px; top: -118px;"><img class="leaflet-tile leaflet-tile-loaded" src="http://a.tiles.mapbox.com/v4/mapbox.streets/16/34586/21190@2x.png?access_token=pk.eyJ1IjoibmljemVtIiwiYSI6ImNpam02MzNrNzAwMmt2eG0zdXI0ZHYzajAifQ.yi825X8J7cP1upzA1x6Y-Q" style="height: 256px; width: 256px; left: 290px; top: -118px;"></div></div></div><div class="leaflet-objects-pane"><div class="leaflet-shadow-pane"></div><div class="leaflet-overlay-pane"></div><div class="leaflet-marker-pane"><img src="http://a.tiles.mapbox.com/v4/marker/pin-l-cafe+BE9A6B@2x.png?access_token=pk.eyJ1IjoibmljemVtIiwiYSI6ImNpam02MzNrNzAwMmt2eG0zdXI0ZHYzajAifQ.yi825X8J7cP1upzA1x6Y-Q" class="leaflet-marker-icon leaflet-zoom-animated leaflet-clickable" title="Peregrine Espresso" tabindex="0" style="margin-left: -17.5px; margin-top: -45px; width: 35px; height: 90px; transform: translate3d(150px, 65px, 0px); z-index: 65;"></div><div class="leaflet-popup-pane"></div></div></div><div class="leaflet-control-container"><div class="leaflet-top leaflet-left"><div class="leaflet-control-zoom leaflet-bar leaflet-control"><a class="leaflet-control-zoom-in" href="#" title="Zoom in">+</a><a class="leaflet-control-zoom-out" href="#" title="Zoom out">-</a></div></div><div class="leaflet-top leaflet-right"><div class="leaflet-control-grid map-tooltip leaflet-control" style="display: none;"><a class="close" href="#" title="close">close</a><div class="map-tooltip-content"></div></div></div><div class="leaflet-bottom leaflet-left"><div class="mapbox-logo leaflet-control mapbox-logo-true"></div></div><div class="leaflet-bottom leaflet-right"><div class="map-legends wax-legends leaflet-control" style="display: none;"></div><div class="leaflet-control-attribution leaflet-control leaflet-compact-attribution"><a href="https://www.mapbox.com/about/maps/" target="_blank">© Mapbox</a> <a href="https://openstreetmap.org/about/" target="_blank">© OpenStreetMap</a> <a class="mapbox-improve-map" href="https://www.mapbox.com/map-feedback/#mapbox.streets/9.984/53.518/16" target="_blank">Improve this map</a></div></div></div></div>
                    <div class="content messenger">
                               <div class="messenger__chat container__large">
                <div class="user_1 message">
                    <p>Hi, here is Sea-Watch!
                    Wir suchen nun ein Rettungsteam. Bitte bleibe ruhig und schließe diese App nicht. Kannst du uns sagen wie viele Leute ihr auf dem Boot seid und wie eure Lage aktuell ist.</p>
                </div>
                 <div class="user_2 message">
                    <p>we need help, please rescue, we are 40 people in small boat, children, womans </p>
                </div>
                <div class="chat_status_notification">
                    <p class="meta">Your internet is slow. The App now use "SMS-MODE".</p>
                </div>

                <div class="user_2 message sms_mode">
                   <p class="lonlat">LON: <span class="lon">15.92828</span> · LAT: <span class="lat">17.34454</span></p>
                   <p>Hi, please help! we are sinking.</p>
                </div>
            </div>
            <div class="messenger__form">
                <a class="close_chat" href="#"><i class="zmdi zmdi-arrow-left"></i></a>
                <div class="form_inline">
                    <form>
                        <input type="text" aria-label="Schreibe einen Text…">
                        <button type="button">Senden</button>
                    </form>
            </div>
        </div>
                    </div>
                         
                </li>
            @foreach ($emergency_cases as $emergency_case)
                <li class="caseBox <?php echo $emergency_case->boat_status;?> oparea_<?php echo $emergency_case->id;?>" data-id="<?php echo $emergency_case->id;?>">
                    <header>
                        
                        <span class="time"><?php echo \Carbon\Carbon::createFromTimeStamp(strtotime($emergency_case->created_at))->diffForHumans() ?></span>
                        <span class="connection_type">
                        <?php 
                        echo $emergency_case->first_location()->connection_type;
                        ?></span>
                        
                        <span class="status">
                            <?php
                            
                                echo ['distress'=>'Distress',
                                 'rescued'=>'Rescued',
                                 'on_land'=>'On Land',
                                 'rescue_in_progress'=>'In Progress'][$emergency_case->boat_status];
                            
                            ?>
                        </span>
                        <span class="source">Refugee</span>
                    </header>
                    <div class="map" id="map_<?php echo $emergency_case->id;?>"></div>
                    <div class="content">
                        <a href="{{ URL::to('cases/get_involved/'.$emergency_case->id) }}" class="btn btn-sm pull-left">Get Involved</a>
                        <a href="#" class="btn btn-sm pull-right"><?php echo $emergency_case->count_messages() ?></a>
                        <table>
                            
                            
                            <?php
                            $case_vars = array('boat_status','boat_condition','boat_type','other_involved','engine_working','passenger_count','additional_informations','spotting_distance','spotting_direction','picture','operation_area');
                            
                            foreach($case_vars AS $case_var){
                                ?>
                                <tr>
                                    <td><?php echo $emergency_case->translateColumnName($case_var);?></td>
                                    <td><?php echo $emergency_case->$case_var;?></td>
                                </tr>
                                <?php
                            } ?>
                            <tr>
                                <td>Involved</td>
                                <td>
                                    <ul class="involvedList">
                                        <li>SAR-TEAMS</li>
                                        <li>Sea-Watch Johanna</li>
                                    </ul>
                                </td>
                            </tr>
                        </table>
                    </div>
                         
                </li>
                
                
                <script>addMiniMap([<?php echo $emergency_case->last_location()->lat.','.$emergency_case->last_location()->lon;?>], 'map_<?php echo $emergency_case->id;?>');</script>
            @endforeach
                
            </ul>
          
          
          
          
          <script src="./js/jquery.min.js" type="text/javascript"></script>
          <script src="./js/cases.js" type="text/javascript"></script>

        </div>
      </div>


@stop
