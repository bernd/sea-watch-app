@extends('layouts.app')

@section('content')



          <script src="./js/jquery.min.js" type="text/javascript"></script>
          <script src="./js/cases.js" type="text/javascript"></script>
<script src='https://api.mapbox.com/mapbox.js/v2.2.4/mapbox.js'></script>
<link href='https://api.mapbox.com/mapbox.js/v2.2.4/mapbox.css' rel='stylesheet' />

<script src="https://cdn.rawgit.com/nnattawat/flip/v1.0.19/dist/jquery.flip.min.js"></script>
          <script>
var involved_cases = []; //is used to save involved chat_sessions to init upload
var emergency_case = new function(){
            
        var base_url = '//safe-passage.transparency-everywhere.com/admin/public/';
        this.getInvolved = function(case_id, callback){
            if(involved_cases.indexOf(parseInt(case_id)) === -1)
                involved_cases.push(parseInt(case_id));
            
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
              
              
          $('.caseBox[data-id='+case_id+'] .messenger__chat').append(html);
        };
        this.submitMessage = function(case_id, message){
            var self = this;
            $.post(base_url+'api/cases/sendMessageCrew', {case_id:case_id, message:message},function( result ) {
                
                var result = JSON.parse(result);
                
                if(result.error != null){
                    alert(result.error);
                }else{
                    
                    self.pushChatMessage(case_id, {type:'sent', message:message, message_id:result.data.emergency_case_message_id});
          
                }
            });
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
            request.cases = [];
            $.each(involved_cases, function(index, value){
                
                request.cases.push({id:value, last_message_received:parseInt($('.caseBox[data-id='+value+'] .messenger__chat').attr('data-last-message-received'))});
                
            });
            
            $.post(base_url+'api/reloadBackend',{request: request} ,function( result ) {
                if(result == 'null')
                    return 0;
                $.each(result.data.messages, function(index, value){
                    var case_id = index;
                    self.handleMessageArray(value);
                });
                
            });
                
            
        };
        
};
        
L.mapbox.accessToken = 'pk.eyJ1IjoibmljemVtIiwiYSI6ImNpam02MzNrNzAwMmt2eG0zdXI0ZHYzajAifQ.yi825X8J7cP1upzA1x6Y-Q';

              
$(document).ready(function(){
    $('.get-involved').click(function(e){
        e.preventDefault();
        var case_id = $(this).attr('data-id');
        emergency_case.getInvolved(case_id,function(result){
            
            if(result.error != null){
                alert(result.error);
            }else{
                
                emergency_case.handleMessageArray(result.data.messages);
                
                $('.oparea_'+case_id+' .front').hide();
                $('.oparea_'+case_id+' .back').show();

                $('.oparea_'+case_id+' .close_chat').click(function(){
                    $('.oparea_'+case_id+' .front').show();
                    $('.oparea_'+case_id+' .back').hide();
                });
                
            }
        });
    });
    
    $('.caseBox .form_inline form').submit(function(e){
        e.preventDefault();
        var case_id = $(this).find('input[type=text]').attr('data-id');
        var message = $(this).find('input[type=text]').val();
        
        
        emergency_case.submitMessage(case_id, message);
        
    });
    emergency_case.initReload();
});

</script>


      <div class="row">
        
        
        @include('partials.casenav')
        <div class="col-sm-10 col-md-10 main">
            <ul id="caseList">
                <!--<li class="caseBox on_land oparea_1" data-id="1">
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
                         
                </li>-->
            @foreach ($emergency_cases as $emergency_case)
                <li class="caseBox <?php echo $emergency_case->boat_status;?> oparea_<?php echo $emergency_case->id;?>" data-id="<?php echo $emergency_case->id;?>">
                    
                    <div class="front">
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
                                <!--{{ URL::to('cases/get_involved/'.$emergency_case->id) }}-->
                                <a href="#" data-id="{{$emergency_case->id}}" class="btn btn-sm pull-left get-involved">Get Involved</a>
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
                                                
                                                <?php
                                                foreach ($emergency_case->involved_users() as $user){ ?>
                                                    <li><?php echo $user ?></li>
                                                <?php } ?>
                                            </ul>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                    </div>
                    <div class="back" style="display:none">
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
                            <div class="content messenger">
                                       <div class="messenger__chat container__large">
                                            <!--<div class="user_1 message">
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
                                            </div>-->
                                        </div>
                                        <div class="messenger__form">
                                            <a class="close_chat" href="#"><i class="zmdi zmdi-arrow-left"></i></a>
                                            <div class="form_inline">
                                                <form>
                                                    <input type="text" aria-label="Schreibe einen Text…" data-id="<?php echo $emergency_case->id;?>">
                                                    <button type="button">Senden</button>
                                                </form>
                                            </div>
                                        </div>
                            </div>
                        
                    </div>
                         
                </li>
                
                
                <script>swApp.addMiniMap([<?php echo $emergency_case->last_location()->lat.','.$emergency_case->last_location()->lon;?>], 'map_<?php echo $emergency_case->id;?>');</script>
            @endforeach
                
            </ul>
          
          
          
          
        </div>
      </div>


@stop
