(function($) {
    $(document).ready(function(){
       $('#expand-search').click(function () {
        $("#search-block-form input").toggle("slow");
        $("#search-block-form img").toggle();
       });
        
    });
    
    
})(jQuery);//the correct way of namespacing jquery