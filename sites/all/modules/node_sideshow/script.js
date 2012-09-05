(function($) {
    $(document).ready(function(){
        $('.view-id-hero_slider .view-content').after('<div id="slide-nav">')
        .cycle({ 
            fx:     'fade', 
            speed:  'fast', 
            pause: 1,
            pager:  '#slide-nav',
            pagerAnchorBuilder: function(idx, slide) { 
                console.log(Drupal.settings.node_slideshow.image_path);
                return '<li class="hero-pager"><a href="#"><img src="'+ Drupal.settings.node_slideshow.image_path + '/pager-blank.png" /></a></li>'; 
            } 
        });
        
        $(".view-id-trio a").removeClass("no-js");
        
        $(".view-id-trio a").hover(function(){
            $(this).children(".trio-text").fadeIn(300);
            $(this).children(".trio-hover").fadeIn(600);
        },
        function(){
            $(this).children(".trio-text").fadeOut(600);
            $(this).children(".trio-hover").fadeOut(600);
        });
        
        $(".view-featured-products .views-row").hover(function(){
            $(this).children(".views-field-field-product-image").fadeTo('fast', 0.5);
            $(this).children(".views-field-field-subtitle").show("fast");
            
        },
        function(){
            $(this).children(".views-field-field-product-image").fadeTo('fast', 1.0);
            $(this).children(".views-field-field-subtitle").hide("fast");
        });
        
        
    });
    
    
})(jQuery);//the correct way of namespacing jquery