
$(document).ready(function(){
    swApp.init()
});




var swApp = new function(){

  this.apiURL = '//safe-passage.transparency-everywhere.com/admin/public/';
  this.clientId;
  this.emergency_case_id;
  this.last_signal_send; //last signal send to server timestamp in unixtime
  this.reloadInterval = 15000; //reload interval
  this.last_message_received = 0;
  this.reloadIntervalObj;
  
  
  this.init = function(){
  
        var self  = this;
  
        this.clientId = this.getClientId();
  
        //initial call on geolocation api
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
                    
                    if(typeof $('body').attr('data-geo') === 'undefined')
                        self.showStartScreen();
                    
                    
                    $('body').attr('data-geo',JSON.stringify(coords));
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
            
            
            
        },2000);
  
  
  
  
  
  };
  
  this.getClientId = function(){
      return 'clientId';
  }
  this.reload= function(){
      api.query(this.apiURL+'api/reloadApp', {last_message_received: this.last_message_received, emergency_case_id:this.emergency_case_id},function(result){
          if(result.error != null){
              alert(result.error);
          }else{
              $.each(result.data.messages, function(index,value){
                  if($('.message[data-id='+value.id+']').length > 0){
                      
                      console.log('already there');
                  }else{
                      console.log('push message');
                  }
                  console.log(value);
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
  
  this.showStartScreen = function(){
      var self = this;
      
      $('body').load('views/index.html',function(){ 
        $('.language_selector__selector li a').click(function(e){
            e.preventDefault();
            self.showMainScreen();
        });
      });
      
  };
  this.showMainScreen = function(){
      var self = this;
      $('body').load('views/app.html',function(){
          
          //send emergency request
          $('.sos a').click(function(e){
              e.preventDefault();
              
              
              self.sendEmergencyCall(function(){
                self.showChatScreen();
              });
              
              
              
          })
      });
  };
  this.showChatScreen = function(){
      var self = this;
      
      var savedMessages = {};
      
      $('body').removeClass('screen_start');
      $('body').addClass('screen_app');
      
      $('body').load('views/messenger.html',function(){
          
          
          self.pushChatMessage({type:'notification', message:'The App was initialized. Please wait...'});
          
          self.initReload();
          
          //init click on back button
          $('.info').click(function(e){
              e.preventDefault();
              self.showMainScreen();
          });
          
          //init sending 
          $('.form_inline form').submit(function(e){
              
              e.preventDefault();
              self.submitChatMessage({message:$('.form_inline form input[type=text]').val(),'callback':function(result){
                      
                      var result = JSON.parse(result);
                      if(result.error != null){
                          
                      }else{
                          
                          
                            self.pushChatMessage({type:'sent', message:$('.form_inline form input[type=text]').val(), message_id:result.data.emergency_case_message_id});
          
                            $('.form_inline form input[type=text]').val('');
                      }
              }});
          });
          
          
      });
  
  };
  
  this.sendEmergencyCall = function(callback){
  	
    var data = {
            /*'status':$('#boat_status').val(),
            'condition':$('#boat_condition').val(),
            'boat_type':$('#boat_type').val(),
            'other_involved':$('#other_involved').is('checked'),
            'engine_working':$('#engine_working').is('checked'),
            'passenger_count':$('#passenger_count').val(),
            'additional_informations':$('#additional_informations').val(),
            'spotting_distance':$('#spotting_distance').val(),
            'spotting_direction':$('#spotting_direction').val(),
            'picture':$('#picture').val(),*/
            'source':'refugee',
            'location_data':$('body').attr('data-geo')
    };
    var self = this;
    //send api call
    api.query(self.apiURL+'api/cases/create', data, function(result){
        var result = JSON.parse(result);
        self.setLastUpdatedNow();
        if(result.error == null){
            //init chat session
            //self.openEmergencySession(result.data.emergency_case_id);
            self.emergency_case_id = result.data.emergency_case_id;
            callback();
        }else{
            if(result.error === 'no_operation_area'){
                alert('the location you submitted is not in a operation_area of the sea watch');
            }
        }
    });
  };
  
  this.submitChatMessage = function(options){
      
      api.query(this.apiURL+'api/messages/send',{emergency_case_id: this.emergency_case_id ,sender_type:'refugee',sender_id:this.client_id,message:options.message,'geo_data':$('body').attr('data-geo')},function(result){
          if(typeof options.callback === 'function')
              options.callback(result);
      });
      
  };
  this.pushChatMessage = function(options){
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
    var html = '<div class="'+divClass+'" data-id="'+options.message_id+'">'
        html += '    <p class="'+pClass+'">'+options.message+'</p>';
        html += '</div>';
    
    $('.messenger__chat').append(html);
  };
  
  this.setLastUpdatedNow = function(){
      var now = Math.round(new Date().getTime()/1000);
      $('.updated-text').attr('data-last-updated', now).html('Connected to Sea-Watch.org. Last Checked 0s ago');
      
  };
  this.updateLanguage = function(language){
  };

}