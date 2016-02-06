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
            
        var base_url = '//app.sea-watch.org/admin/public/';
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
    
    
    emergency_case.initReload();
});

</script>

<!--loggedOut-->
      <div class="row ">
        
        
        @include('partials.casenav')
        <div class="col-sm-10 col-md-10 main">
            <ul id="caseList">
            @foreach ($emergency_cases as $emergency_case)
                <li class="caseBox <?php echo $emergency_case->boat_status;?> type_<?php echo $emergency_case->source_type;?> oparea_<?php echo $emergency_case->operation_area;?> caseBox_<?php echo $emergency_case->id;?>" data-id="<?php echo $emergency_case->id;?>">
                    <div class="front">
                            <header>

                                <span class="time"><?php echo \Carbon\Carbon::createFromTimeStamp(strtotime($emergency_case->created_at))->diffForHumans() ?></span>
                                <span class="connection_type">
                                <?php 
                                echo $emergency_case->first_location()->connection_type;
                                ?></span>

                                <div class="status">
                                    <?php

                                        echo ['distress'=>'Distress',
                                         'rescued'=>'Rescued',
                                         'on_land'=>'On Land',
                                         'rescue_in_progress'=>'In Progress'][$emergency_case->boat_status];

                                    ?>
                                    <span class="source">Refugee</span>
                                </div>

                                <div class="case_settings">
                                        <a href="#"><i class="zmdi zmdi-settings"></i></a>
                                </div>
                                
                            </header>
                            <div class="map" id="map_<?php echo $emergency_case->id;?>"></div>
                            <div class="content">
                                <!--{{ URL::to('cases/get_involved/'.$emergency_case->id) }}-->
                                <a href="#" data-id="{{$emergency_case->id}}" class="btn btn-sm pull-left get-involved">Get Involved</a>
                                <a href="#" class="btn btn-sm pull-right"><?php echo $emergency_case->count_messages() ?></a>
                                <table class="table">


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
                    <div class="editCase content" style="display:none; padding:0 30px;">
                        
                    </div>
                         
                </li>
                
                
                <script>swApp.addMiniMap([<?php echo $emergency_case->last_location()->lat.','.$emergency_case->last_location()->lon;?>], 'map_<?php echo $emergency_case->id;?>');</script>
            @endforeach
                
            </ul>
          
          
          
          
        </div>
      </div>


@stop
