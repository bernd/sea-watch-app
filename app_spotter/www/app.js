
var api = new function(){
    
    this.multiQuery = function(action, parameters, callback){
        if(parameters instanceof Array)
            return api.query(action, { request: parameters}, callback);
        else
            return api.query(action, { request: [parameters]}, callback);
    };
    
    this.query = function(action, parameters, callback){

        var url = action;

        var async;
        if(typeof callback !== 'undefined'){
            async = true;
        }else{
            async = false;
        };
        var result;
        $.ajax({
            type: 'POST',
            url: url,
            data: $.param(parameters),
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success:function(data){
                if(!async){
                    try
                    {
                        result = JSON.parse(data);
                    }
                    catch(e)
                    {
                       result = data;
                    }
                }else{
                    result = callback(data);
                    
                }
            },
            async:async
        });
        return result;
    };
    this.loadSource = function(URL, callback){
        
        var async;
        if(typeof callback !== 'undefined'){
            async = true;
        }else{
            async = false;
        };
        var result;
        $.ajax({
            type: 'POST',
            url: 'api/getPage/',
            data: {url: URL},
            success:function(data){
                if(!async){
                    try
                    {
                        result = JSON.parse(data);
                    }
                    catch(e)
                    {
                       result = data;
                    }
                }else{
                    result = callback(data);
                    
                }
            },
            async:async
        });
        return result;
    };
    
    
};



//http://anthonyterrien.com/knob/
function initDegreeWheel(){
    var dialTwo = JogDial(document.getElementById('jog_dial_two'), 
		{debug:true, wheelSize:'260px', knobSize:'100px', degreeStartAt: 0})
		.on('mousemove', function(evt){
                        $('#spotting_direction').val(Math.round(evt.target.degree));
			$('#jog_dial_two_meter').text(Math.round(evt.target.degree)+'°');
		});
}








var app = new function(){

  this.apiURL = 'https://app.sea-watch.org/admin/public';
  this.last_message_received = 0;
  this.client_id = 'test';
  this.reloadInterval = 30000;


  this.reload= function(){
      var self = this;
      api.query(this.apiURL+'/api/reloadApp', {last_message_received: this.last_message_received, emergency_case_id:this.emergency_case_id, geo_data:$('#init_geodata').val()},function(result){
          if(result.error != null){
              alert(result.error);
          }else{
            self.setLastUpdatedNow();
            $.each(result.data.messages,function(index, value){
                var type = 'received';
                if(value.sender_type === 'refugee'){
                    type = 'sent';
                }
                self.pushChatMessage({type:type, message:value.message, message_id:value.id});
            });
          }
      });
  };
  this.initReload = function(){
      var self = this;
      this.reloadIntervalObj = setInterval(function() {
                                                    self.reload();
      }, this.reloadInterval);
  }
  
  
  this.submitChatMessage = function(options){
      
      api.query(this.apiURL+'/api/messages/send',{emergency_case_id: this.emergency_case_id ,sender_type:'land_operator',sender_id:this.client_id,message:options.message,'geo_data':$('body').attr('data-geo')},function(result){
          if(typeof options.callback === 'function')
              options.callback(result);
      });
      
  };
  this.pushChatMessage = function(options){
      console.log(options);
      var divClass, pClass;
      pClass = '';
    if(options.type == 'sent'){
        divClass = "user_2 message";
    }
    if(options.type == 'received'){
        this.bing();
        divClass = "user_1 message";
    }
    if(options.type == 'notification'){
        divClass = "chat_status_notification";
        pClass = 'meta';
    }
    
    var html = '<div class="'+divClass+'" data-id="'+options.message_id+'">'
        html += '    <p class="'+pClass+'">'+options.message+'</p>';
        html += '</div>';
        
      if($('.message[data-id='+options.message_id+']').length === 0){
        $('.messenger__chat').append(html);
      }
  };

  this.init = function(){
      this.initLastUpdatedReloader();
      var self = this;
      $(document).ready(function(){
          
             $("input[type=number]").keydown(function (e) {
                // Allow: backspace, delete, tab, escape, enter and .
                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                     // Allow: Ctrl+A
                    (e.keyCode == 65 && e.ctrlKey === true) ||
                     // Allow: Ctrl+C
                    (e.keyCode == 67 && e.ctrlKey === true) ||
                     // Allow: Ctrl+X
                    (e.keyCode == 88 && e.ctrlKey === true) ||
                     // Allow: home, end, left, right
                    (e.keyCode >= 35 && e.keyCode <= 39)) {
                         // let it happen, don't do anything
                         return;
                }
                // Ensure that it is a number and stop the keypress
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            });
          
          
        initDegreeWheel();
        
        $('#emergency_form').submit(function(e){
            e.preventDefault();
            self.sendEmergencyCall();
            
            
        });
        var options = { timeout: 90000, enableHighAccuracy: true, maximumAge: 10000 };
        var timeout = setTimeout( function() {
            
            
            if (Modernizr.geolocation) {
                navigator.geolocation.watchPosition (
                  function (position) {
                    var newPosition = {
                        timestamp: position.timestamp,
                        coords:position.coords
                    };


                    if(JSON.stringify(newPosition) === '{}'){
                        alert('can not track your position 1');
                    }
                    
                    var coords = {
                        "speed":position.coords.speeding,
                        "heading":position.coords.heading,
                        "altitudeAccuracy":position.coords.altitudeAccuracy,
                        "accuracy":position.coords.accuracy,
                        "altitude":position.coords.altitude,
                        "longitude":position.coords.longitude,
                        "latitude":position.coords.latitude
                    };
                    

                    $('#init_geodata').val(JSON.stringify(coords));
                    $('input#submit').show();
                  },
                  function (error) {
                    var errorTypes = {
                      0: "Unknown error",
                      1: "Permission denied by user",
                      2: "Position is not available",
                      3: "Request timed out"
                    };

                    var errorMessage = errorTypes[error.code];

                    if (error.code == 0 || error.code == 2) {
                      errorMessage += (": " + error.message);
                    }

                    alert(errorMessage);
                  });
              }
              else {
                alert("Geolocation support is not available.");
              }
            
            
            
        },5000);
        
      });
  };
  
  
  this.initLastUpdatedReloader = function(){
      
      var now = Math.round(new Date().getTime()/1000);
      $('.updated-text').attr('data-last-updated', now).html('Connected to Sea-Watch.org. Last Checked 0s ago');
      
            var intervall = setInterval(function() {
                
                var now = Math.round(new Date().getTime()/1000);
                var last_updated = $('.updated-text').attr('data-last-updated');
                $('.updated-text').html('Connected to Sea-Watch.org. Last Checked '+(parseInt(now)-parseInt(last_updated))+'s ago');
      
            }, 4000);
  };
  this.setLastUpdatedNow = function(){
      var now = Math.round(new Date().getTime()/1000);
      $('.updated-text').attr('data-last-updated', now).html('Connected to Sea-Watch.org. Last Checked 0s ago');
      
  };
  
  this.openEmergencySession = function(emergency_case_id){
      this.emergency_case_id = emergency_case_id;
            var self = this;
 
           
      var html = '<div class="row">';
            html += '<p>We received your emergency call.</p>';
          html += '</div>';
      $('.content').load('messenger.html',function(){
          
          
          self.pushChatMessage({type:'received', message:'Hello, we received your emergency call. Right now you are in are in the operation area '+self.operation_area+'. Your Case-ID is '+self.emergency_case_id+'.Please keep you App opened and follow the instructions.'});
          
          self.initReload();
          
          //init sending 
          $('.form_inline form').submit(function(e){
              
              e.preventDefault();
              self.submitChatMessage({message:$('.form_inline form input[type=text]').val(),'callback':function(result){
                      
                      var result = JSON.parse(result);
                      if(result.error != null){
                          
                      }else{
                          
                          
                            self.pushChatMessage({type:'sent', message:$('.form_inline form input[type=text]').val(), message_id:result.data.emergency_case_message_id});
                            self.last_message_received = result.data.emergency_case_message_id;
                            $('.form_inline form input[type=text]').val('');
                      }
              }});
          });
          
      });
  };

  this.sendEmergencyCall = function(callback){

    var data = {
            'status':$('#boat_status').val(),
            'condition':$('#boat_condition').val(),
            'boat_type':$('#boat_type').val(),
            'other_involved':$('#other_involved').is('checked'),
            'engine_working':$('#engine_working').is('checked'),
            'passenger_count':$('#passenger_count').val(),
            'additional_informations':$('#additional_informations').val(),
            'spotting_distance':$('#spotting_distance').val(),
            'spotting_direction':$('#spotting_direction').val(),
            'picture':$('#picture').val(),
            'location_data':$('#init_geodata').val()
    };
    var self = this;
    //send api call
    api.query(self.apiURL+'/api/cases/create', data, function(result){
        var result = JSON.parse(result);
        self.setLastUpdatedNow();
        if(result.error == null){
            
            //init chat session
            self.openEmergencySession(result.data.emergency_case_id);
            
        }else{
            if(result.error === 'no_operation_area'){
                alert('the location you submitted is not in a operation_area of the sea watch');
            }
        }
        
        
    });
    
  };
  this.bing = function(){
    $("#bing").trigger('play');
  };
};

app.init();