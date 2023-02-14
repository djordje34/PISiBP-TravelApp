$(document).ready(function(){

    $('.arrival').click(function(){
        var tmp = $(this);
        $(".arrival").removeClass("active");
        if($(tmp).is('.arrival')) {
            $(tmp).addClass("active");
        }
    });
    
    $('.hotel').click(function(){
        var tmp = $(this);
        $(".hotel").removeClass("active");
        if($(tmp).is('.hotel')) {
            $(tmp).addClass("active");
        }
    });
    
    $('.fa-star').click(function(){
        $(".fa-star").removeClass("active");
        $(this).addClass("active");
        $(this).prevAll().addClass("active");
    });
    
    });