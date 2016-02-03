



var app = {
    // Application Constructor
    initialize: function() {
        this.bindEvents();
    },
    // Bind Event Listeners
    //
    // Bind any events that are required on startup. Common events are:
    // 'load', 'deviceready', 'offline', and 'online'.
    bindEvents: function() {
        document.addEventListener('deviceready', this.onDeviceReady, false);
    },
    // deviceready Event Handler
    //
    // The scope of 'this' is the event. In order to call the 'receivedEvent'
    // function, we must explicitly call 'app.receivedEvent(...);'
    onDeviceReady: function() {
        $(document).ready(function(){
            swApp.init();
        });
        
    }
};



var swApp = new function(){

  this.apiURL = 'https://safe-passage.transparency-everywhere.com/admin/public/';
  this.clientId;
  this.emergency_case_id;
  this.operation_area;
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
            
            
            if (true) {
                
                self.showStartScreen();
                
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
      var self = this;
      api.query(this.apiURL+'api/reloadApp', {last_message_received: this.last_message_received, emergency_case_id:this.emergency_case_id, geo_data:$('body').attr('data-geo')},function(result){
          if(result.error != null){
              alert(result.error);
          }else{
            self.setStatusMonitorNow();
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
  
  this.showStartScreen = function(){
      var self = this;
      
      loadAfter($('body header'),'views/index.html',function(){
        $('body header').hide();
        $('body').removeClass('screen_app');
        $('body').addClass('screen_start');
        $('.language_selector__selector li a').click(function(e){
            e.preventDefault();
            self.showMainScreen();
        });
      });
      
  };
  
  this.confirmCall = function(cb){
        if (confirm("Are you sure to send an emergency call?")) {
            cb();
        }
  };
  
  this.showMainScreen = function(){
      var self = this;
      
      loadAfter($('body header'), 'views/app.html', function(){
          $('body header').show();
          
          //change classes
          $('body').removeClass('screen_start');
          $('body').addClass('screen_app');
          //init click handler
          $('.sos a').bind('click',function(e){
              e.preventDefault();
              
              if(typeof $('body').attr('data-geo') === 'undefined'){
                  alert('your connection hasn\'t been tracked yet. please wait');
              }else{
                self.confirmCall(function(){
                     //proceed
                     $('.sos a').unbind('click');
                     $('.sos a').click(function(e){
                         e.preventDefault();
                         alert('your request is pending... please wait');
                     });
                     self.sendEmergencyCall(function(){
                       self.showChatScreen();
                     });
                });
              }
               
          });
      });
  };
  this.showChatScreen = function(){
      var self = this;
      
      var savedMessages = {};
      
      $('body').removeClass('screen_start');
      $('body').addClass('screen_app');
      
      loadAfter($('body header'), 'views/messenger.html', function(){
          
          self.pushChatMessage({type:'received', message:'Hello, we received your emergency call. Right now you are in are in the operation area '+self.operation_area+'. Please keep you App opened and follow the instructions.'});
          
          self.initReload();
          
          //init click on back button
          $('.info').click(function(e){
              e.preventDefault();
              self.showMainScreen();
          });
          
          $('.close_chat').click(function(e){
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
                            self.last_message_received = result.data.emergency_case_message_id;
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
    $.post(self.apiURL+'api/cases/create', data, function(result){
        var result = JSON.parse(result);
        self.setStatusMonitorNow();
        if(result.error == null){
            //init chat session
            //self.openEmergencySession(result.data.emergency_case_id);
            self.emergency_case_id = result.data.emergency_case_id;
            
            self.operation_area = result.data.operation_area;
            
            setInterval(function(){
                self.checkConnection();
                self.updateStatusMonitor();
            }, 5000);
            
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
      console.log(options);
      var divClass, pClass;
      pClass = '';
    if(options.type == 'sent'){
        divClass = "user_2 message";
    }
    if(options.type == 'received'){
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
  
  this.checkConnection = function(){
      if(true){
          $('.status_monitor__connection').html('Stable Connection');
      }
      
  };
  
  this.setStatusMonitorNow = function(){
      var now = Math.round(new Date().getTime()/1000);
      $('.status_monitor__gps').attr('data-last-updated', now).html('Sent Position 1s ago');
  };
  this.updateStatusMonitor = function(){
      var diff = Math.round(new Date().getTime()/1000-parseInt($('.status_monitor__gps').attr('data-last-updated')));
      
      $('.status_monitor__gps').html('Send Position '+diff+'s ago');
  };
  this.updateLanguage = function(language){
  };

};

var isApp = document.URL.indexOf( 'http://' ) === -1 && document.URL.indexOf( 'https://' ) === -1;
if ( isApp ) {
    // PhoneGap application
    app.initialize();
} else {
    // Web page
    swApp.init();
}  
