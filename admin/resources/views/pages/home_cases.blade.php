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
var emergency_case = new function(){
            
        var base_url = '//app.sea-watch.org/admin/public/';
        
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
        
L.mapbox.accessToken = 'pk.eyJ1IjoibmljemVtIiwiYSI6ImNpam02MzNrNzAwMmt2eG0zdXI0ZHYzajAifQ.yi825X8J7cP1upzA1x6Y-Q';

              
$(document).ready(function(){
    swApp.initClicks();
    swApp.init();
});

</script>

<!--loggedOut-->
      <div class="row ">
        
        
        @include('partials.casenav')
        <div class="col-sm-10 col-md-10 main">
            <ul id="caseList">
            @foreach ($emergency_cases as $emergency_case)
                
                @include('partials.case_box')
                
            @endforeach
                
            </ul>
          
          
          
          
        </div>
      </div>


@stop
