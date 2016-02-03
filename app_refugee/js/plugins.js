// Avoid `console` errors in browsers that lack a console.
(function() {
    var method;
    var noop = function () {};
    var methods = [
        'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',
        'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',
        'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
        'timeline', 'timelineEnd', 'timeStamp', 'trace', 'warn'
    ];
    var length = methods.length;
    var console = (window.console = window.console || {});

    while (length--) {
        method = methods[length];

        // Only stub undefined methods.
        if (!console[method]) {
            console[method] = noop;
        }
    }
}());

// Place any jQuery/helper plugins in here.






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


function loadAfter($jqObject, url, cb){
  $.get(url, function(data){
    $('body header').nextAll().remove();
    $jqObject.after(data);
    cb();
  });
}

window.alert = function (txt) {
   navigator.notification.alert(txt, null, "Alert", "Close");
};
