(function($) {
  
    //add this script to module and only call it on product finder page...
    Drupal.behaviors.node_slideshow = {
        attach: function (context, settings) {
           $(".map-view").click(function(){
               //remove active class
               $("ul.display-menu li a").removeClass("active");
               $("a.map-view").addClass("active");
               //$(".view-property-search .view-content").hide();
               //$(".view-property-search .item-list").hide();
               $(".view-property-search .attachment").show();
           });
           
           $(".list-view").click(function(){
               $("ul.display-menu li a").removeClass("active");
               $("a.list-view").addClass("active");
               $(".view-property-search .view-content").show();
               $(".view-property-search .attachment").hide();
           });
        }
    }
  
   
    
})(jQuery);//the correct way of namespacing jquery