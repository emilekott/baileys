(function($) {
    $(document).ready(function(){
        $(".map-link").click(function(){
            $("li.button-map a").click();
        });
    });
    
    
})(jQuery);//the correct way of namespacing jquery