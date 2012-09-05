(function($) {
    $(document).ready(function(){
       $(".view-social-links .views-row").hover(function(){
            $(this).children(".views-field-field-social-icon").hide("fast");
            $(this).children(".views-field-field-social-icon-rollover").show("fast");
            
        },
        function(){
            $(this).children(".views-field-field-social-icon").show("fast");
            $(this).children(".views-field-field-social-icon-rollover").hide("fast");
        });
        
    });
    
    
})(jQuery);//the correct way of namespacing jquery