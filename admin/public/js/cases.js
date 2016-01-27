
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
    }
    
    

$(document).ready(function(){
    
    
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
                    $('.'+$(this).attr('data-class')).show();
            }
        });
        if(results.join('') === '')
                    $('.caseBox').show()
            
    });
    
});





Array.prototype.contains = function(obj) {
    var i = this.length;
    while (i--) {
        if (this[i] === obj) {
            return true;
        }
    }
    return false;
}